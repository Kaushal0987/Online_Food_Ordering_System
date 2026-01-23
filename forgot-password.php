<?php 

include('config/constants.php');

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(isset($_POST['send-code'])){
   $email = trim($_POST['email']);
   
   try {
       // Check if email exists in database
       $collection = $conn->selectCollection('users');
       $user = $collection->findOne(['email' => $email]);

       if($user){
           // Generate 6-digit verification code
           $verification_code = rand(100000, 999999);
           
           // Store verification code in database with expiry (10 minutes)
           $collection->updateOne(
               ['email' => $email],
               ['$set' => [
                   'reset_code' => $verification_code,
                   'reset_expiry' => new MongoDB\BSON\UTCDateTime((time() + 600) * 1000) // 10 minutes
               ]]
           );
           
           // For development: Store code in session and show it
           // In production, send actual email
           $_SESSION['reset-email'] = $email;
           $_SESSION['verification-code'] = $verification_code; // Show code for dev
           $_SESSION['code-sent'] = true;
           
           // Uncomment for production email sending:
           /*
           $to = $email;
           $subject = "Password Reset - Wow Foods";
           $message = "Your password reset verification code is: " . $verification_code . "\n\n";
           $message .= "This code will expire in 10 minutes.\n\n";
           $message .= "If you didn't request this, please ignore this email.";
           $headers = "From: noreply@wowfoods.com\r\n";
           $headers .= "Reply-To: support@wowfoods.com\r\n";
           $headers .= "X-Mailer: PHP/" . phpversion();
           
           mail($to, $subject, $message, $headers);
           */
           
           header('location:'.SITEURL.'verify-code.php');
           exit();
       } else {
           $_SESSION['forgot-error'] = "Email not found in our system.";
       }
   } catch (Exception $e) {
       $_SESSION['forgot-error'] = "Error: " . $e->getMessage();
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Wow Foods</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>CSS/style-user.css">
</head>
<body>

    <div class="login-wrapper">
        <!-- Left Side - Forgot Password Form -->
        <div class="login-left">
            <div class="login-card">
                <div class="login-form-container">
                    <div class="logo">
                        <i class="fas fa-utensils"></i> Wow Foods
                    </div>

                    <h2>Forgot Password</h2>
                    <p class="subtitle">Enter your email address and we'll send you a verification code.</p>

                    <?php 
                        if(isset($_SESSION['forgot-error'])){
                            echo '<div class="error-message">'.$_SESSION['forgot-error'].'</div>';
                            unset($_SESSION['forgot-error']);
                        }
                    ?>

                    <form method="post" action="#">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" placeholder="" required>
                        </div>

                        <button type="submit" class="login-btn" name="send-code">SEND CODE</button>

                        <div class="form-footer">
                            <a href="<?php echo SITEURL; ?>login.php" class="back-link">
                                <i class="fas fa-arrow-left"></i> Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Side - Food Image -->
        <div class="login-right">
            <img src="<?php echo SITEURL; ?>images/food/Food-Name-7751.jpg" alt="Delicious Food">
        </div>
    </div>

</body>
</html>
