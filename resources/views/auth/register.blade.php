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
    <title>Create Account - Gizmo Store</title>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto;
            padding: 20px 0;
        }

        .register-container {
            width: 100%;
            max-width: 400px;
        }

        .register-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            padding: 40px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .register-header h1 {
            color: #1a1a1a;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .register-header .icon {
            color: #DC143C;
            font-size: 3rem;
        }

        .register-header p {
            color: #666;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            color: #1a1a1a;
            font-weight: 600;
            margin-bottom: 8px;
        }

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

        .btn-register {
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

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(220, 20, 60, 0.4);
            color: white;
        }

        .form-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .form-footer p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .form-footer a {
            color: #DC143C;
            text-decoration: none;
            font-weight: 600;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 5px;
            border: none;
            margin-bottom: 20px;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <i class="fas fa-power-off icon"></i>
                <h1>GIZMO</h1>
                <p>Create your account</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Oops!</strong> Please check the errors below:
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
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="John Doe">
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Country</label>
                    <select id="country_code" class="form-control" style="max-width:160px;" name="country_code" aria-label="Country">
                        <option value="+249" title="Sudan" {{ old('country_code') == '+249' ? 'selected' : '' }}>🇸🇩 +249</option>
                        <option value="+20" title="Egypt" {{ old('country_code') == '+20' ? 'selected' : '' }}>🇪🇬 +20</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <div class="d-flex gap-2">
                        <input type="tel" inputmode="numeric" pattern="[0-9]*" id="phone_number" class="form-control @error('phone') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" required placeholder="701234567">
                    </div>
                    @error('phone')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <input type="hidden" name="phone" id="phone" value="{{ old('phone') }}">

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Min 8 chars, letters, numbers, mixed case">
                    <small class="text-muted" style="font-size:0.8rem;">At least 8 characters, with letters (upper & lower) and numbers</small>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Confirm your password">
                </div>

                <button type="submit" id="registerBtn" class="btn btn-register">Create Account</button>
            </form>

            <div class="form-footer">
                <p>Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
            </div>
        </div>

        <div class="back-link">
            <a href="/"><i class="fas fa-arrow-left"></i> Back to Store</a>
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
