@extends('ui.layouts.frontend')
@section('title', '| Verify OTP')
@section('metaTitle', 'Verify OTP | StorageKeys')
@section('metaDescription', 'Verify your email address to complete registration.')

@section('content')
<div class="sk-home">

    <!-- ============ HERO ============ -->
    <section class="ps-hero">
        <div class="sk-container">
            <div class="ps-crumb">
                <a href="{{ url('/') }}">Home</a>
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
                <span>Verify OTP</span>
            </div>
            <span class="sk-eyebrow" style="color:#ffcf9e;">Email Verification</span>
            <h1>Verify your <span>email address</span></h1>
            <p class="lead">We've sent a 4-digit code to <strong>{{ $maskedEmail }}</strong></p>
        </div>
    </section>

    <!-- ============ OTP VERIFICATION ============ -->
    <section class="sk-section" style="background:var(--sk-soft);">
        <div class="sk-container">
            <div class="ca-auth-grid sk-reveal in" style="max-width: 600px; margin: 0 auto;">
                <div class="ca-auth-card">
                    <div style="text-align: center; margin-bottom: 1.5rem;">
                        <div style="display: inline-block; width: 80px; height: 80px; background: #fff3cd; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                            <i class="fas fa-envelope-open-text" style="font-size: 2.5rem; color: #f39c12;"></i>
                        </div>
                        <h2>Enter Verification Code</h2>
                        <p>Please enter the 4-digit code we sent to your email.</p>
                    </div>

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

                    <form method="POST" action="{{ route('customer.verify.otp') }}" class="ca-auth-form" id="otpVerifyForm">
                        @csrf

                        <div class="ca-field">
                            <label for="otp">OTP Code</label>
                            <input
                                id="otp"
                                type="text"
                                name="otp"
                                placeholder="Enter 4-digit code"
                                maxlength="4"
                                pattern="\d{4}"
                                required
                                autocomplete="off"
                                style="text-align: center; font-size: 1.5rem; letter-spacing: 0.5rem; font-family: 'Courier New', monospace;"
                            >
                        </div>

                        <!-- Countdown Timer -->
                        <div id="timer-display" style="text-align: center; margin: 1rem 0; padding: 1rem; background: #f8f9fa; border-radius: 6px; border-left: 4px solid #f39c12;">
                            <i class="fas fa-clock" aria-hidden="true"></i>
                            <span style="font-weight: bold;">Code expires in: <span id="countdown">60</span> seconds</span>
                        </div>

                        <!-- Expired Message (hidden initially) -->
                        <div id="expired-message" style="text-align: center; margin: 1rem 0; padding: 1rem; background: #fff3cd; border-radius: 6px; border-left: 4px solid #ffc107; display: none;">
                            <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                            <span style="font-weight: bold; color: #856404;">OTP has expired. Please request a new one.</span>
                        </div>

                        <button type="submit" class="sk-btn sk-btn-primary" id="verify-btn">
                            <i class="fas fa-check-circle" aria-hidden="true"></i> Verify Code
                        </button>

                        <!-- Resend Button -->
                        <button 
                            type="button" 
                            class="sk-btn sk-btn-outline" 
                            id="resend-btn" 
                            disabled
                            style="margin-top: 1rem;"
                        >
                            <i class="fas fa-redo" aria-hidden="true"></i> Resend Code
                        </button>

                        <p id="resend-message" style="text-align: center; margin-top: 0.5rem; font-size: 0.9rem; color: #666;">
                            You can request a new code in <span id="resend-countdown">60</span> seconds
                        </p>

                    </form>

                    <div style="text-align: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #eee;">
                        <p style="margin: 0; color: #666;">
                            <i class="fas fa-info-circle" aria-hidden="true"></i> 
                            Didn't receive the email? Check your spam folder or click resend.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@section('javascriptWork')
<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('OTP Verification Page - JavaScript Loaded');
    
    let countdownSeconds = 60;
    let resendCountdownSeconds = 60;
    let countdownInterval;
    let resendCountdownInterval;

    const countdownElement = document.getElementById('countdown');
    const resendCountdownElement = document.getElementById('resend-countdown');
    const resendBtn = document.getElementById('resend-btn');
    const resendMessage = document.getElementById('resend-message');
    const timerDisplay = document.getElementById('timer-display');
    const expiredMessage = document.getElementById('expired-message');
    const verifyBtn = document.getElementById('verify-btn');
    const otpInput = document.getElementById('otp');

    console.log('Elements found:', {
        resendBtn: !!resendBtn,
        countdownElement: !!countdownElement,
        resendCountdownElement: !!resendCountdownElement
    });

    // Focus on OTP input
    if (otpInput) {
        otpInput.focus();
    }

    // Only allow digits in OTP input
    if (otpInput) {
        otpInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    // Start OTP expiry countdown
    function startCountdown() {
        console.log('Starting OTP expiry countdown from ' + countdownSeconds + ' seconds');
        clearInterval(countdownInterval);
        
        countdownInterval = setInterval(function() {
            countdownSeconds--;
            if (countdownElement) {
                countdownElement.textContent = countdownSeconds;
            }

            if (countdownSeconds <= 0) {
                clearInterval(countdownInterval);
                console.log('OTP has expired');
                if (timerDisplay) timerDisplay.style.display = 'none';
                if (expiredMessage) expiredMessage.style.display = 'block';
                if (verifyBtn) verifyBtn.disabled = true;
            }
        }, 1000);
    }

    // Start resend button countdown
    function startResendCountdown() {
        console.log('Starting resend countdown from ' + resendCountdownSeconds + ' seconds');
        clearInterval(resendCountdownInterval);
        
        if (!resendBtn) {
            console.error('Resend button not found!');
            return;
        }
        
        resendBtn.disabled = true;
        console.log('Resend button disabled: ' + resendBtn.disabled);
        
        if (resendMessage) resendMessage.style.display = 'block';
        
        resendCountdownInterval = setInterval(function() {
            resendCountdownSeconds--;
            if (resendCountdownElement) {
                resendCountdownElement.textContent = resendCountdownSeconds;
            }

            if (resendCountdownSeconds <= 0) {
                console.log('Resend countdown complete - enabling button');
                clearInterval(resendCountdownInterval);
                resendBtn.disabled = false;
                console.log('Resend button enabled: ' + !resendBtn.disabled);
                if (resendMessage) resendMessage.style.display = 'none';
            }
        }, 1000);
    }

    // Initialize countdowns
    startCountdown();
    startResendCountdown();

    // Resend OTP handler
    if (resendBtn) {
        console.log('Attaching click handler to resend button');
        
        resendBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Resend button clicked!');
            
            const button = this;
            
            // Check if button is disabled
            if (button.disabled) {
                console.warn('Button is disabled, click ignored');
                return false;
            }
            
            console.log('Processing resend request...');
            
            // Disable button and show loading
            button.disabled = true;
            const originalHtml = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            
            const url = '{{ route("customer.resend.otp") }}';
            const csrfToken = '{{ csrf_token() }}';
            
            console.log('Making AJAX request to:', url);
            console.log('CSRF Token:', csrfToken.substring(0, 10) + '...');

            // Make AJAX request
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success) {
                    console.log('Resend successful!');
                    
                    // Reset countdown
                    countdownSeconds = 60;
                    resendCountdownSeconds = 60;
                    
                    // Clear old intervals
                    clearInterval(countdownInterval);
                    clearInterval(resendCountdownInterval);
                    
                    // Restart countdowns
                    startCountdown();
                    startResendCountdown();
                    
                    // Show/hide elements
                    if (timerDisplay) timerDisplay.style.display = 'block';
                    if (expiredMessage) expiredMessage.style.display = 'none';
                    if (verifyBtn) verifyBtn.disabled = false;
                    
                    // Show success message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'ca-alert ca-alert-success';
                    alertDiv.textContent = data.message || 'New OTP has been sent to your email.';
                    const form = document.querySelector('.ca-auth-form');
                    if (form) form.insertBefore(alertDiv, form.firstChild);
                    
                    // Remove alert after 5 seconds
                    setTimeout(() => alertDiv.remove(), 5000);
                } else {
                    console.error('Resend failed:', data.message);
                    
                    // Show error message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'ca-alert ca-alert-error';
                    alertDiv.textContent = data.message || 'Failed to resend OTP. Please try again.';
                    const form = document.querySelector('.ca-auth-form');
                    if (form) form.insertBefore(alertDiv, form.firstChild);
                    
                    // Re-enable button on error
                    button.disabled = false;
                    
                    // Remove alert after 5 seconds
                    setTimeout(() => alertDiv.remove(), 5000);
                }
                
                // Restore button text
                button.innerHTML = originalHtml;
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                
                // Show error message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'ca-alert ca-alert-error';
                alertDiv.textContent = 'Network error. Please check your connection and try again.';
                const form = document.querySelector('.ca-auth-form');
                if (form) form.insertBefore(alertDiv, form.firstChild);
                
                // Restore button and re-enable
                button.innerHTML = originalHtml;
                button.disabled = false;
                
                // Remove alert after 5 seconds
                setTimeout(() => alertDiv.remove(), 5000);
            });
            
            return false;
        });
        
        console.log('Resend button click handler attached successfully');
    } else {
        console.error('Resend button (#resend-btn) not found in DOM!');
    }
});
</script>
@endsection
