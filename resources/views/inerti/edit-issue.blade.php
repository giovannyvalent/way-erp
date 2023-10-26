@extends('layouts.app')

@push('page-css')
	<!-- Select2 CSS -->
	<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
	
	<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}">
@endpush

@push('page-header')
<div class="col-sm-12">
	<h3 class="page-title">Editar Issue</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">Editar</li>
	</ul>
</div>
@endpush


@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body custom-edit-service">
				
				<!-- Add Medicine -->
				<form method="post" enctype="multipart/form-data" autocomplete="off" action="{{route('add-issue')}}">
					@csrf
					<div class="service-fields mb-3">
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group">
									<label>Titulo<span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="title" value="{{$issue->title}}">
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label>Release <span class="text-danger">*</span></label>
									<select class="select2 form-select form-control" name="release_id"> 
										@foreach ($releases as $category)
											<option @if($issue->release_id == $category->id) selected @endif value="{{$category->id}}">{{$category->title}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label>Instâncias <span class="text-danger">*</span></label>
									<select class="select2 form-select form-control" name="instances_released[]" multiple> 
										@foreach ($instances as $instance)
											<option @if($issue->instances_released == $category->id) selected @endif  value="{{$instance->id}}">{{$instance->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="service-fields mb-3">
						<div class="row">
							<div class="col-lg-3">
								<div class="form-group">
									<label>Tipo <span class="text-danger">*</span></label>
									<select class="select2 form-select form-control" name="type"> 
										<option value="incident">Incidente</option>
										<option value="task">Tarefa (backlog)</option>
									</select>
								</div>
							</div>
							<div class="col-lg-3">
								<div class="form-group">
									<label>Gravidade <span class="text-danger">*</span></label>
									<select class="select2 form-select form-control" name="gravity"> 
										<option value="1">Sem gravidade</option>
										<option value="2">Pouco grave</option>
										<option value="3">Grave</option>
										<option value="4">Muito grave</option>
										<option value="5">Extremo</option>
									</select>
								</div>
							</div>
							<div class="col-lg-3">
								<div class="form-group">
									<label>Urgência <span class="text-danger">*</span></label>
									<select class="select2 form-select form-control" name="urgency"> 
										<option value="1">Esperar</option>
										<option value="2">Longo prazo</option>
										<option value="3">Médio prazo</option>
										<option value="4">Curto prazo</option>
										<option value="5">Imediato</option>
									</select>
								</div>
							</div>
							<div class="col-lg-3">
								<div class="form-group">
									<label>Tendência <span class="text-danger">*</span></label>
									<select class="select2 form-select form-control" name="trend"> 
										<option value="1">Desaparece</option>
										<option value="2">Reduz</option>
										<option value="3">Permanece</option>
										<option value="4">Aumenta</option>
										<option value="5">Piora</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="service-fields mb-3">
						<div class="row">
							<div class="col-lg-3">
								<div class="form-group">
									<label>Status geral <span class="text-danger">*</span></label>
									<select class="select2 form-select form-control" name="status_all"> 
										<option value="open">Aberto</option>
										<option value="closed">Concluído</option>
									</select>
								</div>
							</div>
							<div class="col-lg-3">
								<div class="form-group">
									<div class="form-group">
										<label>Data de solução <span class="text-danger">*</span></label>
										<input class="form-control" type="date" name="expiry_date">
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="service-fields mb-3">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<label>Descrição <span class="text-danger">*</span></label>
									<textarea class="form-control service-desc" name="description"></textarea>
								</div>
							</div>
							
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

