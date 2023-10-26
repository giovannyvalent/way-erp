@extends('layouts.app')

@push('page-css')
	<!-- Select2 CSS -->
	<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">Issues</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">issues</li>
	</ul>
</div>
<div class="col-sm-5 col">
	<a href="{{route('add-issue')}}" class="btn btn-primary float-right mt-2">Nova issue</a>
</div>
@endpush

@section('content')
<div class="row">
	<div class="col-md-12">
	
		<!-- Recent Orders -->
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="datatable-export" class="table table-hover table-center mb-0">
						<thead>
							<tr>
								<th>Data</th>
								<th>Nome</th>
								<th>Release</th>
								<th>Status</th>
								<th>G</th>
								<th>U</th>
								<th>T</th>
								<th class="action-btn">Ações</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($issues as $issue)
							<tr>
								<td>{{date_format($issue->created_at, 'd/m/Y')}}</td>
								<td>{{$issue->title}}</td>
								<td>{{$issue->release->title}}</td>
								<td>{{$issue->status_all}}</td>
								<td>{{$issue->gravity}}</td>
								<td>{{$issue->urgency}}</td>
								<td>{{$issue->trend}}</td>
								<td>
									<div class="actions">
										<a class="btn btn-sm bg-success-light" href="{{route('edit-issue',$issue)}}">
											<i class="fe fe-pencil"></i> Editar
										</a>
										<a data-id="{{$issue->id}}" href="javascript:void(0);" class="btn btn-sm bg-danger-light deletebtn" data-toggle="modal">
											<i class="fe fe-trash"></i> Deletar
										</a>
									</div>
								</td>
							</tr>
							@endforeach
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<!-- /Recent Orders -->
		
	</div>
</div>
<!-- Delete Modal -->
<x-modals.delete :route="'purchases'" :title="'Purchase'" />
<!-- /Delete Modal -->
@endsection

@push('page-js')
	<!-- Select2 JS -->
	<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
@endpush

