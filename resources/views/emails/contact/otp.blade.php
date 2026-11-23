<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Registration OTP</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="color: #f39c12; margin-bottom: 20px;">Registration Verification</h2>
        
        <p>Hi {{ $customerName }},</p>
        
        <p>Thank you for registering with {{ config('app.name') }}. To complete your registration, please use the following One-Time Password (OTP):</p>
        
        <div style="background-color: #f8f9fa; border: 2px dashed #f39c12; padding: 20px; text-align: center; margin: 25px 0; border-radius: 6px;">
            <h1 style="color: #f39c12; font-size: 36px; letter-spacing: 8px; margin: 0; font-family: 'Courier New', monospace;">{{ $otpCode }}</h1>
        </div>
        
        <p style="color: #e74c3c; font-weight: bold;">⏱️ This OTP is valid for {{ $expiryMinutes }} minute only.</p>
        
        <p>Please enter this code on the verification page to complete your registration.</p>
        
        <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px;">
            <p style="margin: 0; font-size: 14px;"><strong>Security Notice:</strong></p>
            <ul style="margin: 10px 0 0 0; padding-left: 20px; font-size: 14px;">
                <li>Never share this OTP with anyone</li>
                <li>We will never ask for your OTP via phone or email</li>
                <li>If you didn't request this, please ignore this email</li>
            </ul>
        </div>
        
        <p style="margin-top: 30px;">Thanks,<br><strong>{{ config('app.name') }} Team</strong></p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        
        <p style="font-size: 12px; color: #999; text-align: center;">
            This is an automated email. Please do not reply to this message.
        </p>
    </div>
</body>
</html>
