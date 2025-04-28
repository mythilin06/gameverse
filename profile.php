<?php
include_once "header.php";
if (!isLoggedIn()) {
header("location: login.php");
exit;
}
$current_password = $new_password = $confirm_password = "";
$current_password_err = $new_password_err = $confirm_password_err = "";
$update_success = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
if(empty(trim($_POST["current_password"]))){
$current_password_err = "Please enter your current password.";     
} else{
$current_password = trim($_POST["current_password"]);
$sql = "SELECT password FROM users WHERE id = ?";
if($stmt = mysqli_prepare($conn, $sql)){
mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);

if(mysqli_stmt_execute($stmt)){
mysqli_stmt_store_result($stmt);

if(mysqli_stmt_num_rows($stmt) == 1){
mysqli_stmt_bind_result($stmt, $hashed_password);
if(mysqli_stmt_fetch($stmt)){
if(!password_verify($current_password, $hashed_password)){
$current_password_err = "The current password you entered is not correct.";
}
}
} else{

$current_password_err = "Something went wrong. Please try again later.";
}
} else{
$current_password_err = "Something went wrong. Please try again later.";
}

mysqli_stmt_close($stmt);
}
}

if(empty(trim($_POST["new_password"]))){
$new_password_err = "Please enter the new password.";     
} elseif(strlen(trim($_POST["new_password"])) < 6){
$new_password_err = "Password must have at least 6 characters.";
} else{
$new_password = trim($_POST["new_password"]);
}

if(empty(trim($_POST["confirm_password"]))){
$confirm_password_err = "Please confirm the password.";
} else{
$confirm_password = trim($_POST["confirm_password"]);
if(empty($new_password_err) && ($new_password != $confirm_password)){
$confirm_password_err = "Password did not match.";
}
}
if(empty($current_password_err) && empty($new_password_err) && empty($confirm_password_err)){

$sql = "UPDATE users SET password = ? WHERE id = ?";

if($stmt = mysqli_prepare($conn, $sql)){
mysqli_stmt_bind_param($stmt, "si", $param_password, $_SESSION["id"]);

$param_password = password_hash($new_password, PASSWORD_DEFAULT);

if(mysqli_stmt_execute($stmt)){
$update_success = "Your password has been changed successfully.";

$current_password = $new_password = $confirm_password = "";
} else{
$current_password_err = "Something went wrong. Please try again later.";
}

mysqli_stmt_close($stmt);
}
}
}

$user_query = "SELECT username, email, is_premium, created_at FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $user_query);
mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

$posts_query = "SELECT COUNT(*) as count FROM forum_posts WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $posts_query);
mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$posts_count = mysqli_fetch_assoc($result)['count'];

$comments_query = "SELECT COUNT(*) as count FROM comments WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $comments_query);
mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$comments_count = mysqli_fetch_assoc($result)['count'];

$games_query = "SELECT COUNT(*) as count FROM game_tracking WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $comments_query);
mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$games_count = mysqli_fetch_assoc($result)['count'];
?>

<div class="container py-5">
<div class="row">
<div class="col-md-4 mb-4">
<div class="card">
<div class="card-header bg-dark text-white">
<h5 class="mb-0">Profile</h5>
</div>
<div class="card-body">
<div class="text-center mb-4">
<div class="bg-light rounded-circle mx-auto mb-3" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
<i class="fas fa-user fa-3x text-secondary"></i>
</div>
<h4><?php echo htmlspecialchars($user['username']); ?></h4>
<p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
<?php if ($user['is_premium']): ?>
<span class="badge bg-warning text-dark">Premium Member</span>
<?php endif; ?>
</div>
<ul class="list-group list-group-flush">
<li class="list-group-item d-flex justify-content-between">
<span><i class="fas fa-calendar-alt me-2"></i> Member Since</span>
<span><?php echo date('M j, Y', strtotime($user['created_at'])); ?></span>
</li>
<li class="list-group-item d-flex justify-content-between">
<span><i class="fas fa-comments me-2"></i> Forum Posts</span>
<span><?php echo $posts_count; ?></span>
</li>
<li class="list-group-item d-flex justify-content-between">
<span><i class="fas fa-reply me-2"></i> Comments</span>
<span><?php echo $comments_count; ?></span>
</li>
<li class="list-group-item d-flex justify-content-between">
<span><i class="fas fa-gamepad me-2"></i> Tracked Games</span>
<span><?php echo $games_count; ?></span>
</li>
</ul>

<?php if (!$user['is_premium']): ?>
<div class="mt-4">
<a href="premium.php" class="btn btn-warning w-100">
<i class="fas fa-crown me-2"></i> Upgrade to Premium
</a>
</div>
<?php endif; ?>
</div>
</div>
</div>

<div class="col-md-8">
<div class="card mb-4">
<div class="card-header bg-primary text-white">
<h5 class="mb-0">Change Password</h5>
</div>
<div class="card-body">
<?php if (!empty($update_success)): ?>
<div class="alert alert-success"><?php echo $update_success; ?></div>
<?php endif; ?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<div class="mb-3">
<label for="current_password" class="form-label">Current Password</label>
<input type="password" name="current_password" id="current_password" class="form-control <?php echo (!empty($current_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $current_password; ?>">
<div class="invalid-feedback"><?php echo $current_password_err; ?></div>
</div>

<div class="mb-3">
<label for="new_password" class="form-label">New Password</label>
<input type="password" name="new_password" id="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
<div class="invalid-feedback"><?php echo $new_password_err; ?></div>
<div class="form-text">Password must be at least 6 characters long.</div>
</div>

<div class="mb-3">
<label for="confirm_password" class="form-label">Confirm Password</label>
<input type="password" name="confirm_password" id="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
<div class="invalid-feedback"><?php echo $confirm_password_err; ?></div>
</div>

<div class="mb-3">
<button type="submit" class="btn btn-primary">Change Password</button>
</div>
</form>
</div>
</div>

<div class="card">
<div class="card-header bg-light">
<h5 class="mb-0">Recent Activity</h5>
</div>
<div class="card-body">
<div class="alert alert-info">
<i class="fas fa-info-circle me-2"></i>
This section will show your recent forum posts, comments, and game tracking updates. Feature coming soon!
</div>
</div>
</div>
</div>
</div>
</div>

<?php include "footer.php"; ?>