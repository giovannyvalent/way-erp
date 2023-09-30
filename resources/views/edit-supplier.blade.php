@extends('layouts.app')

@push('page-css')
	<!-- Select2 CSS -->
	<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
	<!-- Datetimepicker CSS -->
	<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}">
@endpush

@push('page-header')
<div class="col-sm-12">
	<h3 class="page-title">Editar</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">editar</li>
	</ul>
</div>
@endpush

@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body custom-edit-service">
				
		
			<!-- Add Medicine -->
			<form method="post" enctype="multipart/form-data" action="{{route('edit-supplier',$supplier)}}">
				@csrf
				@method("PUT")
				<div class="service-fields mb-3">
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Nome<span class="text-danger">*</span></label>
								<input class="form-control" type="text" value="{{$supplier->name}}" name="name">
							</div>
						</div>
						<div class="col-lg-6">
							<label>Email<span class="text-danger">*</span></label>
							<input class="form-control" type="text" value="{{$supplier->email}}" name="email" >
						</div>
					</div>
				</div>

				<div class="service-fields mb-3">
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Telefone<span class="text-danger">*</span></label>
								<input class="form-control" type="text" value="{{$supplier->phone}}" name="phone">
							</div>
						</div>
						<div class="col-lg-6">
							<label>Empresa<span class="text-danger">*</span></label>
							<input class="form-control" type="text" value="{{$supplier->company}}" name="company">
						</div>
					</div>
				</div>

				<div class="service-fields mb-3">
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Endereço <span class="text-danger">*</span></label>
								<input type="text" name="address" value="{{$supplier->address}}" class="form-control">
							</div>
						</div>
						<div class="col-lg-6">
							<label>Produto</label>
							<input type="text" name="product" value="{{$supplier->product}}" class="form-control">
						</div>
					</div>
				</div>	
				<div class="service-fields mb-3">
					<div class="row">
						<div class="col-12">
							<label>Comentário</label>
							<textarea name="description" class="form-control" value="{{$supplier->description}}" cols="30" rows="10">{{$supplier->description}}</textarea>
						</div>
					</div>
				</div>		
				
				
				<div class="submit-section">
					<button class="btn btn-primary submit-btn" type="submit" name="form_submit" value="submit">Salvar</button>
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
	<script src="assets/js/moment.min.js"></script>
	<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
	<!-- Select2 JS -->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
@endpush

