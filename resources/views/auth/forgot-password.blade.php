<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link rel="shortcut icon" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>{{ app()->getLocale() === 'ar' ? 'نسيت كلمة المرور - Gizmo Store' : 'Forgot Password - Gizmo Store' }}</title>
   <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            padding: 20px;
            color: #ffffff;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
        }

        .auth-card {
            background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            padding: 40px;
            border: 1px solid rgba(220, 20, 60, 0.2);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-header .icon {
            color: #DC143C;
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .auth-header h1 {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .auth-header p {
            color: #b0b0b0;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            color: #e8e8e8;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.85rem;
            display: block;
            text-transform: uppercase;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 12px 15px;
            color: #ffffff !important;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
            opacity: 1; /* ensure consistent across browsers */
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #DC143C;
            box-shadow: 0 0 0 3px rgba(220, 20, 60, 0.2);
            outline: none;
        }

        .btn-auth {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            border: none;
            color: white;
            padding: 14px;
            border-radius: 8px;
            font-weight: 700;
            width: 100%;
            margin-top: 10px;
            transition: 0.3s;
            text-transform: uppercase;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 20, 60, 0.4);
            color: white;
            background: linear-gradient(135deg, #ff1744 0%, #c41c3b 100%);
        }

        .form-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-footer a, .back-link a {
            color: #DC143C;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .error-message {
            color: #ff6b6b;
            font-size: 0.8rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <i class="fas fa-power-off icon"></i>
                <h1>GIZMO</h1>
                <p>{{ app()->getLocale() === 'ar' ? 'إعادة تعيين كلمة المرور' : 'Reset your password' }}</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-2" style="background: rgba(220, 20, 60, 0.1); border: 1px solid #DC143C; color: #ff6b6b;">
                    <ul class="mb-0 list-unstyled">
                        @foreach ($errors->all() as $error)
                            <li><small>{{ $error }}</small></li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <p class="small mb-3" style="color: {{ app()->getLocale() === 'ar' ? '#ffffff' : '#6c757d' }};">{{ app()->getLocale() === 'ar' ? 'أدخل بريدك الإلكتروني وسنرسل لك رابطًا لإعادة تعيين كلمة المرور.' : "Enter your email address and we'll send you a link to reset your password." }}</p>

            <form action="{{ route('password.email') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني:' : 'Email Address' }}</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="{{ app()->getLocale() === 'ar' ? 'example@domain.com' : 'you@example.com' }}" autofocus>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-auth">{{ app()->getLocale() === 'ar' ? 'إرسال رابط إعادة التعيين' : 'Send Password Reset Link' }}</button>
            </form>

            <div class="form-footer">
                <p><a href="{{ route('login') }}"><i class="fas fa-arrow-left"></i> {{ app()->getLocale() === 'ar' ? 'العودة لتسجيل الدخول' : 'Back to Sign In' }}</a></p>
            </div>
        </div>

        <div class="back-link">
            <a href="/"><i class="fas fa-arrow-left me-1"></i> {{ app()->getLocale() === 'ar' ? 'العودة إلى المتجر' : 'Back to Store' }}</a>
        </div>
    </div>
</body>
</html>
