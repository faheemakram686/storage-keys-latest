<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{config('app.name')}} @yield('title')</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('sk-assets/assets/images/frontend/favicon.png') }}" type="image/png">
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('sk-assets/css/app.css') }}"/>
    <link rel="stylesheet" href="{{ asset('sk-assets/css/backend/theme.css') }}"/>

{{--    <link rel="stylesheet" href="{{ asset('sk-assets/css/backend/dataTables.css') }}"/>--}}
{{--    <link rel="stylesheet" href="{{ asset('sk-assets/css/backend/dataTableResponsive.css') }}"/>--}}
{{--    <link rel="stylesheet" href="{{ asset('sk-assets/css/backend/dataTableRowGroup.css') }}"/>--}}
{{--    <link href=" {{ asset('vendor/DataTables/datatables.min.css') }}" rel="stylesheet">--}}

    <link rel="stylesheet" href="{{ asset('sk-assets/css/custom.css') }}"/>
    <link rel="stylesheet" href="{{ asset('sk-assets/css/toastr.css') }}"/>
    <script src="{{ asset('vendor/ckeditor3/build/ckeditor.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" ></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <style>
        .ck-editor__editable {
            min-height: 300px !important;
        }
    </style>

    <!-- page styles -->
    @yield('styles')
    @include('backend.layouts.partials.admin_colors')
</head>

<body class="nk-body bg-lighter npc-default has-sidebar ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- sidebar @s -->
            @include('backend.layouts.partials.sidebar')
            <!-- sidebar @e -->
            <!-- wrap @s -->
            <div class="nk-wrap ">
                <!-- main header @s -->
                @include('backend.layouts.partials.header')
                <!-- main header @e -->
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content @e -->
                <!-- footer @s -->
                @include('backend.layouts.partials.footer')
                <!-- footer @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <!-- general modal -->
    @include('backend.layouts.partials.modal')

    <script src="{{ asset('sk-assets/js/app.js') }}"></script>
    <script src="{{ asset('sk-assets/js/backend/theme.js') }}"></script>
{{--    <script src="{{ asset('sk-assets/js/backend/charts.js') }}"></script>--}}
    <script src="{{ asset('sk-assets/js/backend/inbox.js') }}"></script>
    <script src="{{ asset('sk-assets/js/backend/tagify.js') }}"></script>

    {{--    <script src="{{ asset('sk-assets/js/backend/dataTables.js') }}"></script>--}}
    {{--    <script src="{{ asset('sk-assets/js/backend/dataTableResposive.js') }}"></script>--}}
    {{--    <script src="{{ asset('vendor/DataTables/datatables.min.js') }}"></script>--}}

    <script src="{{ asset('sk-assets/js/backend/bootstrap4.js') }}"></script>
    <script src="{{ asset('sk-assets/js/common.js') }}"></script>
    <script src="{{ asset('sk-assets/js/bundle.js') }}"></script>
    <script src="{{ asset('sk-assets/js/scripts.js') }}"></script>
    <script src="{{ asset('sk-assets/js/datatable-btns.js') }}"></script>




    <script>
        $(document).ready(function() {
            //  $('#datatable').DataTable({
            //     dom: 'Bfrtip',
            //     buttons: [
            //         'copy', 'csv', 'excel', 'pdf', 'print'
            //     ],
            // });
        } );

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Hide alert message if any
        $('div.alert').not('.alert-important').delay(3000).slideUp(350);
    </script>
    <!-- page scripts -->
    @stack('scripts')
</body>

</html>
