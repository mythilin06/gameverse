<?php
// Include config file
require_once "db.php";

// Initialize variables
$email = $email_err = "";
$subscription_success = false;

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email address.";     
    } elseif(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){
        $email_err = "Please enter a valid email address.";
    } else{
        $email = trim($_POST["email"]);
        
        // Check if email already subscribed
        $sql = "SELECT id FROM newsletter WHERE email = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;
            
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Email already subscribed
                    $subscription_success = true;
                } else{
                    // Insert into newsletter table
                    $insert_sql = "INSERT INTO newsletter (email) VALUES (?)";
                    
                    if($insert_stmt = mysqli_prepare($conn, $insert_sql)){
                        mysqli_stmt_bind_param($insert_stmt, "s", $param_email);
                        
                        if(mysqli_stmt_execute($insert_stmt)){
                            $subscription_success = true;
                        } else{
                            $email_err = "Something went wrong. Please try again later.";
                        }
                        
                        mysqli_stmt_close($insert_stmt);
                    }
                }
            } else{
                $email_err = "Something went wrong. Please try again later.";
            }
            
            mysqli_stmt_close($stmt);
        }
    }
    
    // Set flash message
    if($subscription_success){
        $_SESSION["flash_message"] = [
            "message" => "Thank you for subscribing to our newsletter!",
            "type" => "success"
        ];
    } else if(!empty($email_err)){
        $_SESSION["flash_message"] = [
            "message" => $email_err,
            "type" => "danger"
        ];
    }
    
    // Redirect back to referrer or home page
    $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
    header("location: $redirect");
    exit;
}

// If not a POST request, redirect to home page
header("location: index.php");
exit;
?>