@extends('layouts.app')

@push('page-css')
	<!-- Select2 css-->
	<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">Vendas</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">Vendas</li>
	</ul>
</div>

@can('create-sales')
<div class="col-sm-12 col">
	<a href="#add_sales" data-toggle="modal" class="btn btn-primary float-right mt-2">Nova venda</a>
</div>
@endcan
@endpush

@section('content')
<div class="row">
	<div class="col-md-12">

		<form action="{{route('sales')}}" method="GET" class="col-6">
			<div class="row">
			  <div class="col">
				@if(isset($_GET['date_paid_start']))
					De
					<input type="date" name="date_paid_start" value="{{$_GET['date_paid_start']}}" placeholDer="data de entrada" class="form-control">
				@else
					De
					<input type="date" name="date_paid_start" id="" placeholder="data de entrada" class="form-control" required>
				@endif
			  </div>
		
			  <div class="col">
				@if(isset($_GET['date_paid_end']))
					Até
					<input type="date" name="date_paid_end" value="{{$_GET['date_paid_end']}}" placeholder="data de entrada" class="form-control">
				@else
					Até
					<input type="date" name="date_paid_end" id="" placeholder="data de entrada" class="form-control" required>
				@endif
			  </div>
		
			  <div class="col">
				<button class="btn btn-primary mt-4" type="submit">Buscar</button>
				<a href="/sales" class="btn btn-primary mt-4">Limpar</a>
			  </div>
			</div>
		</form>
		<!-- Recent Sales -->
		<div class="card mt-3">
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-hover table-center mb-0">
						<thead>
							<tr>
								<th>Faturamento</th>
								<th>Entradas em Pix</th>
								<th>Entradas em dinheiro</th>
								<th>Entradas</th>
								<th>Entradas faturadas</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>R$ {{ number_format($total_sales, 2, ',','.') }}</td>
								<td>R$ {{ number_format($total_sales_pix, 2, ',','.') }}</td>
								<td>R$ {{ number_format($total_sales_cash, 2, ',','.') }}</td>
								<td>{{ number_format($count_sales, 0, '.','.') }}</td>
								<td>{{ number_format($count_sales_paid, 0, '.','.') }}</td>
							</tr>
						</tbody>
					</table>
					<table id="datatable-export" class="table table-hover table-center mb-0">
						<thead>
							<tr>
								<th>Venda</th>
								<th>Cliente</th>
								<th>Qtd</th>
								<th>M.P</th>
								<th>Valor pago</th>
								<th>Usuário</th>
								<th>Data de entrada</th>
								<th>Status</th>
								<th class="action-btn">Opções</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($sales as $sale)
								@if (!(empty($sale->product->purchase)))
									<tr>
										<td>{{$sale->product->purchase->name}}</td>
										<td>{{$sale->customer}}</td>
										<td>{{$sale->quantity}}</td>
										<td>{{$sale->paymentMethod->name}}</td>
										<td> R$ {{($sale->paid)}}</td>
										<td> 
											@if(isset($sale->userSale))
												{{($sale->userSale->name)}}
											@else
												N/A
											@endif
										</td>
										<td>
											@if(isset($sale->date_paid))
												{{date_format(\Carbon\Carbon::parse($sale->date_paid), 'd/m/Y')}}
											@else
												N/A
											@endif
										</td>
										<td>
											@if($sale->status_sale == 1)
											<span class="badge rounded-pill bg-success">Quitado</span>
											@else
											<span class="badge rounded-pill bg-warning">Aberto</span>
											@endif
										</td>
										<td>
											<div class="actions">
												<a data-id="{{$sale->id}}"
												data-sale="{{$sale->id}}"
												data-product="{{$sale->product_id}}"
												data-product_name="{{$sale->product->purchase->name}}"
												data-quantity="{{$sale->quantity}}"
												data-customer="{{$sale->customer}}"
												data-paid="{{$sale->paid}}"
												data-description="{{$sale->description}}"
												data-status_sale="{{$sale->status_sale}}"
												data-payment_method="{{$sale->paymentMethod->name}}"
												data-total_price="{{$sale->total_price}}"
												data-debit_balance="{{$sale->debit_balance}}"
												data-date_paid="{{$sale->date_paid}}"
												class="btn btn-sm bg-success-light editbtn" href="javascript:void(0);">
													<i class="fe fe-pencil"></i> Editar
												</a>
												<a data-id="{{$sale->id}}" href="javascript:void(0);" class="btn btn-sm bg-danger-light deletebtn" data-toggle="modal">
													<i class="fe fe-trash"></i> Deletar
												</a>
											</div>
										</td>
									</tr>
								@endif
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- /Recent sales -->
		
	</div>
</div>
<!-- Delete Modal -->
<x-modals.delete :route="'sales'" :title="'Product Sale'" />
<!-- /Delete Modal -->
<!-- Add Modal -->
<div class="modal fade bd-example-modal-lg" id="add_sales" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Nova entrada</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" action="{{route('sales')}}">
					@csrf
					<div class="row form-row">
						<div class="col-6">
							<div class="form-group">
								<label>Produto <span class="text-danger">*</span></label>
								<select class="select2 form-select form-control" name="product"> 
									@foreach ($products as $product)
										@if (!empty($product->purchase))
											@if (!($product->purchase->quantity <= 0))
												<option value="{{$product->id}}">{{$product->purchase->name}}</option>
											@endif
										@endif
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Método de pagamento <span class="text-danger">*</span></label>
								<select class="select2 form-select form-control" name="payment_method" required> 
										<option value="" selected>Selecione</option>
									@foreach ($payments as $pay)
										<option value="{{$pay->id}}">{{$pay->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<input type="hidden" name="">
						<div class="col-6">
							<div class="form-group">
								<label>Quantidade</label>
								<input type="text" class="form-control" name="quantity">
							</div>
						</div>

						<div class="col-6">
							<div class="form-group">
								<label>Cliente</label>
								<input type="text" class="form-control" name="customer">
							</div>
						</div>

						<div class="col-12">
							<div class="form-group">
								<label>Valor pago</label>
								<input type="text" class="form-control" name="paid">
							</div>
						</div>

						<div class="col-12">
							<div class="form-group">
								<label>Descrição</label>
								<input type="text" class="form-control" name="description">
							</div>
						</div>

						<div class="col-6">
							<div class="form-group">
								<label>Data de entrada</label>
								<input type="date" class="form-control" name="date_paid" value="{{$date}}">
							</div>
						</div>

						<div class="col-6">
							<div class="form-group">
								<label>Status da entrada</label>
								<select class="form-select form-control" aria-label="Default select example" name="status_sale" required>
									<option selected>Selecione um status</option>
									<option value="1">Quitado</option>
									<option value="0">Aberto</option>
								</select>
							</div>
						</div>
						

					</div>
					<button type="submit" class="col-3 float-right mt-2 btn btn-primary btn-block">Adicionar</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /ADD Modal -->

<!-- Edit Modal -->
<div class="modal fade bd-example-modal-lg" id="edit_sale" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Editar Venda</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" action="{{route('sales')}}">
					<input type="hidden" class="form-control edit_product" name="product">
					<input type="hidden" class="form-control edit_id" name="id">
					@csrf
					@method("PUT")
					<div class="row form-row">
						<input type="hidden" name="">
						<div class="col-6">
							<div class="form-group">
								<label>Produto</label>
								<input class="form-control edit_product_name" disabled>
							</div>
						</div>

						<div class="col-6">
							<div class="form-group">
								<label>Método</label>
								<input class="form-control edit_payment_method" disabled>
							</div>
						</div>

						<div class="col-6">
							<div class="form-group">
								<label>Total</label>
								<input id="a1" class="form-control edit_total_price" disabled>
							</div>
						</div>

						<div class="col-6">
							<div class="form-group">
								<label>Quantidade</label>
								<input type="number" class="form-control edit_quantity" name="quantity" disabled>
							</div>
						</div>

						<div class="col-6">
							<div class="form-group">
								<label>Cliente</label>
								<input type="text" class="form-control edit_customer" name="customer">
							</div>
						</div>

						<div class="col-lg-6">
							<div class="form-group">
								<label>Valor pago</label>
								<input id="a2"  type="text" class="form-control edit_paid" name="paid" onblur="calculate()">
								<a class="btn btn-primary mt-2" style="color:white">Calcular</a>
							</div>
							<label style="color:red">Saldo devedor do cliente</label>
							<input id="a3" class="form-control edit_debit_balance" type="text" name="debit_balance" />
						</div>
						<hr>

						<div class="col-12 mt-4">
							<div class="form-group">
								<label>Descrição</label>
								<input type="text" class="form-control edit_description" name="description">
							</div>
						</div>

						<div class="col-6 mt-2">
							<div class="form-group">
								<label>Data de entrada</label>
								<input type="date" class="form-control edit_date_paid" name="date_paid">
							</div>
						</div>

						<div class="col-6 mt-2">
							<div class="form-group">
								<label>Status atual da entrada:</label>
								<input style="border: none; border-radius: 2px; font-weight: 700; color: rgb(255, 255, 255); background-color: rgb(202, 42, 42)" type="text" class="edit_status_sale">
								<select class="form-select form-control" aria-label="Default select example" name="status_sale">
									<option value="status_current" selected>Manter status atual</option>
									<option value="1">Quitado</option>
									<option value="0">Aberto</option>
								</select>
							</div>
						</div>
					</div>
					<button type="submit" class="col-3 float-right mt-2 btn btn-primary btn-block">Salvar</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Edit Modal -->
@endsection


@push('page-js')
	<!-- Select2 js-->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
	<script>
		$(document).ready(function(){
			$('#datatable-export').on('click','.editbtn',function (){
				event.preventDefault();
				jQuery.noConflict();
				$('#edit_sale').modal('show');
				var id = $(this).data('id');
				var sale = $(this).data('sale');
				var product = $(this).data('product');
				var product_name = $(this).data('product_name');
				var quantity = $(this).data('quantity');
				var customer = $(this).data('customer');
				var description = $(this).data('description');
				var paid = $(this).data('paid');
				var status_sale_current = 'null';
				if($(this).data('status_sale') == 1){
					status_sale_current = '    Quitada / Faturada' 
				}else{
					status_sale_current = '    Aberta / Pendente' 
				}
				console.log(status_sale_current)
				var status_sale = status_sale_current;
				var payment_method = $(this).data('payment_method');
				var total_price = $(this).data('total_price');
				var debit_balance = $(this).data('debit_balance');
				var date_paid = $(this).data('date_paid');
				$('#edit_id').val(id);
				$('.edit_id').val(id);
				$('.edit_sale').val(sale);
				$('.edit_product').val(product);
				$('.edit_product_name').val(product_name);
				$('.edit_quantity').val(quantity);
				$('.edit_customer').val(customer);
				$('.edit_paid').val(paid);
				$('.edit_description').val(description);
				$('.edit_status_sale').val(status_sale);
				$('.edit_payment_method').val(payment_method);
				$('.edit_total_price').val(total_price);
				$('.edit_debit_balance').val(debit_balance);
				$('.edit_date_paid').val(date_paid);
			});
		});

		calculate = function(){
			var resources = document.getElementById('a1').value;
			var minutes = document.getElementById('a2').value; 
			document.getElementById('a3').value = parseInt(resources)-parseInt(minutes);
		}
	</script>
@endpush
