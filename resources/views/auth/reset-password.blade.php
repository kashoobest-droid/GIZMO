<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link rel="shortcut icon" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Reset Password - GIZMO Store</title>
    <style>
        :root {
            --bg-dark: #121212;
            --card-dark: #1e1e1e;
            --gizmo-red: #DC143C;
            --gizmo-red-hover: #8B0000;
            --text-primary: #ffffff;
            --text-secondary: #b0b0b0;
            --border-color: #333333;
        }

        body {
            /* Using the store's dark vibe instead of the purple gradient */
            background-color: var(--bg-dark);
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(220, 20, 60, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(220, 20, 60, 0.05) 0%, transparent 40%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', -apple-system, system-ui, sans-serif;
            padding: 20px;
            color: var(--text-primary);
        }

        .login-container { width: 100%; max-width: 420px; }

        .login-card {
            background: var(--card-dark);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            padding: 40px;
            transition: transform 0.3s ease;
        }

        .login-header { text-align: center; margin-bottom: 35px; }
        
        /* Matching the Navbar brand style */
        .login-header h1 { 
            color: var(--gizmo-red); 
            font-size: 1.8rem; 
            font-weight: 800; 
            margin-top: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .login-header .icon { 
            background: rgba(220, 20, 60, 0.1);
            color: var(--gizmo-red); 
            font-size: 2rem;
            width: 70px;
            height: 70px;
            line-height: 70px;
            border-radius: 50%;
            display: inline-block;
        }

        .login-header p { color: var(--text-secondary); font-size: 0.95rem; margin-top: 10px; }

        .form-label { 
            color: var(--text-primary); 
            font-weight: 600; 
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px; 
        }

        .form-control {
            background-color: #252525;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 0.95rem;
            color: #ffffff;
            transition: all 0.2s;
        }

        .form-control:focus {
            background-color: #2a2a2a;
            border-color: var(--gizmo-red);
            box-shadow: 0 0 0 0.25rem rgba(220, 20, 60, 0.15);
            color: #ffffff;
        }

        /* Readonly styling for email */
        .form-control[readonly] {
            background-color: #1a1a1a;
            border-color: #222;
            color: var(--text-secondary);
            cursor: not-allowed;
        }

        .btn-login {
            background: var(--gizmo-red);
            border: none;
            color: white;
            padding: 14px;
            border-radius: 10px;
            font-weight: 700;
            width: 100%;
            margin-top: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background: var(--gizmo-red-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 20, 60, 0.3);
            color: white;
        }

        .form-footer { 
            text-align: center; 
            margin-top: 30px; 
            padding-top: 20px; 
            border-top: 1px solid var(--border-color); 
        }

        .form-footer a { 
            color: var(--gizmo-red); 
            text-decoration: none; 
            font-weight: 600; 
            font-size: 0.9rem;
        }

        .form-footer a:hover { color: #ff4d4d; }

        .password-hint { color: var(--text-secondary); font-size: 0.8rem; margin-top: 6px; line-height: 1.4; }
        
        .alert-danger {
            background-color: rgba(220, 20, 60, 0.1);
            border: 1px solid rgba(220, 20, 60, 0.2);
            color: #ff6b6b;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="icon">
                    <i class="fas fa-key"></i>
                </div>
                <h1>GIZMO STORE</h1>
                <p>Secure your account with a new password</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="mb-4">
                    <label for="email" class="form-label">Active Account</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $email) }}" required readonly>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="••••••••">
                    <div class="password-hint">
                        <i class="fas fa-info-circle me-1"></i> Use 8+ characters with a mix of letters and numbers.
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-login">
                    Update Password <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </form>

            <div class="form-footer">
                <a href="{{ route('login') }}">
                    <i class="fas fa-chevron-left me-1 small"></i> Back to Sign In
                </a>
            </div>
        </div>
    </div>
</body>
</html>