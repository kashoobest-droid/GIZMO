<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Forbidden</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
            color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .error-container {
            text-align: center;
            max-width: 500px;
            padding: 40px;
            background: rgba(36, 36, 36, 0.8);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(220, 20, 60, 0.3);
        }
        .error-code {
            font-size: 4rem;
            font-weight: 700;
            color: #DC143C;
            margin-bottom: 1rem;
        }
        .error-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #ffffff;
        }
        .error-message {
            color: #b0b0b0;
            font-size: 1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .btn-home {
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
            color: white;
            padding: 12px 30px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
            display: inline-block;
        }
        .btn-home:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 20, 60, 0.3);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">403</div>
        <div class="error-title">Access Forbidden</div>
        <div class="error-message">
            You don't have permission to access this resource. If you believe this is an error, please contact our support team.
        </div>
        <a href="{{ url('/') }}" class="btn-home">
            <i class="fas fa-home me-2"></i> Go to Home
        </a>
    </div>
</body>
</html>
