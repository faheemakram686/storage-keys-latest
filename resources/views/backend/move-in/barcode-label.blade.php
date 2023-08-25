<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>Barcode - Websolutionstuff</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="shortcut icon" href="{{ asset('sk-assets/assets/images/frontend/favicon.png') }}" type="image/png">
    <title>{{config('app.name')}} | Barcode Label</title>
</head>
<body onload="printPromot()">
<div class="container-fluid text-center">
    <h1>Storage Keys</h1>
    @isset($data)
{{--        @dd($data['barcode']);--}}

    <div class="row">
        @foreach($data['barcode'] as $barcode)
        <div class="col-lg-6 offset-lg-2">
                <h5 class="mb-5">Storage Keys</h5>
            <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($barcode->code, 'C39',1,33,array(0,0,0), true)}}" alt="barcode" /><br><br>
        </div>
        @endforeach
    </div>

    @endisset
</div>
<script>
    function printPromot() {
        window.print();
    }
</script>
</body>
</html>