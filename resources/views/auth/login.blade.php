<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin Login | Ghina Laundry</title>

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="images/favicon.png">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Main Style -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        /* ===== BACKGROUND LOGIN ===== */
        body {
            background: linear-gradient(135deg, #7a6ad8, #b6a9ff);
            font-family: 'Poppins', sans-serif;
        }

        /* ===== CARD LOGIN ===== */
        .authincation-content {
            border-radius: 18px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            background: #ffffff;
            padding: 35px 30px;
        }

        /* ===== LOGIN ICON ===== */
        .login-icon-wrapper {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #f1f3f5;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }

        .login-icon {
            font-size: 78px;
            color: #7a6ad8;
        }

        /* ===== LOGIN TEXT ===== */
        .login-role {
            font-weight: 600;
            color: #2f2f2f;
            margin-bottom: 2px;
        }

        .login-subtitle {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 10px;
        }

        /* ===== BRANDING ===== */
        .login-brand {
            margin-top: 6px;
            margin-bottom: 25px;
        }

        .login-brand h4 {
            font-weight: 700;
            color: #7a6ad8;
            letter-spacing: 0.6px;
            margin-bottom: 2px;
        }

        .login-brand span {
            font-size: 13px;
            color: #868e96;
        }

        /* ===== BUTTON ===== */
        .btn-primary {
            background-color: #7a6ad8;
            border-color: #7a6ad8;
            border-radius: 10px;
            padding: 10px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #695cd4;
            border-color: #695cd4;
        }

        /* ===== INPUT ===== */
        .form-control {
            border-radius: 10px;
            padding: 10px 14px;
        }

        /* ===== LINK ===== */
        a {
            color: #7a6ad8;
        }

        a:hover {
            color: #695cd4;
        }
    </style>
</head>

<body class="vh-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6 col-lg-5">
                    <div class="authincation-content">

                        <!-- LOGIN HEADER -->
                        <div class="text-center">
                            <div class="login-icon-wrapper">
                                <i class="bi bi-person-circle login-icon"></i>
                            </div>

                            <h3 class="login-role">Admin</h3>
                            <p class="login-subtitle">Sign in to your account</p>

                            <div class="login-brand">
                                <h4>Ghina Laundry</h4>
                                <span>Sistem Manajemen Laundry</span>
                            </div>
                        </div>

                        <!-- LOGIN FORM saat mengetik tombol login, maka data akan terkirim -->
                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="mb-1"><strong>Email</strong></label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <p class="mt-2 text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="mb-1"><strong>Password</strong></label>
                                <input type="password" class="form-control" name="password">
                                @error('password')
                                    <p class="mt-2 text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <a href="{{ route('password.request') }}">Forgot Password?</a>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-100">
                                    Sign Me In
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="vendor/global/global.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/dlabnav-init.js"></script>
    {{-- <script src="js/styleSwitcher.js"></script> --}}
</body>

</html>
