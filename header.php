<?php 
// Check if a session is already active before starting one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db_connect.php'); 

// Handle currency switching request
if (isset($_GET['currency'])) {
    $_SESSION['currency'] = ($_GET['currency'] === 'USD') ? 'USD' : 'LKR';
    // Clean refresh to strip out the query string parameters from the address bar
    $actual_link = strtok($_SERVER["REQUEST_URI"], '?');
    header("Location: " . $actual_link);
    exit();
}

// Track active setting state (Defaulting back to local currency if unset)
$current_currency = $_SESSION['currency'] ?? 'LKR';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/estate_sphere/style.css">
    <title>Estate Sphere | Sri Lanka</title>
    
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark-theme');
            window.addEventListener('DOMContentLoaded', () => document.body.classList.add('dark-theme'));
        }

        function toggleTheme(e) {
            e.preventDefault();
            document.body.classList.toggle('dark-theme');
            
            if (document.body.classList.contains('dark-theme')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
        }
    </script>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/estate_sphere/index.php" style="text-decoration:none; color:var(--navy); font-size:1.5rem;"><strong>ESTATE</strong> SPHERE</a>
            <ul class="nav-links">
                <li><a href="/estate_sphere/index.php">Home</a></li>
                <li><a href="/estate_sphere/properties.php">Properties</a></li>
                <li><a href="/estate_sphere/about.php">About</a></li>
                <li><a href="/estate_sphere/contact.php">Contact</a></li>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="user-menu">
                        <a href="#" style="color:var(--crimson)">My Account ▼</a>
                        <ul class="dropdown-content">
                            <li><a href="/estate_sphere/profile.php">👤 Profile</a></li>
                            <li><a href="/estate_sphere/history.php">📜 History</a></li>
                            <li><a href="/estate_sphere/favourites.php">❤️ Favourites</a></li>
                            <li><a href="/estate_sphere/tracking.php">📍 Tracking</a></li>
                            <li><a href="/estate_sphere/my-reservations.php">📅 My Reservations</a></li>
                            <li><a href="/estate_sphere/cart.php">🛒 Shopping Cart</a></li>
                            
                            <li style="border-top: 1px solid #eee; margin-top: 5px; padding: 8px 12px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 0.85rem; color: #555; font-weight: bold;">Currency:</span>
                                <div style="display: flex; background: #eee; padding: 2px; border-radius: 4px;">
                                    <a href="?currency=LKR" style="padding: 2px 6px; font-size: 0.75rem; text-decoration: none; border-radius: 3px; font-weight: bold; transition: 0.2s; <?php echo $current_currency === 'LKR' ? 'background: var(--navy); color:white;' : 'color:#333;'; ?>">LKR</a>
                                    <a href="?currency=USD" style="padding: 2px 6px; font-size: 0.75rem; text-decoration: none; border-radius: 3px; font-weight: bold; transition: 0.2s; <?php echo $current_currency === 'USD' ? 'background: var(--navy); color:white;' : 'color:#333;'; ?>">USD</a>
                                </div>
                            </li>

                            <li><a href="#" onclick="toggleTheme(event)">🌗 Toggle Theme</a></li>
                            
                            <?php if(isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'super_admin')): ?>
                                <li style="border-top: 1px solid #eee; margin-top: 5px; padding-top: 5px;">
                                    <a href="/estate_sphere/admin-dashboard.php" style="color: var(--crimson); font-weight: bold;">⚙️ Admin Dashboard</a>
                                </li>
                                <li>
                                    <a href="/estate_sphere/admin-inquiries.php" style="color: var(--crimson); font-weight: bold;">📥 Client Inbox</a>
                                </li>
                            <?php endif; ?>

                            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'super_admin'): ?>
                                <li>
                                    <a href="/estate_sphere/manage-users.php" style="color: var(--crimson); font-weight: bold;">👑 Manage Users</a>
                                </li>
                            <?php endif; ?>
                            
                            <li style="border-top: 1px solid #eee; margin-top: 5px;"><a href="/estate_sphere/logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="/estate_sphere/login.php">Login</a></li>
                    <li><a href="/estate_sphere/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>