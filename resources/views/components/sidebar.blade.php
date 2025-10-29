<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/dashboard') }}" class="brand-link">
        <img src="{{ asset('assets/lte/dist/img/iconmedcol.png') }}" alt="Medcol Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Medcol SW</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/lte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                <small class="text-muted">{{ Auth::user()->roles->pluck('name')->join(', ') ?: 'Sin rol asignado' }}</small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent nav-compact" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                @if(Auth::user()->hasPermission('dashboard.view'))
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @endif

                <!-- Análisis NT -->
                @if(Auth::user()->hasPermission('analisis-nt.view'))
                <li class="nav-item">
                    <a href="{{ route('analisis-nt.index') }}" class="nav-link {{ request()->routeIs('analisis-nt.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Análisis NT</p>
                    </a>
                </li>
                @endif

                <!-- Medcol2 -->
                @if(Auth::user()->hasAnyPermission(['medcol2.pendientes.view', 'medcol2.dispensado.view']))
                <li class="nav-item {{ request()->is('medcol2/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('medcol2/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-pills"></i>
                        <p>
                            API Medcol
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(Auth::user()->hasPermission('medcol2.pendientes.view'))
                        <li class="nav-item">
                            <a href="{{ url('/medcol6/pendientes') }}" class="nav-link {{ request()->is('medcol6/pendientes') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pendientes</p>
                            </a>
                        </li>
                        @endif
                        @if(Auth::user()->hasPermission('medcol2.dispensado.view'))
                        <li class="nav-item">
                            <a href="{{ url('/medcol6/dispensado') }}" class="nav-link {{ request()->is('medcol6/dispensado') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dispensado</p>
                            </a>
                        </li>
                        @endif
                        @if(Auth::user()->hasPermission('medcol6.informes.view'))
                        <li class="nav-item">
                            <a href="{{ route('medcol6.informes') }}" class="nav-link {{ request()->is('medcol6/informes') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Informes</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                {{-- Medcol3, 5, 6 y Medcold temporalmente comentados hasta configurar rutas correctas --}}
                {{-- Descomentar y ajustar rutas según sea necesario --}}

                {{-- Inventario, Compras y Reportes temporalmente comentados hasta configurar rutas --}}
                {{-- Descomentar y ajustar según sea necesario --}}

                <!-- Administración -->
                @if(Auth::user()->hasAnyPermission(['usuarios.view', 'roles.view', 'permisos.view', 'configuracion.manage']))
                <li class="nav-header">ADMINISTRACIÓN</li>
                <li class="nav-item {{ request()->is('admin/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('admin/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Sistema
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(Auth::user()->hasPermission('usuarios.view'))
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Usuarios</p>
                            </a>
                        </li>
                        @endif
                        @if(Auth::user()->hasPermission('roles.view'))
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        @endif
                        @if(Auth::user()->hasPermission('permisos.view'))
                        <li class="nav-item">
                            <a href="{{ route('permissions.index') }}" class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Permisos</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                {{-- Mipres 2.0 - Comentado temporalmente --}}
                {{-- Descomentar cuando la ruta esté disponible --}}
                {{--
                <li class="nav-item">
                    <a href="{{ route('tokenhercules') }}" class="nav-link">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>Mipres 2.0</p>
                    </a>
                </li>
                --}}

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
