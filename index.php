<?php
require_once 'includes/config.php';
require_once 'includes/languages.php';

// Handle language change
if (isset($_GET['lang'])) {
    set_language($_GET['lang']);
    // Redirect back to remove lang parameter
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit();
}
?>
<!DOCTYPE html>
<html lang="<?php echo get_current_language(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DewataCloud - <?php echo __('hero_title'); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="assets/images/logos/logo.png" alt="DewataCloud" height="40">
                DewataCloud
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#home"><?php echo __('nav_home'); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="#games"><?php echo __('nav_games'); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="#locations"><?php echo __('nav_locations'); ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="#reviews"><?php echo __('nav_reviews'); ?></a></li>
                    <?php if (is_logged_in()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                                <img src="https://www.gravatar.com/avatar/<?php echo md5($user['email']); ?>?d=mp" alt="Profile" class="avatar" style="width: 32px; height: 32px; border-radius: 50%;">
                                <span><?php echo htmlspecialchars($user['name']); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="dashboard.php"><i class='bx bxs-dashboard'></i> Dashboard</a></li>
                                <li><a class="dropdown-item" href="profile.php"><i class='bx bxs-user'></i> Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class='bx bxs-log-out'></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link btn btn-primary login-btn" href="billing-login.php"><?php echo __('nav_login'); ?></a></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-primary register-btn" href="billing-register.php"><?php echo __('nav_register'); ?></a></li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <?php include 'includes/components/language-switcher.php'; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1><?php echo __('hero_title'); ?></h1>
                    <p><?php echo __('hero_subtitle'); ?></p>
                    <?php if (is_logged_in()): ?>
                        <a href="create-server.php" class="btn btn-primary btn-lg"><?php echo __('hero_cta'); ?></a>
                    <?php else: ?>
                        <button class="btn btn-primary btn-lg show-login-prompt"><?php echo __('hero_cta'); ?></button>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <img src="assets/images/mascots/mascot.png" alt="Mascot" class="hero-image">
                </div>
            </div>
        </div>
    </header>

    <section id="games" class="game-section">
        <div class="container">
            <h2><?php echo __('games_title'); ?></h2>
            <div class="row">
                <?php foreach (GAME_CONFIGS as $game_type => $config): ?>
                <div class="col-md-4">
                    <div class="game-card">
                        <img src="assets/images/games/<?php echo $config['icon']; ?>" alt="<?php echo $config['name']; ?>">
                        <h3><?php echo $config['name']; ?></h3>
                        <p><?php echo $config['description']; ?></p>
                        <?php if (is_logged_in()): ?>
                            <a href="create-server.php?game=<?php echo $game_type; ?>" class="btn btn-primary"><?php echo __('view_plans'); ?></a>
                        <?php else: ?>
                            <button class="btn btn-primary show-login-prompt"><?php echo __('view_plans'); ?></button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Login Prompt Overlay -->
    <div class="login-prompt-overlay" style="display: none;">
        <div class="login-prompt-card">
            <div class="text-center mb-4">
                <img src="assets/images/mascots/mascot.png" alt="Mascot" class="prompt-mascot">
                <h3>Oops! Please Login First</h3>
                <p>You need to login to access our premium features:</p>
                <ul class="text-start feature-list">
                    <li><i class='bx bx-check'></i> Create game servers instantly</li>
                    <li><i class='bx bx-check'></i> Access server control panel</li>
                    <li><i class='bx bx-check'></i> Manage multiple servers</li>
                    <li><i class='bx bx-check'></i> 24/7 premium support</li>
                </ul>
            </div>
            <div class="d-grid gap-2">
                <a href="billing-login.php" class="btn btn-primary"><i class='bx bx-log-in'></i> Login</a>
                <a href="billing-register.php" class="btn btn-outline-primary"><i class='bx bx-user-plus'></i> Create Account</a>
                <button class="btn btn-link text-muted close-prompt">Maybe Later</button>
            </div>
        </div>
    </div>

    <style>
    .login-prompt-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(8px);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .login-prompt-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    .login-prompt-card {
        background: white;
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        max-width: 400px;
        width: 90%;
        transform: translateY(30px);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .login-prompt-overlay.show .login-prompt-card {
        transform: translateY(0);
        opacity: 1;
    }

    .prompt-mascot {
        width: 120px;
        margin-bottom: 1.5rem;
        animation: float 6s ease-in-out infinite;
        filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.1));
    }

    .login-prompt-card h3 {
        color: var(--dark-color);
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 1.5rem;
    }

    .login-prompt-card p {
        color: #4A5568;
        margin-bottom: 1rem;
    }

    .feature-list {
        list-style: none;
        padding: 0;
        margin-bottom: 2rem;
    }

    .feature-list li {
        margin-bottom: 0.75rem;
        color: #4A5568;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .feature-list i {
        color: var(--primary-color);
        font-size: 1.25rem;
    }

    .login-prompt-card .btn {
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .close-prompt {
        font-size: 0.875rem;
    }

    .close-prompt:hover {
        text-decoration: underline;
    }

    @keyframes float {
        0% { transform: translateY(0px) rotate(0deg); }
        25% { transform: translateY(-10px) rotate(-2deg); }
        50% { transform: translateY(0px) rotate(0deg); }
        75% { transform: translateY(-5px) rotate(2deg); }
        100% { transform: translateY(0px) rotate(0deg); }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginPromptButtons = document.querySelectorAll('.show-login-prompt');
        const loginPromptOverlay = document.querySelector('.login-prompt-overlay');
        const closePromptButton = document.querySelector('.close-prompt');

        function showLoginPrompt() {
            loginPromptOverlay.style.display = 'flex';
            // Trigger reflow
            loginPromptOverlay.offsetHeight;
            loginPromptOverlay.classList.add('show');
        }

        function hideLoginPrompt() {
            loginPromptOverlay.classList.remove('show');
            setTimeout(() => {
                loginPromptOverlay.style.display = 'none';
            }, 300);
        }

        loginPromptButtons.forEach(button => {
            button.addEventListener('click', showLoginPrompt);
        });

        loginPromptOverlay.addEventListener('click', function(e) {
            if (e.target === loginPromptOverlay) {
                hideLoginPrompt();
            }
        });

        closePromptButton.addEventListener('click', hideLoginPrompt);

        // Escape key to close
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && loginPromptOverlay.classList.contains('show')) {
                hideLoginPrompt();
            }
        });
    });
    </script>

    <section id="features" class="features-section">
        <div class="container">
            <h2 class="text-center mb-5">Mengapa Memilih DewataCloud?</h2>
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bx bx-rocket"></i>
                        </div>
                        <h3>Setup Instan</h3>
                        <p>Server Anda akan siap dalam waktu kurang dari 60 detik</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bx bx-shield-quarter"></i>
                        </div>
                        <h3>Proteksi DDoS</h3>
                        <p>Perlindungan kelas enterprise gratis</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bx bx-support"></i>
                        </div>
                        <h3>Dukungan 24/7</h3>
                        <p>Tim kami selalu siap membantu Anda</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
    .features-section {
        padding: 6rem 0;
        background: var(--light-color);
    }

    .features-section h2 {
        color: var(--dark-color);
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 3rem;
    }

    .feature-card {
        background: white;
        padding: 2.5rem;
        border-radius: 20px;
        text-align: center;
        transition: all 0.3s ease;
        height: 100%;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(var(--primary-rgb), 0.1);
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        background: var(--light-color);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        transition: all 0.3s ease;
    }

    .feature-card:hover .feature-icon {
        background: var(--primary-color);
        transform: scale(1.1) rotate(10deg);
    }

    .feature-icon i {
        font-size: 2.5rem;
        color: var(--primary-color);
        transition: all 0.3s ease;
    }

    .feature-card:hover .feature-icon i {
        color: white;
    }

    .feature-card h3 {
        color: var(--dark-color);
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .feature-card p {
        color: #4A5568;
        margin: 0;
        font-size: 1rem;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .feature-card {
            margin-bottom: 2rem;
        }

        .features-section h2 {
            font-size: 2rem;
        }
    }
    </style>

    <section id="locations" class="location-section">
        <div class="container">
            <h2><?php echo __('locations_title'); ?></h2>
            <div class="row">
                <?php foreach (LOCATIONS as $code => $location): ?>
                <div class="col-md-6">
                    <div class="location-card">
                        <img src="assets/images/locations/<?php echo $code; ?>.png" alt="<?php echo $location['name']; ?>">
                        <h3><?php echo $location['name']; ?></h3>
                        <p><?php echo __('datacenter'); ?>: <?php echo $location['datacenter']; ?></p>
                        <p><?php echo __('ping'); ?>: <?php echo $location['ping']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="reviews" class="reviews-section">
        <div class="container">
            <h2 class="text-center mb-5">Apa Kata Mereka?</h2>
            <div class="reviews-grid">
                <div class="review-card">
                    <div class="review-profile">
                        <div class="review-avatar">
                            <img src="assets/images/avatars/agus.png" alt="Agus">
                        </div>
                        <div class="review-info">
                            <h4>Agus</h4>
                            <p class="review-game">SAMP Roleplay Server</p>
                        </div>
                        <div class="review-rating">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                        </div>
                    </div>
                    <p class="review-text">"Gokil sih server SAMP RP gw! 6 bulan nonstop jalan, player base 200+ daily active. System RP + DM mode works perfect, mapping custom gw load smooth. Anti-cheat nya auto banned cheater. EZ gaming! 😎"</p>
                </div>

                <div class="review-card">
                    <div class="review-profile">
                        <div class="review-avatar">
                            <img src="assets/images/avatars/keisya.png" alt="Keisya">
                        </div>
                        <div class="review-info">
                            <h4>Keisya</h4>
                            <p class="review-game">Minecraft SkyBlock Server</p>
                        </div>
                        <div class="review-rating">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star-half'></i>
                        </div>
                    </div>
                    <p class="review-text">"Suka banget sama Minecraft SkyBlock server nya! Design island nya cantik-cantik, auto farm nya membantu banget. Komunitas nya friendly, apalagi banyak event seru tiap minggu. Thank you DewataCloud! 🌸"</p>
                </div>

                <div class="review-card">
                    <div class="review-profile">
                        <div class="review-avatar">
                            <img src="assets/images/avatars/juli.png" alt="Juli">
                        </div>
                        <div class="review-info">
                            <h4>Juli</h4>
                            <p class="review-game">FiveM RP Server</p>
                        </div>
                        <div class="review-rating">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                        </div>
                    </div>
                    <p class="review-text">"FiveM RP server nya next level! Custom script economy + jobs auto works. Map custom gw install langsung jalan, no restart needed. 100+ player tiap malem auto full house. GG server! 🔥"</p>
                </div>

                <div class="review-card">
                    <div class="review-profile">
                        <div class="review-avatar">
                            <img src="assets/images/avatars/amira.png" alt="Amira">
                        </div>
                        <div class="review-info">
                            <h4>Amira</h4>
                            <p class="review-game">Minecraft Survival Server</p>
                        </div>
                        <div class="review-rating">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                        </div>
                    </div>
                    <p class="review-text">"Finally nemu Minecraft Survival server yang cozy banget! Server nya super smooth, aman buat main sama temen-temen. Bisa bikin rumah dan dekor sesuka hati. Pokoknya the best deh! ✨"</p>
                </div>

                <div class="review-card">
                    <div class="review-profile">
                        <div class="review-avatar">
                            <img src="assets/images/avatars/rosi.png" alt="Rosi">
                        </div>
                        <div class="review-info">
                            <h4>Rosi</h4>
                            <p class="review-game">SAMP Drift Server</p>
                        </div>
                        <div class="review-rating">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                        </div>
                    </div>
                    <p class="review-text">"Server SAMP Drift nya auto perfect! Physics nya smooth banget, handling mod + custom map langsung gas. Full house 100 slot tiap malem, ping dibawah 50ms. Built different! 🏁"</p>
                </div>
            </div>
        </div>
    </section>

    <style>
    .reviews-section {
        padding: 6rem 0;
        background: linear-gradient(135deg, var(--light-color) 0%, #fff 100%);
    }

    .reviews-section h2 {
        color: var(--dark-color);
        font-size: 2.5rem;
        font-weight: 700;
    }

    .reviews-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .review-card {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .review-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(var(--primary-rgb), 0.1);
    }

    .review-profile {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .review-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 1rem;
        border: 3px solid var(--primary-color);
    }

    .review-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .review-info {
        flex: 1;
    }

    .review-info h4 {
        color: var(--dark-color);
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
    }

    .review-game {
        color: var(--primary-color);
        font-size: 0.9rem;
        margin: 0.25rem 0 0;
    }

    .review-rating {
        color: #ffc107;
        font-size: 1.2rem;
        margin-top: 0.5rem;
    }

    .review-rating i {
        margin-right: 2px;
    }

    .review-text {
        color: #4A5568;
        font-size: 0.95rem;
        line-height: 1.6;
        margin: 0;
    }

    @media (max-width: 768px) {
        .reviews-grid {
            grid-template-columns: 1fr;
        }

        .review-card {
            margin-bottom: 1rem;
        }

        .reviews-section h2 {
            font-size: 2rem;
        }
    }
    </style>

    <!-- Control Panel Features -->
    <section id="control-panel" class="control-panel-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2>Web Control Panel</h2>
                    <p class="lead">Kelola server game Anda dari mana saja dengan browser</p>
                    <div class="panel-features">
                        <div class="panel-feature">
                            <i class='bx bx-devices'></i>
                            <div>
                                <h4>Responsive Design</h4>
                                <p>Akses dari PC, tablet, atau smartphone</p>
                            </div>
                        </div>
                        <div class="panel-feature">
                            <i class='bx bx-command'></i>
                            <div>
                                <h4>Console Commands</h4>
                                <p>Kirim perintah server dengan mudah</p>
                            </div>
                        </div>
                        <div class="panel-feature">
                            <i class='bx bx-file'></i>
                            <div>
                                <h4>File Manager</h4>
                                <p>Upload plugins & mods dengan drag & drop</p>
                            </div>
                        </div>
                        <div class="panel-feature">
                            <i class='bx bx-chart'></i>
                            <div>
                                <h4>Real-time Stats</h4>
                                <p>Monitor performa server secara langsung</p>
                            </div>
                        </div>
                    </div>
                    <?php if (!is_logged_in()): ?>
                        <button class="btn btn-primary btn-lg mt-4 show-login-prompt">
                            <i class='bx bx-right-arrow-alt'></i>
                            Coba Sekarang
                        </button>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <div class="browser-preview">
                        <div class="browser-header">
                            <div class="browser-actions">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            <div class="browser-address">
                                <i class='bx bx-lock'></i>
                                panel.dewatacloud.space
                            </div>
                        </div>
                        <div class="browser-content">
                            <img src="assets/images/panels/dashboard-preview.png" alt="Control Panel Preview" class="dashboard-preview">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
    .control-panel-section {
        padding: 6rem 0;
        background: white;
    }

    .control-panel-section h2 {
        color: var(--dark-color);
        margin-bottom: 1rem;
        font-size: 2.5rem;
    }

    .control-panel-section .lead {
        color: #4A5568;
        margin-bottom: 3rem;
        font-size: 1.2rem;
    }

    .panel-features {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .panel-feature {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .panel-feature i {
        font-size: 2rem;
        color: var(--primary-color);
        background: var(--light-color);
        padding: 1rem;
        border-radius: 15px;
        transition: all 0.3s ease;
    }

    .panel-feature:hover i {
        transform: scale(1.1);
        background: var(--primary-color);
        color: white;
    }

    .panel-feature h4 {
        margin-bottom: 0.5rem;
        color: var(--dark-color);
        font-size: 1.1rem;
    }

    .panel-feature p {
        color: #4A5568;
        margin: 0;
        font-size: 0.9rem;
    }

    .browser-preview {
        background: white;
        border-radius: 10px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);
        transition: all 0.3s ease;
    }

    .browser-preview:hover {
        transform: perspective(1000px) rotateY(-2deg) rotateX(2deg);
    }

    .browser-header {
        background: #f1f5f9;
        padding: 0.75rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .browser-actions {
        display: flex;
        gap: 0.5rem;
    }

    .browser-actions span {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #e2e8f0;
    }

    .browser-actions span:nth-child(1) { background: #fc6058; }
    .browser-actions span:nth-child(2) { background: #fec02f; }
    .browser-actions span:nth-child(3) { background: #2aca3e; }

    .browser-address {
        flex: 1;
        background: white;
        padding: 0.5rem 1rem;
        border-radius: 15px;
        font-size: 0.9rem;
        color: #4A5568;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .browser-address i {
        color: #2aca3e;
    }

    .browser-content {
        padding: 1rem;
    }

    .dashboard-preview {
        width: 100%;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .panel-features {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .browser-preview {
            margin-top: 3rem;
            transform: none;
        }

        .browser-preview:hover {
            transform: none;
        }
    }
    </style>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h4>DewataCloud</h4>
                    <p><?php echo __('footer_about'); ?></p>
                </div>
                <div class="col-md-4">
                    <h4><?php echo __('footer_quick_links'); ?></h4>
                    <ul>
                        <li><a href="#games"><?php echo __('nav_games'); ?></a></li>
                        <li><a href="#locations"><?php echo __('nav_locations'); ?></a></li>
                        <li><a href="#reviews"><?php echo __('nav_reviews'); ?></a></li>
                        <li><a href="contact.php"><?php echo __('footer_contact'); ?></a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h4><?php echo __('footer_contact'); ?></h4>
                    <p>Email: support@dewatacloud.com</p>
                    <p>Discord: discord.gg/dewatacloud</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html> 