<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Login - Medical Assistant</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #060d1f 0%, #0f1c3a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .login-container {
            background: rgba(10,22,40,0.95);
            border: 1px solid rgba(0,212,170,0.15);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            position: relative;
            backdrop-filter: blur(12px);
        }

        .login-header {
            background: linear-gradient(135deg, #0a1628 0%, #0f1c3a 100%);
            border-bottom: 1px solid rgba(0,212,170,0.1);
            color: #e8edf5;
            padding: 2rem;
            text-align: center;
        }

        .login-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 1.8rem;
        }

        .login-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.7;
            font-size: 0.9rem;
            color: rgba(232,237,231,0.7);
        }

        .login-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #e8edf5;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            border: 2px solid rgba(0,212,170,0.15);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(6,13,31,0.8);
            color: #e8edf5;
        }

        .form-control:focus {
            border-color: #00d4aa;
            box-shadow: 0 0 0 0.2rem rgba(0, 212, 170, 0.2);
        }

        .input-group {
            position: relative;
        }

        .input-group-text {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(232,237,231,0.4);
            z-index: 10;
        }

        .form-control.with-icon {
            padding-left: 3rem;
        }

        .btn-admin {
            background: linear-gradient(135deg, #00d4aa 0%, #00a88a 100%);
            border: none;
            color: #060d1f;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 10px;
            width: 100%;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 212, 170, 0.3);
        }

        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 212, 170, 0.4);
            background: linear-gradient(135deg, #00e8bb 0%, #00d4aa 100%);
            color: #060d1f;
        }

        .form-check {
            margin: 1rem 0;
        }

        .form-check-input:checked {
            background-color: #00d4aa;
            border-color: #00d4aa;
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(0, 212, 170, 0.2);
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .alert-danger {
            background: rgba(248,113,113,0.1);
            border-color: rgba(248,113,113,0.2);
            color: #f87171;
        }

        .form-check-label {
            color: rgba(232,237,231,0.7);
        }

        .back-to-site {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(0,212,170,0.1);
        }

        .back-to-site a {
            color: rgba(232,237,231,0.55);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .back-to-site a:hover {
            color: #00d4aa;
        }

        .admin-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: linear-gradient(135deg, #00d4aa 0%, #00a88a 100%);
            color: #060d1f;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 10px rgba(0, 212, 170, 0.3);
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="admin-badge">
            <i class="fas fa-shield-alt"></i>
        </div>
        
        <div class="login-header">
            <h2>Admin Panel</h2>
            <p>Medical Assistant Administration</p>
        </div>

        <div class="login-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-2"></i>Email Address
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input id="email" type="email" class="form-control with-icon @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                               placeholder="admin@medical.com">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-2"></i>Password
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input id="password" type="password" class="form-control with-icon @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="current-password"
                               placeholder="Enter your password">
                    </div>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn btn-admin">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Sign In to Admin Panel
                </button>
            </form>

            <div class="back-to-site">
                <a href="{{ url('/') }}">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Main Site
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>