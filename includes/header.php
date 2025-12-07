<?php
require_once __DIR__ . '/auth.php';
$current_user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Chaitanya Resort</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <h1>Chaitanya Resort</h1>
                </div>
                <ul class="nav-menu" id="navMenu">
                    <li><a href="index.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'class="active"' : ''; ?>>Home</a></li>
                    <li><a href="gallery.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'gallery.php') ? 'class="active"' : ''; ?>>Gallery</a></li>
                    <li class="auth-buttons">
                        <?php if (isLoggedIn()): ?>
                            <span style="color: var(--primary-color); margin-right: 1rem;">Hello, <?php echo htmlspecialchars($current_user['name']); ?></span>
                            <?php if (isAdmin()): ?>
                                <a href="admin/index.php" class="btn-auth">Admin Panel</a>
                            <?php endif; ?>
                            <a href="#" class="btn-auth" id="logoutBtn">Logout</a>
                        <?php else: ?>
                            <a href="#" class="btn-auth" id="signInBtn">Sign In</a>
                            <a href="#" class="btn-auth btn-auth-primary" id="signUpBtn">Sign Up</a>
                        <?php endif; ?>
                    </li>
                </ul>
                <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </nav>

