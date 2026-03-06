<div class="sidebar-menu sticky-sidebar-menu">

    <!-- logo start -->
    <div class="logo">
      <h1><a href="{{ route('root.dashboard') }}">GloVans</a></h1>
    </div>


    <div class="logo-icon text-center">
      <a href="{{ route('root.dashboard') }}" title="logo"><img src="{{ asset('assets/images/logo.png') }}" alt="logo-icon"> </a>
    </div>
    <!-- //logo end -->

    <div class="sidebar-menu-inner">

      <!-- sidebar nav start -->
      <ul class="nav nav-pills nav-stacked custom-nav">
        <li class="active"><a href="{{ route('root.dashboard') }}"><i class="fa fa-tachometer"></i><span> Dashboard</span></a>
        <li class=""><a href="{{ route('root.agents') }}"><i class="lnr lnr-users"></i><span> Agents</span></a>
        </li>
          <li>
              <a href="{{ route("root.categories") }}">
                  <i class="fa fa-ticket"></i> <span>Network Categories</span>
              </a>
          </li>
        <li>
          <a href="{{ route("root.products") }}"><i class="fa fa-star"></i>
            <span>Products <i class="lnr lnr-chevron-right"></i></span></a>
        </li>
        <li>
            <a href="{{ route("root.orders") }}">
                <i class="fa fa-shopping-cart"></i> <span>Orders</span>
            </a>
        </li>
         <li>
                <a href="{{ route("root.settings") }}">
                    <i class="fa fa-cogs"></i> <span>Settings</span>
                </a>
            </li>
      </ul>
      <!-- //sidebar nav end -->
      <!-- toggle button start -->
      <a class="toggle-btn">
        <i class="fa fa-angle-double-left menu-collapsed__left"><span>Collapse Sidebar</span></i>
        <i class="fa fa-angle-double-right menu-collapsed__right"></i>
      </a>
      <!-- //toggle button end -->
    </div>
  </div>
