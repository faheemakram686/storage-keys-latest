{{-- Google Fonts outside CSS @import (avoids render-blocking chain through style.css) --}}
{{-- Figtree is the stand-in for the brand font Capitana (see ui.includes.brand-fonts); weights match the brand book: light, regular, medium, semibold --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" media="print" onload="this.media='all'">
<noscript>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap">
</noscript>
@include('ui.includes.brand-fonts')
