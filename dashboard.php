<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Require login
require_login();

// Get user data
$user = get_user($_SESSION['user_id']);
$servers = get_user_servers($_SESSION['user_id']);
$invoices = get_user_invoices($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MimiHost</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="assets/images/logo.png" alt="MimiHost" height="40">
                <h4>MimiHost</h4>
            </div>
            
            <ul class="sidebar-menu">
                <li class="active">
                    <a href="#overview"><i class='bx bxs-dashboard'></i> Overview</a>
                </li>
                <li>
                    <a href="#servers"><i class='bx bxs-server'></i> My Servers</a>
                </li>
                <li>
                    <a href="#billing"><i class='bx bxs-credit-card'></i> Billing</a>
                </li>
                <li>
                    <a href="#support"><i class='bx bxs-help-circle'></i> Support</a>
                </li>
                <li>
                    <a href="logout.php"><i class='bx bxs-log-out'></i> Logout</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="top-bar">
                <div class="search-bar">
                    <i class='bx bx-search'></i>
                    <input type="text" placeholder="Search...">
                </div>
                
                <div class="user-menu">
                    <span class="balance">Balance: <?php echo format_money($user['balance']); ?></span>
                    <a href="#" class="btn btn-primary btn-sm">Add Funds</a>
                    <img src="https://www.gravatar.com/avatar/<?php echo md5($user['email']); ?>?d=mp" alt="Profile" class="avatar">
                </div>
            </div>

            <!-- Overview Section -->
            <section id="overview" class="dashboard-section">
                <h2>Welcome back, <?php echo htmlspecialchars($user['name']); ?>!</h2>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <i class='bx bxs-server'></i>
                        <h3><?php echo count($servers); ?></h3>
                        <p>Active Servers</p>
                    </div>
                    
                    <div class="stat-card">
                        <i class='bx bxs-user'></i>
                        <h3>0</h3>
                        <p>Players Online</p>
                    </div>
                    
                    <div class="stat-card">
                        <i class='bx bxs-credit-card'></i>
                        <h3><?php echo format_money($user['balance']); ?></h3>
                        <p>Account Balance</p>
                    </div>
                    
                    <div class="stat-card">
                        <i class='bx bxs-time'></i>
                        <h3>100%</h3>
                        <p>Uptime</p>
                    </div>
                </div>
            </section>

            <!-- Servers Section -->
            <section id="servers" class="dashboard-section">
                <div class="section-header">
                    <h2>My Servers</h2>
                    <a href="create-server.php" class="btn btn-primary">
                        <i class='bx bx-plus'></i> New Server
                    </a>
                </div>
                
                <div class="servers-grid">
                    <?php foreach ($servers as $server): ?>
                    <div class="server-card">
                        <div class="server-header">
                            <img src="assets/images/<?php echo $server['game_type']; ?>.jpg" alt="<?php echo ucfirst($server['game_type']); ?>">
                            <span class="status <?php echo $server['status']; ?>"><?php echo ucfirst($server['status']); ?></span>
                        </div>
                        
                        <div class="server-info">
                            <h4><?php echo ucfirst($server['game_type']); ?> Server</h4>
                            <p>Location: <?php echo LOCATIONS[$server['location']]['name']; ?></p>
                            <div class="server-stats">
                                <div class="stat">
                                    <i class='bx bxs-cpu'></i>
                                    <span>0%</span>
                                </div>
                                <div class="stat">
                                    <i class='bx bxs-memory-card'></i>
                                    <span>0%</span>
                                </div>
                                <div class="stat">
                                    <i class='bx bxs-user'></i>
                                    <span>0/<?php echo json_decode($server['specs'], true)['slots'] ?? '∞'; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="server-actions">
                            <a href="server.php?id=<?php echo $server['id']; ?>" class="btn btn-primary btn-sm">Manage</a>
                            <button class="btn btn-outline-primary btn-sm">
                                <i class='bx bx-power-off'></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Billing Section -->
            <section id="billing" class="dashboard-section">
                <h2>Billing History</h2>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoices as $invoice): ?>
                            <tr>
                                <td>#<?php echo $invoice['id']; ?></td>
                                <td><?php echo format_date($invoice['created_at']); ?></td>
                                <td><?php echo htmlspecialchars($invoice['description']); ?></td>
                                <td><?php echo format_money($invoice['amount']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $invoice['status'] == 'paid' ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($invoice['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="invoice.php?id=<?php echo $invoice['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <style>
    .dashboard-container {
        display: flex;
        min-height: 100vh;
    }

    /* Sidebar Styles */
    .sidebar {
        width: 260px;
        background: white;
        border-right: 1px solid #E2E8F0;
        padding: 1.5rem;
    }

    .sidebar-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .sidebar-header h4 {
        margin: 0;
        color: var(--primary-color);
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
    }

    .sidebar-menu li {
        margin-bottom: 0.5rem;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: var(--dark-color);
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .sidebar-menu a:hover,
    .sidebar-menu li.active a {
        background: var(--primary-color);
        color: white;
    }

    /* Main Content Styles */
    .main-content {
        flex: 1;
        background: #F7FAFC;
        padding: 2rem;
    }

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .search-bar {
        display: flex;
        align-items: center;
        background: white;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        width: 300px;
    }

    .search-bar input {
        border: none;
        outline: none;
        margin-left: 0.5rem;
        width: 100%;
    }

    .user-menu {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .stat-card i {
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .stat-card h3 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .stat-card p {
        color: #4A5568;
        margin: 0;
    }

    /* Servers Grid */
    .servers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .server-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .server-header {
        position: relative;
    }

    .server-header img {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }

    .status {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        color: white;
        font-size: 0.875rem;
    }

    .status.active {
        background: #48BB78;
    }

    .status.pending {
        background: #ECC94B;
    }

    .status.suspended {
        background: #E53E3E;
    }

    .server-info {
        padding: 1.5rem;
    }

    .server-info h4 {
        margin: 0 0 0.5rem;
    }

    .server-info p {
        color: #4A5568;
        margin: 0 0 1rem;
    }

    .server-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .stat {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .server-actions {
        padding: 1rem 1.5rem;
        border-top: 1px solid #E2E8F0;
        display: flex;
        justify-content: space-between;
    }

    /* Section Styles */
    .dashboard-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    /* Table Styles */
    .table {
        margin: 0;
    }

    .table th {
        font-weight: 600;
        color: var(--dark-color);
    }

    .badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle sidebar navigation
        const menuItems = document.querySelectorAll('.sidebar-menu a');
        const sections = document.querySelectorAll('.dashboard-section');
        
        menuItems.forEach(item => {
            item.addEventListener('click', function(e) {
                if (this.getAttribute('href').startsWith('#')) {
                    e.preventDefault();
                    
                    // Update active state
                    menuItems.forEach(i => i.parentElement.classList.remove('active'));
                    this.parentElement.classList.add('active');
                    
                    // Show selected section
                    const targetId = this.getAttribute('href').substring(1);
                    sections.forEach(section => {
                        section.style.display = section.id === targetId ? 'block' : 'none';
                    });
                }
            });
        });
        
        // Show first section by default
        sections.forEach((section, index) => {
            section.style.display = index === 0 ? 'block' : 'none';
        });
    });
    </script>
</body>
</html> 