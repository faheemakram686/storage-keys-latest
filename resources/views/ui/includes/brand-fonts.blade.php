{{-- Brand book typography: Capitana (licensed, self-hosted). Drop the .woff2 files into public/fonts/capitana/
     and they are picked up automatically; until then --sk-font falls back to Poppins. --}}
@php
    $capitanaWeights = [
        100 => 'Capitana-Thin',
        300 => 'Capitana-Light',
        400 => 'Capitana-Regular',
        500 => 'Capitana-Medium',
        600 => 'Capitana-SemiBold',
    ];
    $capitanaFaces = array_filter($capitanaWeights, fn ($file) => file_exists(public_path("fonts/capitana/{$file}.woff2")));
@endphp
@if($capitanaFaces)
    @isset($capitanaFaces[400])
    <link rel="preload" href="{{ asset('fonts/capitana/Capitana-Regular.woff2') }}" as="font" type="font/woff2" crossorigin>
    @endisset
    <style>
    @foreach($capitanaFaces as $weight => $file)
    @font-face {
        font-family: 'Capitana';
        src: url('{{ asset("fonts/capitana/{$file}.woff2") }}') format('woff2');
        font-weight: {{ $weight }};
        font-style: normal;
        font-display: swap;
    }
    @endforeach
    </style>
@endif
@if(app()->getLocale() === 'ar')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap">
@endif
