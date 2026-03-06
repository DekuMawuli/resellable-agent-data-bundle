<div class="main-menu">
    <div class="logo-box">
        <a class="logo-light" href="{{ route('agent.dashboard') }}">
            <img src="{{ asset('admin_assets/images/glovans-logo.svg') }}" alt="GloVans logo" class="logo-lg" height="32">
            <img src="{{ asset('admin_assets/images/glovans-logo-sm.svg') }}" alt="GloVans icon" class="logo-sm" height="32">
        </a>

        <a class="logo-dark" href="{{ route('agent.dashboard') }}">
            <img src="{{ asset('admin_assets/images/glovans-logo-dark.svg') }}" alt="GloVans logo" class="logo-lg" height="32">
            <img src="{{ asset('admin_assets/images/glovans-logo-dark-sm.svg') }}" alt="GloVans icon" class="logo-sm" height="32">
        </a>
    </div>

    <div data-simplebar>
        <ul class="app-menu">
            <li class="menu-title">Agent Menu</li>

            <li class="menu-item {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
                <a class="menu-link waves-effect" href="{{ route('agent.dashboard') }}">
                    <span class="menu-icon"><i data-lucide="home"></i></span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('agent.orders') ? 'active' : '' }}">
                <a class="menu-link waves-effect" href="{{ route('agent.orders') }}">
                    <span class="menu-icon"><i data-lucide="clipboard-list"></i></span>
                    <span class="menu-text">My Orders</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('agent.products') ? 'active' : '' }}">
                <a class="menu-link waves-effect" href="{{ route('agent.products') }}">
                    <span class="menu-icon"><i data-lucide="shopping-cart"></i></span>
                    <span class="menu-text">Buy Package</span>
                </a>
            </li>
        </ul>
    </div>
</div>
