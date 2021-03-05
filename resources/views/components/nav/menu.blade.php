<!-- begin::navigation menu -->
<div class="navigation-menu-body">

    <!-- begin::navigation-logo -->
    <div>
        <div id="navigation-logo">
            <a href="{{ route('index') }}">
                <img class="logo" src="{{ asset('assets/media/image/logo.png') }}" alt="logo">
                <img class="logo-light" src="{{ asset('assets/media/image/logo-light.png') }}" alt="light logo">
            </a>
        </div>
    </div>
    <!-- end::navigation-logo -->

    <div class="navigation-menu-group">
        @foreach($menus as $menu)
            @hasanyrole($menu->roles ?? \App\Models\Role::select('name')->get()->toArray())
            <div class="{{ request()->is($menu->menu_tab_prefix.'*') ? 'open' : '' }}" id="{{ $menu->menu_tab_prefix }}">
                <ul>
                    <li class="navigation-divider">{{ $menu->menu_tab_label }}</li>
                    @foreach($menu->child_menus as $child_menu)
                        @hasanyrole($child_menu->roles ?? \App\Models\Role::select('name')->get()->toArray())
                            <li><a class="{{ request()->is($menu->menu_tab_prefix.'/'.$child_menu->prefix.'*') ? 'active' : '' }}" href="{{ route($child_menu->link) }}">{{ $child_menu->label }}</a></li>
                        @endhasanyrole
                    @endforeach
                </ul>
            </div>
            @endhasanyrole
        @endforeach
    </div>
</div>
<!-- end::navigation menu -->