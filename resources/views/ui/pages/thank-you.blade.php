@extends('ui.layouts.thankyou')
@section('title', '| Thank You')
@section('metaTitle', 'Thank You | Storage Keys')
@section('metaDescription', 'Your quote request has been received. The Storage Keys team will be in touch shortly.')

@section('content')
@php
    $inquiry = session('inquiry', []);
    $name = trim((string) ($inquiry['name'] ?? ''));
    $firstName = $name !== '' ? explode(' ', $name)[0] : '';
    $email = $inquiry['email'] ?? null;
    $storage = $inquiry['storage_type'] ?? null;
    $reference = $inquiry['reference'] ?? null;
@endphp

<main class="sk-thanks">
    <div class="sk-thanks-glow sk-thanks-glow-a"></div>
    <div class="sk-thanks-glow sk-thanks-glow-b"></div>
    <span class="sk-thanks-dot d1"></span>
    <span class="sk-thanks-dot d2"></span>
    <span class="sk-thanks-dot d3"></span>
    <span class="sk-thanks-dot d4"></span>

    <div class="sk-thanks-wrap">
        <div class="sk-thanks-card">
            <div class="sk-thanks-check" aria-hidden="true">
                <svg viewBox="0 0 72 72">
                    <circle class="ring" cx="36" cy="36" r="32"></circle>
                    <path class="tick" d="M22 37.5 L31.5 47 L51 26"></path>
                </svg>
            </div>

            <p class="sk-thanks-kicker">Request received</p>
            <h1>@if($firstName)Thank you, {{ $firstName }}@else Thank you@endif</h1>
            <p class="sk-thanks-lead">
                We’ve got your quote request@if($storage) for <strong>{{ $storage }}</strong>@endif.
                A Storage Keys advisor will contact you shortly with the right unit and pricing.
            </p>

            @if($reference || $email)
                <div class="sk-thanks-meta">
                    @if($reference)
                        <div>
                            <span>Reference</span>
                            <strong>{{ $reference }}</strong>
                        </div>
                    @endif
                    @if($email)
                        <div>
                            <span>We’ll reach you at</span>
                            <strong>{{ $email }}</strong>
                        </div>
                    @endif
                </div>
            @endif

            <ol class="sk-thanks-steps">
                <li>
                    <em>01</em>
                    <div>
                        <strong>We review your request</strong>
                        <span>Usually within a few hours during business days.</span>
                    </div>
                </li>
                <li>
                    <em>02</em>
                    <div>
                        <strong>You get a tailored quote</strong>
                        <span>Unit size, location and pricing matched to what you need.</span>
                    </div>
                </li>
                <li>
                    <em>03</em>
                    <div>
                        <strong>Move in when you’re ready</strong>
                        <span>Flexible terms — no long lock-in required.</span>
                    </div>
                </li>
            </ol>

            <div class="sk-thanks-cta">
                <a href="https://wa.me/971565018785" class="sk-btn sk-btn-wa"><i class="fab fa-whatsapp"></i> WhatsApp us now</a>
                <a href="{{ url('/booking') }}" class="sk-btn sk-btn-primary"><i class="fas fa-boxes"></i> Book a unit</a>
                <a href="{{ url('/') }}" class="sk-btn sk-btn-ghost"><i class="fas fa-home"></i> Back to home</a>
            </div>
        </div>
    </div>
</main>
@endsection
