<?php
require_once "db.php";

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GameVerse | Your Ultimate Gaming Hub</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link href="style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">
<a class="navbar-brand" href="index.php">GameVerse</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
<ul class="navbar-nav me-auto">
<li class="nav-item">
<a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a>
</li>
<li class="nav-item">
<a class="nav-link <?php echo ($current_page == 'wiki.php') ? 'active' : ''; ?>" href="wiki.php">Wiki</a>
</li>
<li class="nav-item">
<a class="nav-link <?php echo ($current_page == 'forums.php') ? 'active' : ''; ?>" href="forums.php">Forums</a>
</li>
<?php if (isLoggedIn()): ?>
<li class="nav-item">
<a class="nav-link <?php echo ($current_page == 'tracker.php') ? 'active' : ''; ?>" href="tracker.php">Game Tracker</a>
</li>
<?php endif; ?>
<li class="nav-item">
<a class="nav-link <?php echo ($current_page == 'premium.php') ? 'active' : ''; ?>" href="premium.php">Premium</a>
</li>
</ul>
<div class="navbar-nav ms-auto">
<?php if (isLoggedIn()): ?>
<div class="dropdown">
<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
<?php echo htmlspecialchars($_SESSION["username"]); ?>
</a>
<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
<li><a class="dropdown-item" href="profile.php">Profile</a></li>
<li><hr class="dropdown-divider"></li>
<li><a class="dropdown-item" href="logout.php">Logout</a></li>
</ul>
</div>
<?php else: ?>
<a class="nav-link <?php echo ($current_page == 'login.php') ? 'active' : ''; ?>" href="login.php">Login</a>
<a class="nav-link <?php echo ($current_page == 'register.php') ? 'active' : ''; ?>" href="register.php">Register</a>
<?php endif; ?>
</div>
</div>
</div>
</nav>
<?php if (isset($_SESSION["flash_message"])): ?>
<div class="container mt-3">
<div class="alert alert-<?php echo $_SESSION["flash_message"]["type"]; ?> alert-dismissible fade show" role="alert">
<?php echo $_SESSION["flash_message"]["message"]; ?>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
</div>
<?php unset($_SESSION["flash_message"]); ?>
<?php endif; ?>

<div class="container-fluid p-0">