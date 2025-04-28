<?php
include_once "db.php";
$username = $email = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

if (empty(trim($_POST["username"]))) {
$username_err = "Please enter a username.";
} else {
$sql = "SELECT id FROM users WHERE username = ?";

if ($stmt = mysqli_prepare($conn, $sql)) {
mysqli_stmt_bind_param($stmt, "s", $param_username);

$param_username = trim($_POST["username"]);
if (mysqli_stmt_execute($stmt)) {
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) == 1) {
$username_err = "This username is already taken.";
} else {
$username = trim($_POST["username"]);
}
} else {
echo "Oops! Something went wrong. Please try again later.";
}

mysqli_stmt_close($stmt);
}
}
if (empty(trim($_POST["email"]))) {
$email_err = "Please enter an email.";
} else {
if (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
$email_err = "Please enter a valid email address.";
} else {
$sql = "SELECT id FROM users WHERE email = ?";

if ($stmt = mysqli_prepare($conn, $sql)) {
mysqli_stmt_bind_param($stmt, "s", $param_email);
$param_email = trim($_POST["email"]);

if (mysqli_stmt_execute($stmt)) {
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) == 1) {
$email_err = "This email is already registered.";
} else {
$email = trim($_POST["email"]);
}
} else {
echo "Oops! Something went wrong. Please try again later.";
}

mysqli_stmt_close($stmt);
}
}
}

if (empty(trim($_POST["password"]))) {
$password_err = "Please enter a password.";     
} elseif (strlen(trim($_POST["password"])) < 6) {
$password_err = "Password must have at least 6 characters.";
} else {
$password = trim($_POST["password"]);
}

if (empty(trim($_POST["confirm_password"]))) {
$confirm_password_err = "Please confirm password.";     
} else {
$confirm_password = trim($_POST["confirm_password"]);
if (empty($password_err) && ($password != $confirm_password)) {
$confirm_password_err = "Passwords did not match.";
}
}

if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

if ($stmt = mysqli_prepare($conn, $sql)) {
mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);

$param_username = $username;
$param_email = $email;
$param_password = password_hash($password, PASSWORD_DEFAULT);
if (mysqli_stmt_execute($stmt)) {
$userId = mysqli_insert_id($conn);
$_SESSION["loggedin"] = true;
$_SESSION["id"] = $userId;
$_SESSION["username"] = $username;

header("location: index.php");
exit();
} else {
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
<h2 class="text-center mb-4">Create an Account</h2>
<p class="text-center text-muted mb-4">Join GameVerse to track games, participate in forums, and more!</p>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="needs-validation" novalidate>
<div class="mb-3">
<label for="username" class="form-label">Username</label>
<input type="text" name="username" id="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" required>
<div class="invalid-feedback">
<?php echo $username_err; ?>
</div>
</div>

<div class="mb-3">
<label for="email" class="form-label">Email</label>
<input type="email" name="email" id="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" required>
<div class="invalid-feedback">
<?php echo $email_err; ?>
</div>
</div>

<div class="mb-3">
<label for="password" class="form-label">Password</label>
<input type="password" name="password" id="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" required>
<div class="invalid-feedback">
<?php echo $password_err; ?>
</div>
</div>

<div class="mb-4">
<label for="confirm_password" class="form-label">Confirm Password</label>
<input type="password" name="confirm_password" id="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" required>
<div class="invalid-feedback">
<?php echo $confirm_password_err; ?>
</div>
</div>

<div class="d-grid mb-3">
<button type="submit" class="btn btn-primary">Register</button>
</div>

<p class="text-center">Already have an account? <a href="login.php">Login here</a></p>
</form>
</div>
</div>
</div>
</div>

<?php include "footer.php"; ?>