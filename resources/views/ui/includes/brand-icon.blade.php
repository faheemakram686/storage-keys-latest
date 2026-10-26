{{-- Brand book icons (p.15–17), drawn as two-tone line art: main stroke follows currentColor, accent is Tangerine.
     Usage: @include('ui.includes.brand-icon', ['name' => 'security'])
     Replace a path set here with the designers' SVG export when it arrives; call sites stay the same. --}}
@php
    $size = $size ?? 26;
    $accent = '#F48922';
@endphp
<svg class="sk-brand-svg" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
@switch($name)
    @case('security')
        {{-- Keyhole --}}
        <path d="M24 5a9.5 9.5 0 0 0-5.4 17.3L15.5 43h17l-3.1-20.7A9.5 9.5 0 0 0 24 5z"/>
        <path stroke="{{ $accent }}" d="M24 11a4 4 0 0 0-2.2 7.3L19.8 37h8.4l-2-18.7A4 4 0 0 0 24 11z"/>
        @break
    @case('safe')
        {{-- Garage with padlock: "your belongings are kept safe 24/7" --}}
        <path d="M5 42V19L24 8l19 11v8"/>
        <path d="M10 42V23h22v5"/>
        <path stroke="{{ $accent }}" d="M14 28h14M14 33h12M14 38h12"/>
        <rect stroke="{{ $accent }}" x="30" y="33" width="13" height="10" rx="1.5"/>
        <path stroke="{{ $accent }}" d="M32.5 33v-3a4 4 0 0 1 8 0v3"/>
        @break
    @case('pickup')
        {{-- House with hand trolley and box: "our team is available for pick-up" --}}
        <path d="M17 20L29 9l12 11v19H25"/>
        <path d="M35 13V7h4v9"/>
        <path d="M3 21l3 1 6 18"/>
        <circle cx="13" cy="42" r="2.6"/>
        <path d="M15.5 41l13-4"/>
        <path stroke="{{ $accent }}" d="M10 25l9-3 4 12-9 3z"/>
        <path stroke="{{ $accent }}" d="M13.5 24l1.2 3.6 2.6-.9-1.2-3.6"/>
        @break
    @case('tracking')
        {{-- Phone with box and route: "tracking your belongings made easier" --}}
        <rect x="12" y="3" width="24" height="42" rx="2"/>
        <path d="M21 7h6M21 41h6"/>
        <rect stroke="{{ $accent }}" x="17" y="12" width="14" height="12"/>
        <path stroke="{{ $accent }}" d="M22.5 12v4h3v-4"/>
        <path d="M18 34a2.5 2.5 0 1 1 5 0c0 2-2.5 4-2.5 4s-2.5-2-2.5-4zM25 34a2.5 2.5 0 1 1 5 0c0 2-2.5 4-2.5 4s-2.5-2-2.5-4z"/>
        @break
    @case('delivery')
        {{-- Moving truck with speed lines: "we deliver when you need" --}}
        <path d="M14 14h20v22H14"/>
        <path d="M34 20h6l5 7v9h-4"/>
        <path d="M37 21v6h7"/>
        <circle cx="19" cy="38" r="3.4"/>
        <circle cx="38" cy="38" r="3.4"/>
        <path d="M22.5 38h12"/>
        <path d="M2 19h8M5 24h7M2 29h8"/>
        <path stroke="{{ $accent }}" d="M24 19c-3 0-4 2-4 4s2 3 4 3 4 1 4 3-1 4-4 4M27 18l-6 6M28 22l-5 5"/>
        @break
    @case('insurance')
        {{-- Shield with tick --}}
        <path d="M24 4l16 5.5V22c0 10.5-7 17.5-16 22-9-4.5-16-11.5-16-22V9.5z"/>
        <path d="M24 9.5l11 3.8V22c0 7.6-4.8 13-11 16.6C17.8 35 13 29.6 13 22v-8.7z"/>
        <path stroke="{{ $accent }}" d="M18.5 23.5l4 4L30 19.5"/>
        @break
@endswitch
</svg>
