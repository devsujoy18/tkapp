<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('admin/dashboard') }}" class="brand-link">
      <img src="{{ asset('admin_assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('admin_assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <?php 
            $logged_in_admin = Session::get('logged_in_admin');
          ?>
          <a href="#" class="d-block">{{$logged_in_admin->name}}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <li class="nav-item">
            <a href="{{ url('admin/dashboard') }}" class="nav-link @yield('dashboard_select')">
              <i class="nav-icon nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <!-- <li class="nav-item">
            <a href="{{ url('admin/categories') }}" class="nav-link @yield('category_select')">
              <i class="nav-icon nav-icon fas fa-list-alt"></i>
              <p>Manage Category</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ url('admin/products') }}" class="nav-link @yield('product_select')">
              <i class="nav-icon nav-icon fas fa-list-alt"></i>
              <p>Manage Product</p>
            </a>
          </li> -->

          <li class="nav-item @yield('master_menu_open')">
            <a href="#" class="nav-link @yield('master_active')">
              <i class="nav-icon fas fa-book"></i>
              <p>Master<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('admin/countries') }}" class="nav-link @yield('country_select')">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Country</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('admin/timezones') }}" class="nav-link @yield('timezone_select')">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Timezones</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('admin/categories') }}" class="nav-link @yield('category_select')">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Category</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('admin/subcategories') }}" class="nav-link @yield('sub_category_select')">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sub Category</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="{{ url('admin/logout') }}" class="nav-link">
              <i class="nav-icon nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>
          
          
        </ul>
      </nav>
    </div>
  </aside>