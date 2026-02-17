<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link rel="shortcut icon" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>Forgot Password - Gizmo Store</title>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto;
        }
        .login-container { width: 100%; max-width: 400px; }
        .login-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            padding: 40px;
        }
        .login-header { text-align: center; margin-bottom: 30px; }
        .login-header h1 { color: #1a1a1a; font-size: 2rem; font-weight: bold; margin-bottom: 10px; }
        .login-header .icon { color: #DC143C; font-size: 3rem; }
        .login-header p { color: #666; font-size: 0.95rem; }
        .form-group { margin-bottom: 20px; }
        .form-label { color: #1a1a1a; font-weight: 600; margin-bottom: 8px; }
        .form-control {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 12px 15px;
            font-size: 0.95rem;
        }
        .form-control:focus {
            border-color: #DC143C;
            box-shadow: 0 0 0 0.2rem rgba(220, 20, 60, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 5px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(220, 20, 60, 0.4);
            color: white;
        }
        .form-footer { text-align: center; margin-top: 25px; padding-top: 20px; border-top: 1px solid #eee; }
        .form-footer p { color: #666; font-size: 0.9rem; margin-bottom: 0; }
        .form-footer a { color: #DC143C; text-decoration: none; font-weight: 600; }
        .form-footer a:hover { text-decoration: underline; }
        .alert { border-radius: 5px; border: none; margin-bottom: 20px; }
        .error-message { color: #dc3545; font-size: 0.85rem; margin-top: 5px; }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: white; text-decoration: none; font-weight: 500; }
        .back-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-power-off icon"></i>
                <h1>GIZMO</h1>
                <p>Reset your password</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <p class="text-muted small mb-3">Enter your email address and we'll send you a link to reset your password.</p>

            <form action="{{ route('password.email') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com" autofocus>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-login">Send Password Reset Link</button>
            </form>

            <div class="form-footer">
                <p><a href="{{ route('login') }}"><i class="fas fa-arrow-left"></i> Back to Sign In</a></p>
            </div>
        </div>

        <div class="back-link">
            <a href="/"><i class="fas fa-arrow-left"></i> Back to Store</a>
        </div>
    </div>
</body>
</html>
