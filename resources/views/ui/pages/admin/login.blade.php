@extends('ui.layouts.frontend')
@section('title', '| Admin Login')
@section('metaTitle', 'Admin Login | StorageKeys')
@section('metaDescription', 'Sign in to the StorageKeys admin panel to manage bookings, customers and site content.')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb">
                <a href="{{ url('/') }}">Home</a>
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
                <span>Admin Login</span>
            </div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Staff Access</span>
            <h1>Sign in to the <span>admin</span> panel</h1>
            <p class="lead">Access your dashboard to manage bookings, customers, shop orders and site settings.</p>
        </div>
    </section>

    <!-- ============ LOGIN ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="ca-auth-grid sk-reveal in">
                <div class="ca-auth-card">
                    <h2>Admin sign in</h2>
                    <p>Enter your staff email and password to continue.</p>

                    @if (session('status'))
                        <div class="ca-alert ca-alert-success">{{ session('status') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="ca-alert ca-alert-error">{{ session('error') }}</div>
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

                    <form method="POST" action="{{ route('users.login') }}" class="ca-auth-form" id="adminLoginForm">
                        @csrf
                        <div class="ca-field">
                            <label for="admin-email">Email address</label>
                            <input
                                id="admin-email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="you@example.com"
                                required
                                autocomplete="username"
                            >
                        </div>
                        <div class="ca-field">
                            <label for="admin-password">Password</label>
                            <div class="password-field-wrap">
                                <input
                                    id="admin-password"
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

                        <div class="ca-form-meta">
                            <label class="ca-check">
                                <input type="checkbox" name="remember_me" value="1" {{ old('remember_me') ? 'checked' : '' }}>
                                <span>Remember me</span>
                            </label>
                            <a href="{{ route('password-reset.index') }}">Forgot password?</a>
                        </div>

                        <button type="submit" class="sk-btn sk-btn-primary" id="adminLoginSubmit">
                            <i class="fas fa-sign-in-alt" aria-hidden="true"></i> Sign In
                        </button>
                    </form>
                </div>

                <div class="ca-auth-card ca-auth-aside">
                    <div class="ic"><i class="fas fa-shield-alt" aria-hidden="true"></i></div>
                    <h2>Authorised staff only</h2>
                    <p>This area is for StorageKeys team members. Customers should use the customer login instead.</p>
                    <ul>
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i> Manage bookings and units</li>
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i> Handle shop orders</li>
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i> Update site content</li>
                    </ul>
                    <a href="{{ route('customer.login') }}" class="sk-btn sk-btn-outline">
                        <i class="fas fa-user" aria-hidden="true"></i> Customer Login
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@section('javascriptWork')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('adminLoginForm');
    if (!form) return;

    var submitBtn = document.getElementById('adminLoginSubmit');
    var alertHost = form.closest('.ca-auth-card');

    function showError(message) {
        var existing = alertHost.querySelector('.ca-alert-ajax');
        if (existing) existing.remove();
        var box = document.createElement('div');
        box.className = 'ca-alert ca-alert-error ca-alert-ajax';
        box.textContent = message || 'Unable to sign in. Please try again.';
        form.parentNode.insertBefore(box, form);
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var existing = alertHost.querySelector('.ca-alert-ajax');
        if (existing) existing.remove();

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.setAttribute('aria-busy', 'true');
        }

        var body = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: body,
            credentials: 'same-origin'
        }).then(function (res) {
            return res.text().then(function (text) {
                var data = null;
                try { data = text ? JSON.parse(text) : null; } catch (err) { data = text; }
                return { ok: res.ok, status: res.status, data: data, raw: text };
            });
        }).then(function (result) {
            if (result.ok) {
                var redirectUrl = typeof result.data === 'string'
                    ? result.data.replace(/^"|"$/g, '')
                    : (result.data && (result.data.redirect || result.data.url)) || result.raw;
                if (redirectUrl && typeof redirectUrl === 'string' && redirectUrl.indexOf('http') === 0) {
                    window.location.href = redirectUrl;
                    return;
                }
                if (redirectUrl && typeof redirectUrl === 'string' && redirectUrl.charAt(0) === '/') {
                    window.location.href = redirectUrl;
                    return;
                }
                window.location.reload();
                return;
            }

            var message = 'Unable to sign in. Please try again.';
            if (result.data && typeof result.data === 'object') {
                if (result.data.message) message = result.data.message;
                else if (result.data.errors) {
                    var first = Object.values(result.data.errors)[0];
                    message = Array.isArray(first) ? first[0] : String(first);
                }
            }
            showError(message);
        }).catch(function () {
            showError('Unable to sign in. Please try again.');
        }).finally(function () {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.removeAttribute('aria-busy');
            }
        });
    });
});
</script>
@endsection
