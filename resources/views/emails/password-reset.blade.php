<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $userType }} Password Reset</title>
</head>
<body>
<h2>Hello {{ $userType }},</h2>
<p>You requested a password reset. Click the button below to reset your password:</p>

<p>
    <a href="{{ $resetUrl }}"
       style="display:inline-block;padding:10px 20px;background:#2563eb;color:#fff;
                  text-decoration:none;border-radius:5px;">
        Reset Password
    </a>
</p>

<p>If you did not request this, please ignore this email.</p>
<p>Thanks,<br>{{ config('app.name') }}</p>
</body>
</html>
