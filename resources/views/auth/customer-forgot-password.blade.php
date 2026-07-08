@extends('ui.layouts.frontend2')
@section('title', '| Forgot Password')
@section('content')

    <div class="ltn__utilize-overlay"></div>

    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image" data-bg="{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_1.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Forgot Password</h1>
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
                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <p class="mb-4">Enter your email address and we will send you a link to reset your password.</p>
                        <form method="POST" action="{{ route('customer.password.email') }}" class="ltn__form-box contact-form-box">
                            @csrf
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email*" required>
                            <div class="btn-wrapper mt-0">
                                <button class="theme-btn-1 btn btn-block" type="submit">SEND RESET LINK</button>
                            </div>
                            <div class="go-to-btn mt-20">
                                <a href="{{ route('customer.login') }}"><small>BACK TO LOGIN</small></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
