<!DOCTYPE html>
<html lang="zxx" class="js">
@isset($expire)
<head>
    <base href="../../../">
    <meta charset="utf-8">
    <meta name="author" content="Storage Keys">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{$expire['messege']}}">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('sk-assets/assets/images/frontend/favicon.png') }}" type="image/png">
    <!-- Page Title  -->
    <title>Error {{$expire['code']}} | {{$expire['title']}}</title>
    <!-- StyleSheets  -->
{{--    <link rel="stylesheet" href="{{ asset('sk-assets/css/app.css') }}"/>--}}
    <link rel="stylesheet" href="{{ asset('sk-assets/css/backend/theme.css') }}"/>
</head>

<body class="nk-body bg-white npc-default pg-error">
<div class="nk-app-root">
    <!-- main @s -->
    <div class="nk-main ">
        <!-- wrap @s -->
        <div class="nk-wrap nk-wrap-nosidebar">
            <!-- content @s -->

                <div class="nk-content ">
                    <div class="nk-block nk-block-middle wide-xs mx-auto">
                        <div class="nk-block-content nk-error-ld text-center">
                            <h1 class="nk-error-head">{{$expire['code']}}</h1>
                            <h3 class="nk-error-title">{{$expire['title']}}</h3>
                            <p class="nk-error-text">{{$expire['messege']}}</p>
                            <a href="{{url('/')}}" class="btn btn-lg btn-primary mt-2">Back To Home</a>
                        </div>
                    </div><!-- .nk-block -->
                </div>

        </div>
        <!-- content @e -->
    </div>
    <!-- main @e -->
</div>
<!-- app-root @e -->
<!-- JavaScript -->
</body>
@endisset
</html>