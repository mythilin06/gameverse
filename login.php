<?php
require_once "db.php";

$username = $password = "";
$username_err = $password_err = $login_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

if(empty(trim($_POST["username"]))){
$username_err = "Please enter username.";
} else{
$username = trim($_POST["username"]);
}

if(empty(trim($_POST["password"]))){
$password_err = "Please enter your password.";
} else{
$password = trim($_POST["password"]);
}

if(empty($username_err) && empty($password_err)){
$sql = "SELECT id, username, password FROM users WHERE username = ?";

if($stmt = mysqli_prepare($conn, $sql)){
mysqli_stmt_bind_param($stmt, "s", $param_username);

$param_username = $username;

if(mysqli_stmt_execute($stmt)){
mysqli_stmt_store_result($stmt);

if(mysqli_stmt_num_rows($stmt) == 1){                    
mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
if(mysqli_stmt_fetch($stmt)){
if(password_verify($password, $hashed_password)){
session_start();

$_SESSION["loggedin"] = true;
$_SESSION["id"] = $id;
$_SESSION["username"] = $username;                            

header("location: index.php");
exit;
} else{
$login_err = "Invalid username or password.";
}
}
} else{
$login_err = "Invalid username or password.";
}
} else{
echo "Oops! Something went wrong. Please try again later.";
}

mysqli_stmt_close($stmt);
}
}

mysqli_close($conn);
}
?>

<?php include "header.php"; ?>

<div class="container py-5">
<div class="row justify-content-center">
<div class="col-md-6">
<div class="auth-form">
<h2 class="text-center mb-4">Login to Your Account</h2>
<p class="text-center text-muted mb-4">Welcome back! Please enter your credentials to continue.</p>

<?php 
if(!empty($login_err)){
echo '<div class="alert alert-danger">' . $login_err . '</div>';
}        
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<div class="mb-3">
<label for="username" class="form-label">Username</label>
<input type="text" name="username" id="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
<div class="invalid-feedback"><?php echo $username_err; ?></div>
</div>    
<div class="mb-4">
<label for="password" class="form-label">Password</label>
<input type="password" name="password" id="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
<div class="invalid-feedback"><?php echo $password_err; ?></div>
</div>
<div class="d-grid mb-3">
<button type="submit" class="btn btn-primary">Login</button>
</div>
<p class="text-center">Don't have an account? <a href="register.php">Sign up now</a></p>
</form>
</div>
</div>
</div>
</div>

<?php include "footer.php"; ?>