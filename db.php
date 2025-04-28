<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'gameverse');
$conn = mysqli_connect('localhost', 'root', '', 'gameverse');

if($conn === false){
    echo "ERROR: Could not connect. " . mysqli_connect_error();
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

function isLoggedIn() {
    return isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
}

function redirectWithMessage($location, $message, $type = "danger") {
    $_SESSION["flash_message"] = [
        "message" => $message,
        "type" => $type
    ];
    
    header("location: $location");
    exit;
}
?>
