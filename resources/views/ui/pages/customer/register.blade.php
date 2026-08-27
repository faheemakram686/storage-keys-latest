@extends('ui.layouts.frontend')
@section('title', '| Customer Register')
@section('metaTitle', 'Create Account | StorageKeys')
@section('metaDescription', 'Create a free StorageKeys customer account to book storage, shop online and manage your details.')

@section('content')
<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb">
                <a href="{{ url('/') }}">Home</a>
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
                <span>Create Account</span>
            </div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Customer Account</span>
            <h1>Create your <span>StorageKeys</span> account</h1>
            <p class="lead">Register once to book storage, shop packing supplies and manage your details in one place.</p>
        </div>
    </section>

    <!-- ============ REGISTER ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="ca-auth-grid ca-auth-grid-register sk-reveal in">
                <div class="ca-auth-card">
                    <h2>Create account</h2>
                    <p>Fill in your details below. It only takes a minute.</p>

                    @if (session('success'))
                        <div class="ca-alert ca-alert-success">{{ session('success') }}</div>
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

                    <form method="POST" action="{{ route('customer.register') }}" class="ca-auth-form" id="customerRegisterForm">
                        @csrf

                        <div class="ca-type-toggle" role="radiogroup" aria-label="Account type">
                            <div class="ca-type-option">
                                <input type="radio" name="customer_type" id="type_individual" value="individual" {{ old('customer_type', 'individual') === 'individual' ? 'checked' : '' }}>
                                <label for="type_individual">Individual</label>
                            </div>
                            <div class="ca-type-option">
                                <input type="radio" name="customer_type" id="type_company" value="company" {{ old('customer_type') === 'company' ? 'checked' : '' }}>
                                <label for="type_company">Company</label>
                            </div>
                        </div>

                        <div class="ca-field" id="company_name_field" hidden>
                            <label for="company_name">Company name</label>
                            <input
                                id="company_name"
                                type="text"
                                name="company_name"
                                value="{{ old('company_name') }}"
                                placeholder="Your company name"
                                autocomplete="organization"
                            >
                        </div>

                        <div class="ca-field-row">
                            <div class="ca-field">
                                <label for="first_name">First name</label>
                                <input
                                    id="first_name"
                                    type="text"
                                    name="first_name"
                                    value="{{ old('first_name') }}"
                                    placeholder="First name"
                                    required
                                    autocomplete="given-name"
                                >
                            </div>
                            <div class="ca-field">
                                <label for="last_name">Last name</label>
                                <input
                                    id="last_name"
                                    type="text"
                                    name="last_name"
                                    value="{{ old('last_name') }}"
                                    placeholder="Last name"
                                    required
                                    autocomplete="family-name"
                                >
                            </div>
                        </div>

                        <div class="ca-field">
                            <label for="register-email">Email address</label>
                            <input
                                id="register-email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="you@example.com"
                                required
                                autocomplete="email"
                            >
                        </div>

                        <div class="ca-field">
                            <label for="register-password">Password</label>
                            <div class="password-field-wrap">
                                <input
                                    id="register-password"
                                    type="password"
                                    name="password"
                                    placeholder="At least 8 characters"
                                    required
                                    autocomplete="new-password"
                                >
                                <button type="button" class="password-toggle" aria-label="Show password">
                                    <i class="far fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

                        <div class="ca-field">
                            <label for="register-password-confirm">Confirm password</label>
                            <div class="password-field-wrap">
                                <input
                                    id="register-password-confirm"
                                    type="password"
                                    name="password_confirmation"
                                    placeholder="Re-enter password"
                                    required
                                    autocomplete="new-password"
                                >
                                <button type="button" class="password-toggle" aria-label="Show password">
                                    <i class="far fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="sk-btn sk-btn-primary">
                            <i class="fas fa-user-plus" aria-hidden="true"></i> Create Account
                        </button>

                        <p class="ca-agree">
                            By creating an account, you agree to our
                            <a href="{{ url('/privacy-policy') }}">Privacy Policy</a>
                            and
                            <a href="{{ url('/terms-of-service') }}">Terms of Service</a>.
                        </p>
                    </form>
                </div>

                <div class="ca-auth-card ca-auth-aside">
                    <div class="ic"><i class="fas fa-sign-in-alt" aria-hidden="true"></i></div>
                    <h2>Already have an account?</h2>
                    <p>Sign in to access your dashboard, bookings and orders.</p>
                    <ul>
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i> Faster checkout and booking</li>
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i> Track orders and reservations</li>
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i> Manage your account details</li>
                    </ul>
                    <a href="{{ route('customer.login') }}" class="sk-btn sk-btn-outline">
                        <i class="fas fa-sign-in-alt" aria-hidden="true"></i> Sign In
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
    var typeIndividual = document.getElementById('type_individual');
    var typeCompany = document.getElementById('type_company');
    var companyField = document.getElementById('company_name_field');
    var companyInput = document.getElementById('company_name');
    if (!typeIndividual || !typeCompany || !companyField || !companyInput) return;

    function toggleCompany() {
        var isCompany = typeCompany.checked;
        companyField.hidden = !isCompany;
        if (isCompany) {
            companyInput.setAttribute('required', 'required');
        } else {
            companyInput.removeAttribute('required');
            companyInput.value = '';
        }
    }

    typeIndividual.addEventListener('change', toggleCompany);
    typeCompany.addEventListener('change', toggleCompany);
    toggleCompany();
});
</script>
@endsection
