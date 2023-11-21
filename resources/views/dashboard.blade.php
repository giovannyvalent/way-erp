@extends('layouts.app')

@push('page-css')
@endpush

@push('page-header')
<div class="col-sm-12">
	<img src="https://wayfactory.com.br/wp-content/uploads/2023/10/waylogo.jpg" style="max-width: 150px; border-radius:20px;" alt="">
	<hr>
	<h3 class="page-title">Bem-vindo(a), {{auth()->user()->name}}!</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item active">Dashboard</li>
	</ul>
</div>
@endpush

@section('content')
	<div class="row">
		<div class="col-xl-3 col-sm-6 col-12">
			<div class="card">
				<div class="card-body">
					<div class="dash-widget-header">
						<span class="dash-widget-icon text-danger border-danger">
							<i class="fe fe-money"></i>
						</span>
						<div class="dash-count">
							<h4>R$ {{number_format($today_sales, 2, ',','.')}}</h4>
						</div>
					</div>
					<div class="dash-widget-info">
						<h6 class="text-muted">Vendas de hoje</h6>
						<div class="progress progress-sm">
							@if($today_sales > 200)
							<div class="progress-bar bg-danger w-25"></div>
							@elseif($today_sales > 500)
							<div class="progress-bar bg-danger w-50"></div>
							@elseif($today_sales > 1000)
							<div class="progress-bar bg-danger w-80"></div>
							@else
							<div class="progress-bar bg-danger w-0"></div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-sm-6 col-12">
			<div class="card">
				<div class="card-body">
					<div class="dash-widget-header">
						<span class="dash-widget-icon text-danger border-danger">
							<i class="fe fe-credit-card"></i>
						</span>
						<div class="dash-count">
							<h3>{{$total_categories}}</h3>
						</div>
					</div>
					<div class="dash-widget-info">
						
						<h6 class="text-muted">Categoria de produtos</h6>
						{{-- <div class="progress progress-sm">
							<div class="progress-bar bg-danger w-100"></div>
						</div> --}}
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-sm-6 col-12">
			<div class="card">
				<div class="card-body">
					<div class="dash-widget-header">
						<span class="dash-widget-icon text-danger border-danger">
							<i class="fe fe-folder"></i>
						</span>
						<div class="dash-count">
							<h3>{{$total_expired_products}}</h3>
						</div>
					</div>
					<div class="dash-widget-info">
						
						<h6 class="text-muted">Produtos expirados</h6>
						{{-- <div class="progress progress-sm">
							<div class="progress-bar bg-danger w-100"></div>
						</div> --}}
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-3 col-sm-6 col-12">
			<div class="card">
				<div class="card-body">
					<div class="dash-widget-header">
						<span class="dash-widget-icon text-danger border-danger">
							<i class="fe fe-users"></i>
						</span>
						<div class="dash-count">
							<h3>{{\DB::table('users')->count()}}</h3>
						</div>
					</div>
					<div class="dash-widget-info">
						
						<h6 class="text-muted">Usuário</h6>
						{{-- <div class="progress progress-sm">
							<div class="progress-bar bg-danger w-100"></div>
						</div> --}}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-lg-6">
		
			<div class="card card-table">
				<div class="card-header">
					<h5 class="card-title ">Vendas de hoje</h5>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover table-center mb-0">
							<thead>
								<tr>
									<th>Produto</th>
									<th>Quantidade</th>
									<th>Preço</th>
									<th>Data</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($latest_sales as $sale)
									@if(!empty($sale->product->purchase))
										<tr>
											<td>{{$sale->product->purchase->name}}</td>
											<td>{{$sale->quantity}}</td>
											<td>
												{{AppSettings::get('app_currency', 'R$')}} {{($sale->paid)}}
											</td>
											<td>{{date_format(date_create($sale->date_paid),"d/m/Y")}}</td>
											
										</tr>
									@endif
								@endforeach
																
							</tbody>
						</table>
					</div>
				</div>
			</div>
			
		</div>

		<div class="col-md-12 col-lg-6">
						
			<!-- Pie Chart -->
			<div class="card card-chart">
				<div class="card-header">
					<h5 class="card-title">Soma de recursos</h5>
				</div>
				<div class="card-body">
					<div style="width:65%;">
						{!! $pieChart->render() !!}
					</div>
				</div>
			</div>
			<!-- /Pie Chart -->
			
		</div>	
		
		
	</div>
	<div class="row">
		<div class="col-md-12">
		
			<!-- Latest Customers -->
			
			<!-- /Latest Customers -->
			
		</div>
	</div>
@endsection

@push('page-js')

@endpush

