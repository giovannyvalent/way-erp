@extends('layouts.app')

@push('page-css')
	<!-- Select2 CSS -->
	<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
	
	<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}">
@endpush

@push('page-header')
<div class="col-sm-12">
	@if($categories[0]['name'] !== 'DESPESAS')
	<h3 class="page-title">Nova compra</h3>
	@else
	<h3 class="page-title">Nova despesa</h3>
	@endif
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">Adicionar</li>
	</ul>
</div>
@endpush


@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body custom-edit-service">
				
				<!-- Add Medicine -->
				<form method="post" enctype="multipart/form-data" autocomplete="off" action="{{route('add-purchase')}}">
					@csrf
					<div class="service-fields mb-3">
						<div class="row">
							<div class="col-lg-4">
								<div class="form-group">
									<label>Nome<span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="name" >
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label>Categoria <span class="text-danger">*</span></label>
									<select class="select2 form-select form-control" name="category"> 
										@foreach ($categories as $category)
											<option value="{{$category->id}}">{{$category->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							@if($categories[0]['name'] !== 'DESPESAS')
							<div class="col-lg-4">
								<div class="form-group">
									<label>Fornecedor <span class="text-danger">*</span></label>
									<select class="select2 form-select form-control" name="supplier"> 
										@foreach ($suppliers as $supplier)
											<option value="{{$supplier->id}}">{{$supplier->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							@endif
						</div>
					</div>
					
					<div class="service-fields mb-3">
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group">
									<label>Valor (R$)<span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="price">
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label>Quantidade <span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="quantity">
								</div>
							</div>
						</div>
					</div>

					<div class="service-fields mb-3">
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group">
									<label>Data de expiração (qualquer data se não houver)<span class="text-danger">*</span></label>
									<input class="form-control" type="date" name="expiry_date">
								</div>
							</div>
							{{-- <div class="col-lg-6">
								<div class="form-group">
									<label>Imagem</label>
									<input type="file" name="image" class="form-control">
								</div>
							</div> --}}
						</div>
					</div>
					
					
					<div class="submit-section">
						<button class="btn btn-primary submit-btn" type="submit" >Salvar</button>
					</div>
				</form>
				<!-- /Add Medicine -->

			</div>
		</div>
	</div>			
</div>
@endsection

@push('page-js')
	<!-- Datetimepicker JS -->
	<script src="{{asset('assets/js/moment.min.js')}}"></script>
	<script src="{{asset('assets/js/bootstrap-datetimepicker.min.js')}}"></script>
	<!-- Select2 JS -->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
@endpush

