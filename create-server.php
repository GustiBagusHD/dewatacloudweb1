<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Require login
require_login();

// Get user data
$user = get_user($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $game_type = $_POST['game_type'];
    $location = $_POST['location'];
    $specs = [];
    
    // Validate and process specs based on game type
    switch ($game_type) {
        case 'minecraft':
            $specs['ram'] = intval($_POST['ram']);
            if ($specs['ram'] < GAME_CONFIGS['minecraft']['min_ram'] || $specs['ram'] > GAME_CONFIGS['minecraft']['max_ram']) {
                $error = 'Invalid RAM amount';
            }
            break;
            
        case 'samp':
        case 'fivem':
            $specs['slots'] = intval($_POST['slots']);
            $config = GAME_CONFIGS[$game_type];
            if ($specs['slots'] < $config['min_slots'] || $specs['slots'] > $config['max_slots']) {
                $error = 'Invalid number of slots';
            }
            break;
            
        default:
            $error = 'Invalid game type';
            break;
    }
    
    if (!isset($error)) {
        // Calculate price
        $price = calculate_server_price($game_type, $specs);
        
        // Check if user has enough balance
        if ($user['balance'] >= $price) {
            // Create server
            if (create_server($_SESSION['user_id'], $game_type, $location, $specs)) {
                // Deduct balance
                $db->prepare("UPDATE users SET balance = balance - ? WHERE id = ?")->execute([$price, $_SESSION['user_id']]);
                
                // Create invoice
                create_invoice($_SESSION['user_id'], $price, "New {$game_type} server");
                
                // Redirect to dashboard
                header('Location: dashboard.php#servers');
                exit();
            } else {
                $error = 'Failed to create server';
            }
        } else {
            $error = 'Insufficient balance';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Server - MimiHost</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <div class="create-server-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="create-server-card">
                        <div class="text-center mb-4">
                            <img src="assets/images/mascots/mascot.png" alt="Mascot" class="mascot">
                            <h2>Create New Server</h2>
                            <p>Configure your game server</p>
                        </div>
                        
                        <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" id="createServerForm">
                            <div class="game-selector mb-4">
                                <h4>Select Game</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="radio" class="btn-check" name="game_type" id="minecraft" value="minecraft" checked>
                                        <label class="btn game-option" for="minecraft">
                                            <img src="assets/images/minecraft.jpg" alt="Minecraft">
                                            <span>Minecraft</span>
                                        </label>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <input type="radio" class="btn-check" name="game_type" id="samp" value="samp">
                                        <label class="btn game-option" for="samp">
                                            <img src="assets/images/samp.jpg" alt="SA-MP">
                                            <span>SA-MP</span>
                                        </label>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <input type="radio" class="btn-check" name="game_type" id="fivem" value="fivem">
                                        <label class="btn game-option" for="fivem">
                                            <img src="assets/images/fivem.jpg" alt="FiveM">
                                            <span>FiveM</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="location-selector mb-4">
                                <h4>Select Location</h4>
                                <div class="row">
                                    <?php foreach (LOCATIONS as $code => $location): ?>
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="location" id="<?php echo $code; ?>" value="<?php echo $code; ?>" <?php echo $code == 'sg' ? 'checked' : ''; ?>>
                                        <label class="btn location-option" for="<?php echo $code; ?>">
                                            <i class='bx bxs-server'></i>
                                            <div>
                                                <strong><?php echo $location['name']; ?></strong>
                                                <span><?php echo $location['datacenter']; ?></span>
                                                <span class="ping">Ping: <?php echo $location['ping']; ?></span>
                                            </div>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <div class="specs-selector mb-4">
                                <h4>Server Specifications</h4>
                                
                                <div class="specs-option minecraft-specs">
                                    <label class="form-label">RAM</label>
                                    <div class="ram-slider">
                                        <input type="range" class="form-range" id="ramSlider" name="ram" 
                                               min="<?php echo GAME_CONFIGS['minecraft']['min_ram']; ?>" 
                                               max="<?php echo GAME_CONFIGS['minecraft']['max_ram']; ?>" 
                                               step="1024" value="2048">
                                        <div class="d-flex justify-content-between">
                                            <span>1 GB</span>
                                            <span id="ramValue">2 GB</span>
                                            <span>32 GB</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="specs-option samp-specs" style="display: none;">
                                    <label class="form-label">Player Slots</label>
                                    <div class="slots-slider">
                                        <input type="range" class="form-range" id="sampSlider" name="slots" 
                                               min="<?php echo GAME_CONFIGS['samp']['min_slots']; ?>" 
                                               max="<?php echo GAME_CONFIGS['samp']['max_slots']; ?>" 
                                               step="50" value="100">
                                        <div class="d-flex justify-content-between">
                                            <span>50</span>
                                            <span id="sampValue">100</span>
                                            <span>1000</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="specs-option fivem-specs" style="display: none;">
                                    <label class="form-label">Player Slots</label>
                                    <div class="slots-slider">
                                        <input type="range" class="form-range" id="fivemSlider" name="slots" 
                                               min="<?php echo GAME_CONFIGS['fivem']['min_slots']; ?>" 
                                               max="<?php echo GAME_CONFIGS['fivem']['max_slots']; ?>" 
                                               step="32" value="64">
                                        <div class="d-flex justify-content-between">
                                            <span>32</span>
                                            <span id="fivemValue">64</span>
                                            <span>128</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="price-calculator mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="mb-0">Total Price</h4>
                                        <small class="text-muted">Monthly</small>
                                    </div>
                                    <div class="price">
                                        <span id="totalPrice">$10.00</span>
                                        <small class="text-muted">/month</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Create Server</button>
                                <a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .create-server-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #FFF5F7 0%, #FFF 100%);
        padding: 4rem 0;
    }

    .create-server-card {
        background: white;
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .mascot {
        width: 120px;
        margin-bottom: 1.5rem;
        animation: float 6s ease-in-out infinite;
    }

    .game-option,
    .location-option {
        width: 100%;
        border: 2px solid #E2E8F0;
        border-radius: 15px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .game-option img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 0.5rem;
    }

    .game-option span {
        display: block;
        text-align: center;
        font-weight: 500;
    }

    .location-option {
        display: flex;
        align-items: center;
        gap: 1rem;
        text-align: left;
    }

    .location-option i {
        font-size: 2rem;
        color: var(--primary-color);
    }

    .location-option div {
        display: flex;
        flex-direction: column;
    }

    .location-option .ping {
        font-size: 0.875rem;
        color: #4A5568;
    }

    .btn-check:checked + .game-option,
    .btn-check:checked + .location-option {
        border-color: var(--primary-color);
        background-color: #FFF5F7;
    }

    .specs-selector h4 {
        margin-bottom: 1.5rem;
    }

    .form-range {
        height: 6px;
    }

    .form-range::-webkit-slider-thumb {
        background: var(--primary-color);
    }

    .price-calculator {
        background: #F7FAFC;
        border-radius: 15px;
        padding: 1.5rem;
    }

    .price {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    .price small {
        font-size: 1rem;
        font-weight: 400;
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('createServerForm');
        const gameInputs = document.querySelectorAll('input[name="game_type"]');
        const specsOptions = document.querySelectorAll('.specs-option');
        const ramSlider = document.getElementById('ramSlider');
        const ramValue = document.getElementById('ramValue');
        const sampSlider = document.getElementById('sampSlider');
        const sampValue = document.getElementById('sampValue');
        const fivemSlider = document.getElementById('fivemSlider');
        const fivemValue = document.getElementById('fivemValue');
        const totalPrice = document.getElementById('totalPrice');
        
        // Game type change handler
        function updateSpecsVisibility() {
            const selectedGame = document.querySelector('input[name="game_type"]:checked').value;
            
            specsOptions.forEach(option => {
                option.style.display = 'none';
            });
            
            document.querySelector(`.${selectedGame}-specs`).style.display = 'block';
            
            updatePrice();
        }
        
        // Price calculator
        function updatePrice() {
            const selectedGame = document.querySelector('input[name="game_type"]:checked').value;
            let price = 0;
            
            switch (selectedGame) {
                case 'minecraft':
                    price = (ramSlider.value / 1024) * <?php echo GAME_CONFIGS['minecraft']['price_per_gb']; ?>;
                    break;
                case 'samp':
                    price = sampSlider.value * <?php echo GAME_CONFIGS['samp']['price_per_slot']; ?>;
                    break;
                case 'fivem':
                    price = fivemSlider.value * <?php echo GAME_CONFIGS['fivem']['price_per_slot']; ?>;
                    break;
            }
            
            totalPrice.textContent = `$${price.toFixed(2)}`;
        }
        
        // Slider value updates
        ramSlider.addEventListener('input', function() {
            ramValue.textContent = `${this.value / 1024} GB`;
            updatePrice();
        });
        
        sampSlider.addEventListener('input', function() {
            sampValue.textContent = this.value;
            updatePrice();
        });
        
        fivemSlider.addEventListener('input', function() {
            fivemValue.textContent = this.value;
            updatePrice();
        });
        
        // Game type change event
        gameInputs.forEach(input => {
            input.addEventListener('change', updateSpecsVisibility);
        });
        
        // Initial setup
        updateSpecsVisibility();
    });
    </script>
</body>
</html> 