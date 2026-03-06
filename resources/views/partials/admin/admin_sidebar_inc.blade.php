<div class="main-menu">
    <div class="logo-box">
        <a class="logo-light" href="{{ route('root.dashboard') }}">
            <img src="{{ asset('admin_assets/images/glovans-logo.svg') }}" alt="GloVans logo" class="logo-lg" height="32">
            <img src="{{ asset('admin_assets/images/glovans-logo-sm.svg') }}" alt="GloVans icon" class="logo-sm" height="32">
        </a>

        <a class="logo-dark" href="{{ route('root.dashboard') }}">
            <img src="{{ asset('admin_assets/images/glovans-logo-dark.svg') }}" alt="GloVans logo" class="logo-lg" height="32">
            <img src="{{ asset('admin_assets/images/glovans-logo-dark-sm.svg') }}" alt="GloVans icon" class="logo-sm" height="32">
        </a>
    </div>

    <div data-simplebar>
        <ul class="app-menu">
            <li class="menu-title">Menu</li>

            <li class="menu-item {{ request()->routeIs('root.dashboard') ? 'active' : '' }}">
                <a class="menu-link waves-effect" href="{{ route('root.dashboard') }}">
                    <span class="menu-icon"><i data-lucide="airplay"></i></span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('root.agents') || request()->routeIs('root.agent_detail') ? 'active' : '' }}">
                <a class="menu-link waves-effect" href="{{ route('root.agents') }}">
                    <span class="menu-icon"><i data-lucide="users"></i></span>
                    <span class="menu-text">Agents</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('root.categories') ? 'active' : '' }}">
                <a class="menu-link waves-effect" href="{{ route('root.categories') }}">
                    <span class="menu-icon"><i data-lucide="ticket"></i></span>
                    <span class="menu-text">Network Categories</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('root.products') ? 'active' : '' }}">
                <a class="menu-link waves-effect" href="{{ route('root.products') }}">
                    <span class="menu-icon"><i data-lucide="package"></i></span>
                    <span class="menu-text">Products</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('root.orders') ? 'active' : '' }}">
                <a class="menu-link waves-effect" href="{{ route('root.orders') }}">
                    <span class="menu-icon"><i data-lucide="shopping-cart"></i></span>
                    <span class="menu-text">Orders</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('root.settings') ? 'active' : '' }}">
                <a class="menu-link waves-effect" href="{{ route('root.settings') }}">
                    <span class="menu-icon"><i data-lucide="settings"></i></span>
                    <span class="menu-text">Settings</span>
                </a>
            </li>
        </ul>
    </div>
</div>
