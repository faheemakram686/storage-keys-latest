@extends('ui.layouts.frontend')
@section('title', '| Customer Login')
@section('metaTitle', 'Customer Login | StorageKeys')
@section('metaDescription', 'Sign in to your StorageKeys customer account to manage bookings, orders and storage details.')

@section('content')
<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb">
                <a href="{{ url('/') }}">Home</a>
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
                <span>Customer Login</span>
            </div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Customer Account</span>
            <h1>Sign in to your <span>StorageKeys</span> account</h1>
            <p class="lead">Access your dashboard, track orders and manage your storage details in one place.</p>
        </div>
    </section>

    <!-- ============ LOGIN ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="ca-auth-grid sk-reveal in">
                <div class="ca-auth-card">
                    <h2>Welcome back</h2>
                    <p>Enter your email and password to continue.</p>

                    @if (session('status'))
                        <div class="ca-alert ca-alert-success">{{ session('status') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="ca-alert ca-alert-error">
                            <strong>Please check the following:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('customer.login.post') }}" class="ca-auth-form">
                        @csrf
                        <div class="ca-field">
                            <label for="customer-email">Email address</label>
                            <input
                                id="customer-email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="you@example.com"
                                required
                                autocomplete="email"
                            >
                        </div>
                        <div class="ca-field">
                            <label for="customer-password">Password</label>
                            <div class="password-field-wrap">
                                <input
                                    id="customer-password"
                                    type="password"
                                    name="password"
                                    placeholder="Your password"
                                    required
                                    autocomplete="current-password"
                                >
                                <button type="button" class="password-toggle" aria-label="Show password">
                                    <i class="far fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="sk-btn sk-btn-primary">
                            <i class="fas fa-sign-in-alt" aria-hidden="true"></i> Sign In
                        </button>
                        <div class="ca-auth-links">
                            <a href="{{ route('customer.password.request') }}">Forgotten your password?</a>
                        </div>
                    </form>
                </div>

                <div class="ca-auth-card ca-auth-aside">
                    <div class="ic"><i class="fas fa-user-plus" aria-hidden="true"></i></div>
                    <h2>Don't have an account?</h2>
                    <p>Create a free StorageKeys account to book storage, shop online and manage your details.</p>
                    <ul>
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i> Faster checkout and booking</li>
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i> Track orders and reservations</li>
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i> Manage your account details</li>
                    </ul>
                    <a href="{{ route('customer.register') }}" class="sk-btn sk-btn-outline">
                        <i class="fas fa-user" aria-hidden="true"></i> Create Account
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
