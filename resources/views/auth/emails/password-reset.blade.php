<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
</head>

<body>
    <h1>Reset Password Request</h1>
    <p>Anda telah meminta pengaturan ulang kata sandi. Silakan klik tautan di bawah ini untuk mengatur ulang kata sandi Anda:</p>
    <a href="{{ route('password.reset', $token) }}">Reset Password</a>
</body>

</html>