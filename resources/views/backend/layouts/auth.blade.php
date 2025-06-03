<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{config('app.name')}} @yield('title')</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('sk-assets/assets/images/frontend/favicon.png') }}" type="image/png">

    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('sk-assets/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('sk-assets/css/backend/theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('sk-assets/css/custom.css') }}" />

    <!-- page styles -->
    @yield('styles')
    @include('backend.layouts.partials.admin_colors')
</head>

<body class="nk-body bg-lighter npc-default pg-auth">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content ">
                    @yield('content')
                </div>
                <!-- wrap @e -->
            </div>
            <!-- content @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <!-- JavaScript -->
    <script src="{{ asset('sk-assets/js/app.js') }}"></script>
    <script src="{{ asset('sk-assets/js/backend/theme.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Hide alert message if any
        $('div.alert').not('.alert-important').delay(3000).slideUp(350);
    </script>
</body>

</html>