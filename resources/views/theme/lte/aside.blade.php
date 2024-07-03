 <!-- Main Sidebar Container -->
  <aside class="main-sidebar  sidebar-light-info elevation-4 ">
    <!-- Brand Logo -->
    <a href="#" class="brand-link logo-switch">
    <img src="{{asset("assets/img/icon_aside.jpeg")}}" alt="fidem_icon _aside" class="brand-image-xl logo-xs" style="left: 2px">
    <img src="{{asset("assets/img/logo_aside.jpeg")}}" alt="fidem_logo_aside" class="brand-image-l logo-xl" style="left: 12px">
    </a>

    <!-- Sidebar -->
    <div class="sidebar sidebar-light-info sidebar-collapse">
      {{-- <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset("assets/$theme/dist/img/user_default.jpg  ")}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#"  class="d-block">{{Session()->get('usuario') ?? ''}}</a>
        </div>
      </div> --}}

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul id="sidebar-menu" class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <div class="user-panel mt-1 pb-1 mb-1 d-flex">
           <div class="info">
            <i class="fa fa-bars" aria-hidden="false"></i>
           </div>
          <div class="info">
            <i class="nav-item has-treeview">
            <H5 alignt="center">Control Nomina</H5>
            </i>
         </div>
          </div>

          </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
