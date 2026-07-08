@extends('backend.layouts.auth')

@section('content')
<div class="nk-block nk-block-middle nk-auth-body wide-xs">
    <div class="brand-logo pb-4 text-center">
        <a href="/" class="logo-link">
            <img class="logo-light logo-img logo-img-lg" src="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}" srcset="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}" alt="logo">
            <img class="logo-dark logo-img logo-img-lg" src="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}" srcset="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}" alt="logo-dark">
        </a>
    </div>
    <div class="card">
        <div class="card-inner card-inner-lg">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h5 class="nk-block-title">Reset password</h5>
                    <div class="nk-block-des">
                        <p>Enter your new password below.</p>
                    </div>
                </div>
            </div>
            @include('backend.layouts.alert')
            <form method="POST" action="{{ route('admin.password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                <div class="form-group">
                    <div class="form-label-group">
                        <label class="form-label" for="password">New Password</label>
                    </div>
                    <div class="form-control-wrap">
                        <a href="#" class="form-icon form-icon-right passcode-switch lg" data-target="password">
                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                        </a>
                        <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter new password" required>
                        @error('password')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-label-group">
                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                    </div>
                    <div class="form-control-wrap">
                        <a href="#" class="form-icon form-icon-right passcode-switch lg" data-target="password_confirmation">
                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                        </a>
                        <input type="password" class="form-control form-control-lg" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" required>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Reset Password</button>
                </div>
            </form>
            <div class="form-note-s2 text-center pt-4">
                <a href="{{ route('login') }}"><strong>Return to login</strong></a>
            </div>
        </div>
    </div>
</div>
@include('backend.layouts.partials.footer')
@endsection
