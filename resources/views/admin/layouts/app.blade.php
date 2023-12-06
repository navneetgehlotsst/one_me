<!DOCTYPE html>
<html lang="en" ng-app="de-HIVEApp">
    <head>
        <meta charset="utf-8" />
        <title>de-HIVE</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="" />
        <meta content="" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="ws_url" content="{{ env('WS_URL') }}">
        <meta name="user_id" content="{{ Auth::id() }}">
        <meta name="user_image_url" content="{{''}}">
        <link rel="shortcut icon" href="{{asset('assets/admin/images/favicon.png')}}">
        <link href="{{asset('assets/admin/css/vendor/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/admin/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/admin/css/app.min.css')}}" rel="stylesheet" type="text/css" id="light-style" />
        <link href="{{asset('assets/admin/css/app-dark.min.css')}}" rel="stylesheet" type="text/css" id="dark-style" />
        <link href="{{asset('assets/admin/css/vendor/dataTables.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/admin/css/vendor/responsive.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/admin/css/vendor/buttons.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/admin/css/vendor/select.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/admin/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
        @yield('style')
        <style>
            .preloader .loader {
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: -webkit-fill-available;
            }
        </style>
    </head>
    <body class="loading" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
        <div class="wrapper">
            <div class="preloader" id="preloader">
                <div class="loader">
                    <img src="{{asset('assets/admin/images/preloader.jpg')}}" class="loader_img" height="150" width="150">
                </div>
            </div>
            @include('admin.layouts.elements.left-sidebar')
            <div class="content-page">
                <div class="content" id="de-HIVE">
                    @include('admin.layouts.elements.header')
                    @yield('content')
                </div>
                @include('admin.layouts.elements.footer')
            </div>
        </div>
        @include('admin.layouts.elements.right-sidebar')

        <script src="{{asset('assets/admin/js/vendor/dropzone.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/vendor.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/app.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/vendor/apexcharts.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/vendor/jquery-jvectormap-1.2.2.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/vendor/jquery-jvectormap-world-mill-en.js')}}"></script>
        <script src="{{asset('assets/admin/js/pages/demo.dashboard.js')}}"></script>
        <script src="{{asset('assets/admin/js/vendor/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/vendor/dataTables.bootstrap4.js')}}"></script>
        <script src="{{asset('assets/admin/js/vendor/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/vendor/responsive.bootstrap4.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/vendor/dataTables.buttons.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/vendor/buttons.bootstrap4.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/vendor/buttons.html5.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/vendor/dataTables.keyTable.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/vendor/dataTables.select.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/pages/demo.datatable-init.js')}}"></script>
        <script src="{{asset('assets/admin/js/moment.min.js')}}"></script>
        <script src="{{asset('assets/admin/js/select2.min.js')}}"></script>
        @yield('script')
        @include('admin.layouts.elements.sweet_alerts')
    </body>
</html>