<!-- Sidebar -->
<div class="sidebar" id="sidebar">
	<div class="sidebar-inner slimscroll">
		<div id="sidebar-menu" class="sidebar-menu">
			
			<ul>
				<li class="menu-title"> 
					<span>Menu</span>
				</li>
				<li class="{{ Request::routeIs('dashboard') ? 'active' : '' }}"> 
					<a style="font-size: 12px" href="{{route('dashboard')}}"><i class="fe fe-home"></i> <span>Dashboard</span></a>
				</li>
				
				<li class="{{ Request::routeIs('categories') ? 'active' : '' }}"> 
					<a style="font-size: 12px" href="{{route('categories')}}"><i class="fe fe-layout"></i> <span>Categorias</span></a>
				</li>
				
				<li class="submenu">
					<a style="font-size: 12px" href="#"><i class="fe fe-document"></i> <span> Produtos</span> <span class="menu-arrow"></span></a>
					<ul style="display: none;">
						<li><a style="font-size: 12px" class="{{ Request::routeIs(('products')) ? 'active' : '' }}" href="{{route('products')}}">Produtos</a></li>
						<li><a style="font-size: 12px" class="{{ Request::routeIs('add-product') ? 'active' : '' }}" href="{{route('add-product')}}">Adicionar</a></li>
						{{--<li><a class="{{ Request::routeIs('outstock') ? 'active' : '' }}" href="{{route('outstock')}}">Out-Stock</a></li>--}}
						{{-- <li><a class="{{ Request::routeIs('expired') ? 'active' : '' }}" href="{{route('expired')}}">Expirados</a></li> --}}
					</ul>
				</li>
				
				<li class="submenu">
					<a style="font-size: 12px" href="#"><i class="fe fe-star-o"></i> <span> Compras e despesas</span> <span class="menu-arrow"></span></a>
					<ul style="display: none;">
						<li><a style="font-size: 12px" class="{{ Request::routeIs('purchases') ? 'active' : '' }}" href="{{route('purchases')}}">Lista</a></li>
						{{--@can('create-purchase')--}}
						<li><a style="font-size: 12px" class="{{ Request::routeIs('add-purchase') ? 'active' : '' }}" href="{{route('add-purchase')}}">Nova compra</a></li>
						<li><a style="font-size: 12px" class="{{ Request::routeIs('add-expenses') ? 'active' : '' }}" href="{{route('add-expenses')}}">Nova despesa</a></li>
						{{--@endcan--}}
					</ul>
				</li>

				<li><a  style="font-size: 12px"class="{{ Request::routeIs('sales') ? 'active' : '' }}" href="{{route('sales')}}"><i class="fe fe-activity"></i> <span>Entradas</span></a></li>

				<li class="submenu">
					<a style="font-size: 12px" href="#"><i class="fe fe-user"></i> <span> Fornecedores</span> <span class="menu-arrow"></span></a>
					<ul style="display: none;">
						<li><a  style="font-size: 12px" class="{{ Request::routeIs('suppliers') ? 'active' : '' }}" href="{{route('suppliers')}}">Lista</a></li>
						@can('create-supplier')<li><a style="font-size: 12px" class="{{ Request::routeIs('add-supplier') ? 'active' : '' }}" href="{{route('add-supplier')}}">Adicionar</a></li>@endcan
					</ul>
				</li>

				<li class="submenu">
					<a style="font-size: 12px" href="#"><i class="fe fe-document"></i> <span> Relatório</span> <span class="menu-arrow"></span></a>
					<ul style="display: none;">
						<li><a style="font-size: 12px" class="{{ Request::routeIs('reports') ? 'active' : '' }}" href="{{route('reports')}}">Gerar relatório</a></li>
					</ul>
				</li>

				@can('view-access-control')
				<li class="submenu">
					<a href="#"><i class="fe fe-lock"></i> <span> Access Control</span> <span class="menu-arrow"></span></a>
					<ul style="display: none;">
						@can('view-permission')
						<li><a class="{{ Request::routeIs('permissions') ? 'active' : '' }}" href="{{route('permissions')}}">Permissions</a></li>
						@endcan
						@can('view-role')
						<li><a class="{{ Request::routeIs('roles') ? 'active' : '' }}" href="{{route('roles')}}">Roles</a></li>
						@endcan
					</ul>
				</li>					
				@endcan

				@can('view-users')
				<li class="{{ Request::routeIs('users') ? 'active' : '' }}"> 
					<a href="{{route('users')}}"><i class="fe fe-users"></i> <span>Users</span></a>
				</li>
				@endcan
				
				{{-- <li class="{{ Request::routeIs('profile') ? 'active' : '' }}"> 
					<a href="{{route('profile')}}"><i class="fe fe-user-plus"></i> <span>Profile</span></a>
				</li> --}}
				@can('view-settings')
				<li class="{{ Request::routeIs('settings') ? 'active' : '' }}"> 
					<a href="{{route('settings')}}">
						<i class="fa fa-gears"></i>
						 <span> Settings</span>
					</a>
				</li>
				@endcan
			</ul>
		</div>
	</div>
</div>
<!-- /Sidebar -->