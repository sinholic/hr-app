<!-- begin::navigation menu -->
<div class="navigation-menu-body">

    <!-- begin::navigation-logo -->
    <div>
        <div id="navigation-logo">
            <a href="{{ route('dashboard') }}">
                <img class="logo" src="{{ asset('assets/media/image/logo.png') }}" alt="logo">
                <img class="logo-light" src="{{ asset('assets/media/image/logo-light.png') }}" alt="light logo">
            </a>
        </div>
    </div>
    <!-- end::navigation-logo -->

    <div class="navigation-menu-group">
        @foreach($menus as $menu)
        <div class="{{ request()->is($menu->menu_tab_id.'*') ? 'open' : '' }}" id="{{ $menu->menu_tab_id }}">
            <ul>
                <li class="navigation-divider">{{ $menu->menu_tab_label }}</li>
                @foreach($menu->child_menus as $child_menu)
                    <li><a class="{{ request()->is($menu->menu_tab_id.'/'.$child_menu->id.'*') ? 'active' : '' }}" href="{{ route($child_menu->link) }}">{{ $child_menu->label }}</a></li>
                @endforeach
            </ul>
        </div>
        @endforeach
    </div>
</div>
<!-- end::navigation menu -->