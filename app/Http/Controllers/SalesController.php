<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Sales;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Events\PurchaseOutStock;
use App\Models\OrderProductions;
use App\Models\PaymentMethods;
use App\Notifications\StockAlert;
use Carbon\Carbon;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "sales";
        $products = Product::get();
        $payments = PaymentMethods::get();
        // $start_month = Carbon::now()->startOfMonth()->format('Y-m-d');
        $date = Carbon::now()->format('Y-m-d');
        $start_month = Carbon::now()->format('Y-m-d');

        if(isset($_GET['date_paid_start'])){
            $sales = Sales::whereBetween('date_paid', [$_GET['date_paid_start'], $_GET['date_paid_end']])->with('product')->latest()->get();
        }else{
            $sales = Sales::whereBetween('date_paid', [$start_month, $date])->with('product')->latest()->get();
        }

        $total_sales = $sales->where('status_sale', 1)->sum('paid');
        $total_sales_pix = $sales->where('status_sale', 1)->where('payment_method', 1)->sum('paid');
        $total_sales_cash = $sales->where('status_sale', 1)->where('payment_method', 2)->sum('paid');

        $count_sales = $sales->count();
        $count_sales_paid = $sales->where('status_sale', 1)->count();

        $users = User::all();
                
        return view('sales',compact(
            'title','products','sales', 'payments', 'date', 'total_sales',
            'total_sales_pix', 'total_sales_cash', 'count_sales', 'count_sales_paid', 'users', 'start_month'
        ));
    }

    public function salesPublic($login){
        $usuario = User::where('email', $login)->get()->first();
        $title = "sales";
        $products = Product::get();
        $payments = PaymentMethods::get();
        // $start_month = Carbon::now()->startOfMonth()->format('Y-m-d');
        $date = Carbon::now()->format('Y-m-d');
        $start_month = Carbon::now()->format('Y-m-d');

        if(isset($_GET['date_paid_start'])){
            $sales = Sales::whereBetween('date_paid', [$_GET['date_paid_start'], $_GET['date_paid_end']])
            ->with('product')
            ->where('user_id', $usuario->id)
            ->latest()->get();
        }else{
            $sales = Sales::whereBetween('date_paid', [$start_month, $date])
            ->with('product')
            ->where('user_id', $usuario->id)
            ->latest()->get();
        }

        $total_sales = $sales->where('status_sale', 1)->sum('paid');
        $total_sales_pix = $sales->where('status_sale', 1)->where('payment_method', 1)->sum('paid');
        $total_sales_cash = $sales->where('status_sale', 1)->where('payment_method', 2)->sum('paid');

        $count_sales = $sales->count();
        $count_sales_paid = $sales->where('status_sale', 1)->count();

        $users = User::all();
                
        return view('sales-public',compact(
            'title','products','sales', 'payments', 'date', 'total_sales',
            'total_sales_pix', 'total_sales_cash', 'count_sales', 'count_sales_paid', 'users',
            'start_month', 'usuario'
        ));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'product'=>'required',
            'quantity'=>'required|min:1'
        ]);
        $sold_product = Product::find($request->product);
        
        /**update quantity of
            sold item from
         purchases
        **/
        $quantity_formated = preg_replace('/[,]/', '.', $request->quantity); 

        $purchased_item = Purchase::find($sold_product->purchase->id);
        $new_quantity = ($purchased_item->quantity) - ($quantity_formated);
        $notification = '';
        if (!($new_quantity < 0)){

            $purchased_item->update([
                'quantity'=>$new_quantity,
            ]);

            /**
             * calcualting item's total price
            **/
            $total_price = ($quantity_formated) * ($sold_product->price);
            $price_formated = preg_replace('/[,]/', '.', $total_price); 
            $paid_formated = preg_replace('/[,]/', '.', $request->paid); 
            $sale = Sales::create([
                'product_id'=>$request->product,
                'quantity'=>$quantity_formated,
                'total_price'=>$total_price,
                'customer'=>$request->customer,
                'payment_method'=>$request->payment_method,
                'paid'=>$paid_formated,
                'description'=>$request->description,
                'status_sale'=>$request->status_sale,
                'date_paid'=>$request->date_paid,
                'user_id'=>auth()->user()->id,
                'partial_sale'=>isset($request->partial_sale) ? $request->partial_sale : null
            ]);

            OrderProductions::create([
                'user_id' => null,
                'sale_id' => $sale->id,
                'status' => null
            ]);

            $notification = array(
                'message'=>"Entrada cadastrada",
                'alert-type'=>'success',
            );
        } 
        if($new_quantity <=1 && $new_quantity !=0){
            // send notification 
            // $product = Purchase::where('quantity', '<=', 1)->first(); // legacy rules
            $product = Purchase::where('id', $purchased_item->id)->first(); // new rules
            event(new PurchaseOutStock($product));
            // end of notification 
            $notification = array(
                'message'=>"Produto insuficiente no estoque!!",
                'alert-type'=>'danger'
            );
            
        }
        return back()->with($notification);
    }

    public function storePublic(Request $request)
    {
        $this->validate($request,[
            'product'=>'required',
            'quantity'=>'required|min:1'
        ]);
        $sold_product = Product::find($request->product);
        $user = User::where('email', $request->login)->get()->first();
        
        /**update quantity of
            sold item from
         purchases
        **/
        $quantity_formated = preg_replace('/[,]/', '.', $request->quantity); 

        $purchased_item = Purchase::find($sold_product->purchase->id);
        $new_quantity = ($purchased_item->quantity) - ($quantity_formated);
        $notification = '';
        if (!($new_quantity < 0)){

            $purchased_item->update([
                'quantity'=>$new_quantity,
            ]);

            /**
             * calcualting item's total price
            **/
            $total_price = ($quantity_formated) * ($sold_product->price);
            $price_formated = preg_replace('/[,]/', '.', $total_price); 
            $paid_formated = preg_replace('/[,]/', '.', $request->paid); 
            $sale = Sales::create([
                'product_id'=>$request->product,
                'quantity'=>$quantity_formated,
                'total_price'=>$total_price,
                'customer'=>$request->customer,
                'payment_method'=>$request->payment_method,
                'paid'=>$paid_formated,
                'description'=>$request->description,
                'status_sale'=>$request->status_sale,
                'date_paid'=>$request->date_paid,
                'user_id'=>$user->id,
                'partial_sale'=>isset($request->partial_sale) ? $request->partial_sale : null
            ]);

            OrderProductions::create([
                'user_id' => null,
                'sale_id' => $sale->id,
                'status' => null
            ]);

            $notification = array(
                'message'=>"Entrada cadastrada",
                'alert-type'=>'success',
            );
        } 
        if($new_quantity <=1 && $new_quantity !=0){
            // send notification 
            // $product = Purchase::where('quantity', '<=', 1)->first(); // legacy rules
            $product = Purchase::where('id', $purchased_item->id)->first(); // new rules
            event(new PurchaseOutStock($product));
            // end of notification 
            $notification = array(
                'message'=>"Produto insuficiente no estoque!!",
                'alert-type'=>'danger'
            );
            
        }
        
        return back()->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        // $this->validate($request,[
        //     // 'product'=>'required',
        //     'quantity'=>'required|integer'
        // ]);
        $sale = Sales::find($request->id);
        $sold_product = Product::find($sale->product_id);
        
        $purchased_item = Purchase::find($sold_product->purchase->id);
        // $new_quantity = ($purchased_item->quantity) - ($request->quantity);
        $new_quantity = $purchased_item->quantity;
        if ($new_quantity > 0){

            if($request->status_sale == 'status_current'){
                $status_sale = $sale->status_sale;
            }else{
                $status_sale = $request->status_sale;
            }
            
            $partial_sale_current = $sale->partial_sale;
            $total_price = ($request->quantity) * ($sold_product->price);
            $paid_formated = preg_replace('/[,]/', '.', $request->paid); 

            $sale->update([
                'product_id'=>$request->product,
                // 'quantity'=>$new_quantity,
                // 'quantity'=>$request->quantity,
                // 'total_price'=>$total_price,
                'customer'=>$request->customer,
                'paid'=>$paid_formated,
                'description'=>$request->description,
                'debit_balance'=>$request->debit_balance,
                'date_paid'=>$request->date_paid,
                'status_sale'=>$status_sale,
                'partial_sale'=> isset($request->partial_sale) 
                && $request->partial_sale !== 'status_current_partial'  
                ? $request->partial_sale : $partial_sale_current

            ]);

            $notification = array(
                'message'=>"Venda realizada",
                'alert-type'=>'success',
            );
        }
        
        elseif($new_quantity <=3 && $new_quantity !=0){
            // send notification 
            $product = Purchase::where('quantity', '<=', 3)->first();
            event(new PurchaseOutStock($product));
            // end of notification 
            $notification = array(
                'message'=>"Produto insuficiente no estoque!",
                'alert-type'=>'danger'
            );
            
        }
        else{
            $notification = array(
                'message'=>"Verifique a quantidade de produtos para compra",
                'alert-type'=>'info',
            );
            return back()->with($notification);
        }

        $notification = array(
            'message'=> "Ok",
            'alert-type'=> 'info',
        );
        return back()->with($notification);
    }

    public function updatePublic(Request $request)
    {
        $sale = Sales::find($request->id);
        $sold_product = Product::find($sale->product_id);
        
        $purchased_item = Purchase::find($sold_product->purchase->id);
        $new_quantity = $purchased_item->quantity;
        if ($new_quantity > 0){

            if($request->status_sale == 'status_current'){
                $status_sale = $sale->status_sale;
            }else{
                $status_sale = $request->status_sale;
            }
            
            $partial_sale_current = $sale->partial_sale;
            $total_price = ($request->quantity) * ($sold_product->price);
            $paid_formated = preg_replace('/[,]/', '.', $request->paid); 

            $sale->update([
                'product_id'=>$request->product,
                // 'quantity'=>$new_quantity,
                // 'quantity'=>$request->quantity,
                // 'total_price'=>$total_price,
                'customer'=>$request->customer,
                'paid'=>$paid_formated,
                'description'=>$request->description,
                'debit_balance'=>$request->debit_balance,
                'date_paid'=>$request->date_paid,
                'status_sale'=>$status_sale,
                'partial_sale'=> isset($request->partial_sale) 
                && $request->partial_sale !== 'status_current_partial'  
                ? $request->partial_sale : $partial_sale_current

            ]);

            $notification = array(
                'message'=>"Venda realizada",
                'alert-type'=>'success',
            );
        }
        
        elseif($new_quantity <=3 && $new_quantity !=0){
            // send notification 
            $product = Purchase::where('quantity', '<=', 3)->first();
            event(new PurchaseOutStock($product));
            // end of notification 
            $notification = array(
                'message'=>"Produto insuficiente no estoque!",
                'alert-type'=>'danger'
            );
            
        }
        else{
            $notification = array(
                'message'=>"Verifique a quantidade de produtos para compra",
                'alert-type'=>'info',
            );
            return back()->with($notification);
        }

        $notification = array(
            'message'=> "Ok",
            'alert-type'=> 'info',
        );
        return back()->with($notification);
    }

    public function orderUpdate(Request $request){
        $order = OrderProductions::where('sale_id', $request->sale_id)->get()->first();
        if(empty($order)){
            OrderProductions::create([
                'user_id' => $request->user_id == 'Selecione' ? null : $request->user_id,
                'sale_id' => $request->sale_id,
                'status' => $request->status
            ]);
        }else{
            OrderProductions::where('sale_id', $request->sale_id)->update([
                'user_id' => $request->user_id,
                'sale_id' => $request->sale_id,
                'status' => $request->status
            ]);
        }

        $notification = array(
            'message'=>"Ok",
            'alert-type'=>'success'
        );

        return back()->with($notification);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $sale = Sales::find($request->id);
        $new_qty = $sale->product->purchase->quantity + $sale->quantity;

        Purchase::where('id', $sale->product->purchase->id)->update([
            'quantity' => $new_qty
        ]);

        $sale->delete();
        $notification = array(
            'message'=>"Venda deletada",
            'alert-type'=>'success'
        );
        return back()->with($notification);
    }
}
