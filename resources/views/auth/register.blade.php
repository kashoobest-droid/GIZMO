<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link rel="shortcut icon" href="https://res.cloudinary.com/dgrnbtgts/image/upload/v1771338287/gizmo_qsab1d.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <title>{{ __('messages.nav_sign_up') }} - Gizmo Store</title>
    <link rel="stylesheet" href="/css/gizmo_sudan.css">
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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto;
            padding: 20px;
        }

        .register-container {
            width: 100%;
            max-width: 500px;
        }

        .register-card {
            background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            padding: 50px 40px;
            border: 1px solid rgba(220, 20, 60, 0.2);
        }

        .register-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .register-header .icon {
            color: #DC143C;
            font-size: 3.5rem;
            margin-bottom: 15px;
            display: inline-block;
        }

        .register-header h1 {
            color: #ffffff;
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .register-header p {
            color: #b0b0b0;
            font-size: 1rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            color: #e8e8e8;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 0.95rem;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 14px 18px;
            font-size: 0.95rem;
            color: #e8e8e8;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control::placeholder {
            color: #808080;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #DC143C;
            box-shadow: 0 0 0 3px rgba(220, 20, 60, 0.2);
            outline: none;
            color: #e8e8e8;
        }

        .form-control.is-invalid {
            border-color: #ff6b6b;
        }

        .phone-group {
            display: grid;
            grid-template-columns: 100px 1fr;
            gap: 12px;
            align-items: flex-start;
        }

        .country-selector {
            grid-column: 1;
        }

        .phone-input {
            grid-column: 2;
        }

        .btn-register {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(220, 20, 60, 0.3);
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(220, 20, 60, 0.5);
            background: linear-gradient(135deg, #ff1744 0%, #c41c3b 100%);
        }

        .btn-register:active {
            transform: translateY(-1px);
        }

        .form-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-footer p {
            color: #b0b0b0;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .form-footer a {
            color: #DC143C;
            text-decoration: none;
            font-weight: 700;
            transition: color 0.3s;
        }

        .form-footer a:hover {
            color: #ff1744;
            text-decoration: underline;
        }

        .alert {
            border-radius: 8px;
            border: 1px solid rgba(255, 59, 48, 0.3);
            background: rgba(220, 20, 60, 0.1);
            margin-bottom: 25px;
            padding: 15px 20px;
            color: #ff6b6b;
            font-size: 0.9rem;
        }

        .alert strong {
            color: #ff1744;
        }

        .alert ul {
            margin-top: 10px;
            margin-bottom: 0;
            padding-left: 20px;
        }

        .alert li {
            margin-bottom: 5px;
        }

        .error-message {
            color: #ff6b6b;
            font-size: 0.8rem;
            margin-top: 8px;
            display: block;
        }

        .back-link {
            text-align: center;
            margin-top: 30px;
        }

        .back-link a {
            color: #DC143C;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-link a:hover {
            color: #ff1744;
            text-decoration: underline;
        }

        .form-text {
            color: #909090;
            font-size: 0.8rem;
            margin-top: 8px;
            display: block;
        }

        @media (max-width: 768px) {
            .register-card {
                padding: 40px 30px;
            }

            .register-header h1 {
                font-size: 1.8rem;
            }

            .phone-group {
                grid-template-columns: 80px 1fr;
            }
        }

        @media (max-width: 576px) {
            .register-card {
                padding: 35px 25px;
            }

            .register-header h1 {
                font-size: 1.6rem;
            }

            .phone-group {
                grid-template-columns: 70px 1fr;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <i class="fas fa-power-off icon"></i>
                <h1>GIZMO</h1>
                <p>{{ __('messages.auth_create_account') }}</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>{{ __('messages.auth_errors_title') }}</strong> {{ __('messages.auth_errors_msg') }}
                    <ul style="margin-top: 10px; margin-bottom: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم الكامل:' : 'Full name :' }}</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="{{ app()->getLocale() === 'ar' ? 'محمد على عبدالله' : 'Mohamed Ali Abdullah' }}">
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">{{ __('messages.auth_email') }}</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone_number" class="form-label">{{ __('messages.auth_phone_number') }}</label>
                    <div class="phone-group">
                        <select id="country_code" class="form-control country-selector" name="country_code" aria-label="Country">
                            <option value="+249" title="Sudan" {{ old('country_code') == '+249' ? 'selected' : '' }}>🇸🇩 +249</option>
                            <option value="+20" title="Egypt" {{ old('country_code') == '+20' ? 'selected' : '' }}>🇪🇬 +20</option>
                        </select>
                        <input type="tel" inputmode="numeric" pattern="[0-9]*" id="phone_number" class="form-control phone-input @error('phone') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" required placeholder="701234567">
                    </div>
                    @error('phone')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <input type="hidden" name="phone" id="phone" value="{{ old('phone') }}">

                <div class="form-group">
                    <label for="password" class="form-label">{{ __('messages.auth_password') }}</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Min 8 chars, letters, numbers, mixed case">
                    <small class="text-muted" style="font-size:0.8rem;">{{ __('messages.auth_password_requirements') }}</small>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">{{ __('messages.auth_confirm_password') }}</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Confirm your password">
                </div>

                <button type="submit" id="registerBtn" class="btn btn-register">{{ __('messages.auth_create_button') }}</button>
            </form>

            <div class="form-footer">
                <p>{{ __('messages.auth_have_account') }} <a href="{{ route('login') }}">{{ __('messages.auth_sign_in_link') }}</a></p>
            </div>
        </div>

        <div class="back-link">
            <a href="/"><i class="fas fa-arrow-left"></i> {{ __('messages.auth_back_to_store') }}</a>
        </div>
    </div>
    <script>
        (function () {
            const form = document.querySelector('form[action="{{ route('register.post') }}"]');
            if (!form) return;

            const phoneInput = document.getElementById('phone_number');
            if (phoneInput) {
                phoneInput.addEventListener('input', function (e) {
                    // allow only digits
                    const digits = this.value.replace(/\D+/g, '');
                    if (this.value !== digits) this.value = digits;
                });
            }

            form.addEventListener('submit', function (e) {
                const code = document.getElementById('country_code')?.value || '';
                const number = (document.getElementById('phone_number')?.value || '').replace(/\D+/g, '');
                const full = code + number;
                document.getElementById('phone').value = full;
            });
        })();
    </script>
</body>
</html>
