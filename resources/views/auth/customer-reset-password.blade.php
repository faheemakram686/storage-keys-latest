@extends('ui.layouts.frontend2')
@section('title', '| Reset Password')
@section('content')

    <div class="ltn__utilize-overlay"></div>

    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image" data-bg="{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_1.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Reset Password</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ltn__login-area pb-65">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="account-login-inner">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('customer.password.update') }}" class="ltn__form-box contact-form-box">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">
                            <div class="password-field-wrap">
                                <input type="password" name="password" placeholder="New Password*" required>
                                <button type="button" class="password-toggle" aria-label="Show password">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-field-wrap">
                                <input type="password" name="password_confirmation" placeholder="Confirm Password*" required>
                                <button type="button" class="password-toggle" aria-label="Show password">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            <div class="btn-wrapper mt-0">
                                <button class="theme-btn-1 btn btn-block" type="submit">RESET PASSWORD</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
