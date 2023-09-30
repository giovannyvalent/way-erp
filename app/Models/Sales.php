<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use App\Models\PaymentMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sales extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'product_id','quantity','total_price','customer','paid','description','status_sale','payment_method','debit_balance', 'date_paid', 'user_id'
    ];

    public function userSale(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function purchase(){
        return $this->belongsTo(Purchase::class,'purchase_id');
    }

    public function paymentMethod(){
        return $this->belongsTo(PaymentMethods::class,'payment_method');
    }
    
}
