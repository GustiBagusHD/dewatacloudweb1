<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DewataCloud</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a href="./" class="navbar-brand">
                <img src="assets/images/logos/logo.png" alt="DewataCloud" height="40">
            </a>
            <div class="ms-auto">
                <a href="billing-login" class="btn btn-brown me-2">Login</a>
                <a href="billing-register" class="text-register">Register</a>
            </div>
        </div>
    </nav>

    <div class="register-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="maintenance-card text-center">
                        <div class="logo-container mb-4">
                            <img src="assets/images/logos/logo.png" alt="DewataCloud" class="maintenance-logo" onerror="this.onerror=null; this.src='assets/images/logo.png';">
                        </div>
                        <div class="maintenance-icon mb-4">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h2 class="mb-3">Oops! Billing Under Maintenance</h2>
                        <p class="text-muted mb-4">We're currently improving our billing system to serve you better. Please be patient, we'll be back soon!</p>
                        <div class="estimated-time mb-4">
                            <span class="badge bg-warning text-dark">Estimated completion: 24 hours</span>
                        </div>
                        <a href="./" class="btn btn-brown">Back to Homepage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f8f9fa;
    }
    
    .navbar {
        padding: 1rem 0;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .register-section {
        padding: 4rem 0;
    }
    
    .logo-container {
        margin-bottom: 2rem;
    }
    
    .maintenance-logo {
        height: 60px;
        width: auto;
    }
    
    .maintenance-card {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    @media (min-width: 768px) {
        .maintenance-card {
            padding: 3rem;
        }
    }
    
    @media (max-width: 767px) {
        .maintenance-logo {
            height: 50px;
        }
        
        .maintenance-icon {
            font-size: 2.5rem;
        }
        
        .maintenance-card h2 {
            font-size: 1.5rem;
        }
        
        .badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }
        
        .btn-brown {
            padding: 0.6rem 1.2rem;
            font-size: 0.95rem;
        }
        
        .navbar {
            padding: 0.75rem 0;
        }
        
        .navbar-brand img {
            height: 35px;
        }
    }
    
    .maintenance-icon {
        font-size: 3rem;
        color: #8B4513;
        animation: wrench 2.5s ease infinite;
    }
    
    .maintenance-card h2 {
        color: #2D3748;
        font-weight: 600;
    }
    
    .badge {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
    }
    
    @keyframes wrench {
        0% { transform: rotate(0deg); }
        20% { transform: rotate(30deg); }
        40% { transform: rotate(-30deg); }
        60% { transform: rotate(30deg); }
        80% { transform: rotate(-30deg); }
        100% { transform: rotate(0deg); }
    }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 