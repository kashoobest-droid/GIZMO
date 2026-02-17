<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>FAQ - Gizmo Store</title>
    <style>
        body { 
            background: #f5f5f5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .navbar-custom { 
            background: #1a1a1a;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar-custom .navbar-brand { 
            color: #DC143C !important;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .navbar-custom .nav-link { 
            color: #ffffff !important;
            margin: 0 10px;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-custom .nav-link:hover {
            color: #DC143C !important;
        }

        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .accordion-button {
            background: white;
            color: #1a1a1a;
            font-weight: 600;
            border: 1px solid #ddd;
        }

        .accordion-button:not(.collapsed) {
            background: #DC143C;
            color: white;
            border-color: #DC143C;
        }

        .accordion-button:focus {
            border-color: #DC143C;
            box-shadow: 0 0 0 0.25rem rgba(220, 20, 60, 0.25);
        }

        .accordion-item {
            margin-bottom: 12px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .accordion-body {
            color: #666;
            line-height: 1.6;
            padding: 20px;
        }

        h2 {
            color: #1a1a1a;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .page-header {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header i {
            color: #DC143C;
            font-size: 1.8rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="/"><i class="fas fa-power-off"></i> Gizmo Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="/"><i class="fas fa-home"></i> Home</a>
                    <a class="nav-link" href="{{ route('contact') }}"><i class="fas fa-envelope"></i> Contact</a>
                    <a class="nav-link" href="{{ route('order.track.show') }}"><i class="fas fa-map-location-dot"></i> Track Order</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1>Frequently Asked Questions</h1>
            <p>Find answers to common questions about our products and services</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container my-5">
        <h2 class="page-header">
            <i class="fas fa-question-circle"></i>
            <span>FAQ</span>
        </h2>
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        <i class="fas fa-shopping-cart me-2" style="color: #DC143C;"></i>How do I place an order?
                    </button>
                </h3>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">Add items to your cart, then go to Cart and click Checkout. Enter any notes and place your order. We will confirm by email.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        <i class="fas fa-credit-card me-2" style="color: #DC143C;"></i>What payment methods do you accept?
                    </button>
                </h3>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">We currently accept Cash on Delivery (COD). You pay when your order is delivered.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        <i class="fas fa-tracking me-2" style="color: #DC143C;"></i>How can I track my order?
                    </button>
                </h3>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">Go to Track Order, enter your order ID and the email you used. You will see the current status.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                        <i class="fas fa-undo me-2" style="color: #DC143C;"></i>What is your return policy?
                    </button>
                </h3>
                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">Contact us with your order details. We will guide you through the process.</div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
