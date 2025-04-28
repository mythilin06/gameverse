<?php
// Include header
include_once "header.php";

// Check if user is logged in
$subscription_err = "";
$subscription_success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isLoggedIn()) {
    // Process payment form
    $card_number = $_POST["card_number"] ?? "";
    $card_expiry = $_POST["card_expiry"] ?? "";
    $card_cvv = $_POST["card_cvv"] ?? "";
    
    // Simple validation
    if (empty($card_number) || empty($card_expiry) || empty($card_cvv)) {
        $subscription_err = "All payment fields are required.";
    } elseif (strlen($card_number) < 16) {
        $subscription_err = "Invalid card number.";
    } elseif (strlen($card_cvv) < 3) {
        $subscription_err = "Invalid CVV.";
    } else {
        // In a real app, you'd handle payment processing here
        // For demo, we'll just update the user's premium status
        
        $update_query = "UPDATE users SET is_premium = 1 WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
        
        if (mysqli_stmt_execute($stmt)) {
            $subscription_success = "Congratulations! You are now a premium member.";
        } else {
            $subscription_err = "Something went wrong. Please try again.";
        }
    }
}

// Check if user is already a premium member
$is_premium = false;
if (isLoggedIn()) {
    $user_query = "SELECT is_premium FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $user_query);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($user = mysqli_fetch_assoc($result)) {
        $is_premium = (bool)$user['is_premium'];
    }
}
?>

<!-- Premium Header Section -->
<div class="premium-header">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1 class="display-4 fw-bold mb-3">Upgrade to Premium</h1>
                <p class="lead mb-5">Enhance your gaming experience with exclusive benefits and features.</p>
                <div class="badge bg-warning text-dark fs-5 mb-3">Only ₹1000 / year</div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Premium Benefits -->
    <div class="row mb-5">
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mx-auto mb-4">
                        <i class="fas fa-ad fa-2x"></i>
                    </div>
                    <h3 class="h5 fw-bold mb-3">Ad-Free Experience</h3>
                    <p class="text-muted">
                        Enjoy the entire GameVerse platform without any advertisements or distractions.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mx-auto mb-4">
                        <i class="fab fa-discord fa-2x"></i>
                    </div>
                    <h3 class="h5 fw-bold mb-3">Discord Integration</h3>
                    <p class="text-muted">
                        Gain access to our exclusive Discord server with premium channels and direct developer support.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="feature-icon mx-auto mb-4">
                        <i class="fas fa-bolt fa-2x"></i>
                    </div>
                    <h3 class="h5 fw-bold mb-3">Early Access</h3>
                    <p class="text-muted">
                        Be the first to experience new features and updates before they're released to everyone else.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Premium Subscription Form -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <?php if ($subscription_success): ?>
                <div class="alert alert-success text-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $subscription_success; ?>
                </div>
                <div class="text-center mb-4">
                    <p>You now have access to all premium features, including:</p>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item">Ad-free browsing experience</li>
                        <li class="list-group-item">Access to premium Discord server</li>
                        <li class="list-group-item">Early access to new features</li>
                    </ul>
                    <p>To join our Discord server, click the button below:</p>
                    <a href="https://discord.gg/gameverse" target="_blank" class="btn btn-primary">
                        <i class="fab fa-discord me-2"></i> Join Discord Server
                    </a>
                </div>
            <?php elseif ($is_premium): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    You are already a premium member! Enjoy all the exclusive benefits.
                </div>
                <div class="text-center mb-4">
                    <p>You have access to all premium features, including:</p>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item">Ad-free browsing experience</li>
                        <li class="list-group-item">Access to premium Discord server</li>
                        <li class="list-group-item">Early access to new features</li>
                    </ul>
                    <p>To join our Discord server, click the button below:</p>
                    <a href="https://discord.gg/gameverse" target="_blank" class="btn btn-primary">
                        <i class="fab fa-discord me-2"></i> Join Discord Server
                    </a>
                </div>
            <?php elseif (isLoggedIn()): ?>
                <div class="premium-card">
                    <div class="card-header">
                        <h2 class="h4 mb-0">Subscribe to Premium</h2>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($subscription_err)): ?>
                            <div class="alert alert-danger"><?php echo $subscription_err; ?></div>
                        <?php endif; ?>
                        
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="mb-3">
                                <label for="card_number" class="form-label">Card Number</label>
                                <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="16" required>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="card_expiry" class="form-label">Expiration Date</label>
                                    <input type="text" class="form-control" id="card_expiry" name="card_expiry" placeholder="MM/YY" maxlength="5" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="card_cvv" class="form-label">CVV</label>
                                    <input type="text" class="form-control" id="card_cvv" name="card_cvv" placeholder="123" maxlength="4" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="name_on_card" class="form-label">Name on Card</label>
                                <input type="text" class="form-control" id="name_on_card" name="name_on_card" required>
                            </div>
                            <div class="alert alert-info">
                                <div class="d-flex">
                                    <div>
                                        <i class="fas fa-info-circle mt-1 me-3"></i>
                                    </div>
                                    <div>
                                        <strong>Total: ₹1000</strong> for 1 year of Premium membership. Your subscription will automatically renew after 1 year.
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-crown me-2"></i> Subscribe Now
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Please <a href="login.php" class="alert-link">login</a> or <a href="register.php" class="alert-link">register</a> to subscribe to premium.
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Testimonials -->
    <div class="row mt-5">
        <div class="col-12 text-center mb-4">
            <h2>What Our Premium Members Say</h2>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex mb-3">
                        <div class="star-rating">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <p class="mb-4">"The premium subscription is totally worth it. The ad-free experience and Discord community have enhanced my gaming experience tremendously!"</p>
                    <div class="d-flex align-items-center">
                        <div class="ms-3">
                            <h5 class="mb-0">Rahul S.</h5>
                            <small class="text-muted">Premium Member</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex mb-3">
                        <div class="star-rating">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <p class="mb-4">"Being part of the Discord community has connected me with fellow gamers who share my interests. The early access to new features is a great bonus!"</p>
                    <div class="d-flex align-items-center">
                        <div class="ms-3">
                            <h5 class="mb-0">Priya M.</h5>
                            <small class="text-muted">Premium Member</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex mb-3">
                        <div class="star-rating">
                            <?php for($i = 1; $i <= 4; $i++): ?>
                                <i class="fas fa-star"></i>
                            <?php endfor; ?>
                            <i class="fas fa-star inactive"></i>
                        </div>
                    </div>
                    <p class="mb-4">"For just ₹1000 a year, the premium subscription offers excellent value. The Discord community is very helpful for getting gaming tips and making new friends."</p>
                    <div class="d-flex align-items-center">
                        <div class="ms-3">
                            <h5 class="mb-0">Arjun K.</h5>
                            <small class="text-muted">Premium Member</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>