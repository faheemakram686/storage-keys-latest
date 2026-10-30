{{-- Figtree is self-hosted and preloaded so it is ready at first paint; loaded later from Google Fonts it
     swapped in after render and re-wrapped the hero text (mobile CLS). One variable file covers weights 300-700.
     $inlineLatin (home): the latin file is embedded as a data: URI. Home paints before a preloaded font can
     arrive, and the swap re-wrapped the hero badges (CLS 0.12); an embedded font is there at first paint. --}}
@php
    $figtreeLatin = asset('sk-assets/css/fonts/figtree-latin.woff2');
    if ($inlineLatin ?? false) {
        $figtreeData = @file_get_contents(public_path('sk-assets/css/fonts/figtree-latin.woff2'));
        if ($figtreeData) {
            $figtreeLatin = 'data:font/woff2;base64,' . base64_encode($figtreeData);
        }
    }
@endphp
@if(!str_starts_with($figtreeLatin, 'data:'))
<link rel="preload" href="{{ $figtreeLatin }}" as="font" type="font/woff2" crossorigin>
@endif
<style>
@font-face {
  font-family: 'Figtree';
  font-style: normal;
  font-weight: 300 700;
  font-display: swap;
  src: url('{{ asset('sk-assets/css/fonts/figtree-latin-ext.woff2') }}') format('woff2');
  unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C4, U+2113, U+2C60-2C7F, U+A720-A7FF;
}
@font-face {
  font-family: 'Figtree';
  font-style: normal;
  font-weight: 300 700;
  font-display: swap;
  src: url('{{ $figtreeLatin }}') format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
</style>
@if(str_starts_with($figtreeLatin, 'data:'))
{{-- Chrome only starts decoding a font when layout first needs it, which is after first paint; start now. --}}
<script>document.fonts && document.fonts.load('500 1em Figtree');</script>
@endif
