<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <p>Hi {{ $name }},</p>
    <p>We received a request to reset your password. Click the button below to choose a new password:</p>
    <p>
        <a href="{{ $resetUrl }}"
           style="background: #f39c12; color: #fff; padding: 10px 18px; border-radius: 4px; text-decoration: none; display: inline-block;">
            Reset Password
        </a>
    </p>
    <p>If you did not request a password reset, you can ignore this email.</p>
    <p>Thanks,<br>{{ config('app.name') }}</p>
</body>
</html>
