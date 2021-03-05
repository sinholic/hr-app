<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}{{ $title != "" ? ' | '.$title : '' }}{{ $subTitle != "" ? ' | '.$subTitle : '' }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/media/image/favicon.png') }}" />

    <!-- Plugin styles -->
    <link rel="stylesheet" href="{{ asset('vendors/bundle.css') }}" type="text/css">
    <!-- <link rel="stylesheet" href="{{ asset('assets/vendors/pris/prism.css') }}" type="text/css"> -->

    <!-- App styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" type="text/css">
    @yield('css-header')
</head>
<body class="navigation-toggle-two">
    <!-- begin::preloader-->
    <div class="preloader">
        <div class="preloader-icon"></div>
    </div>
    <!-- end::preloader -->
    <?php
        $menus = array(
            (object) array(
                'menu_tab_prefix'   => 'dashboard',
                'menu_tab_label'    => 'Dashboards',
                'menu_tab_icon'     => 'bar-chart-2',
                'child_menus'       => array(
                    (object) array(
                        'label'     => 'HR System',
                        'link'      => 'dashboard.hr',
                        'prefix'    => 'hr'
                    ),
                ),
            ),
            (object) array(
                'menu_tab_prefix'   => 'application',
                'menu_tab_label'    => 'Apps',
                'menu_tab_icon'     => 'command',
                'child_menus'       => array(
                    (object) array(
                        'label'     => 'Recruitment',
                        'link'      => 'recruitments.index',
                        'prefix'    => 'recruitments',
                        // 'roles'     =>  ['Super Admin', 'HR Manager', 'Management']
                    ),
                ),
            ),
            (object) array(
                'menu_tab_prefix'   => 'settings',
                'menu_tab_label'    => 'Settings',
                'menu_tab_icon'     => 'sliders',
                'roles'             =>  ['Super Admin', 'HR Manager', 'Management'],
                'child_menus'       => array(
                    (object) array(
                        'label'     => 'Candidate Status',
                        'link'      => 'candidate_status.index',
                        'prefix'    => 'candidate-status'
                    ),
                    (object) array(
                        'label'     => 'Departments',
                        'link'      => 'departments.index',
                        'prefix'    => 'departments'
                    ),
                    (object) array(
                        'label'     => 'Users',
                        'link'      => 'users.index',
                        'prefix'    => 'users'
                    ),
                ),
            ),
        );
    ?>
    <x-nav.MenuHeader />

    <div id="main">
        <!-- begin::navigation -->
        <div class="navigation">
            <x-nav.MenuTab :menus="$menus" />
            <x-nav.Menu :menus="$menus" />
        </div>
        <!-- end::navigation -->
        <!-- begin::main content -->
        <main class="main-content">
            <x-page.PageHeader :title=$title :sub-title=$subTitle :breadcrumbs=$breadcrumbs />
            <div class="container-fluid">
                <x-page.PageAlert />
                @yield('content')
            </div>
            <!-- begin::footer -->
            <footer>
                <div class="container-fluid">
                    <div>© 2021 Prodable v1.0.0 Made by <a href="#">Mahar</a></div>
                    <!-- <div>
                        <nav class="nav">
                            <a href="https://themeforest.net/licenses/standard" class="nav-link">Licenses</a>
                            <a href="#" class="nav-link">Change Log</a>
                            <a href="#" class="nav-link">Get Help</a>
                        </nav>
                    </div> -->
                </div>
            </footer>
            <!-- end::footer -->

        </main>
        <!-- end::main content -->

    </div>
    <!-- Plugin scripts -->
    <script src="{{ asset('vendors/bundle.js') }}"></script>
    <!-- Circle progress -->
    <script src="{{ asset('vendors/circle-progress/circle-progress.min.js') }}"></script>
    <!-- App scripts -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @yield('js-footer')
</body>
</html>
