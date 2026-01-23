<?php 

include('config/constants.php');

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user came from forgot-password page
if(!isset($_SESSION['reset-email'])){
    header('location:'.SITEURL.'forgot-password.php');
    exit();
}

if(isset($_POST['verify-code'])){
   $code = trim($_POST['code']);
   $email = $_SESSION['reset-email'];
   
   try {
       // Check verification code
       $collection = $conn->selectCollection('users');
       $user = $collection->findOne([
           'email' => $email,
           'reset_code' => (int)$code
       ]);

       if($user){
           // Check if code has expired
           $reset_expiry = $user['reset_expiry']->toDateTime()->getTimestamp();
           
           if(time() > $reset_expiry){
               $_SESSION['verify-error'] = "Verification code has expired. Please request a new one.";
           } else {
               // Code is valid, redirect to reset password page
               $_SESSION['verified'] = true;
               header('location:'.SITEURL.'reset-password.php');
               exit();
           }
       } else {
           $_SESSION['verify-error'] = "Invalid verification code. Please try again.";
       }
   } catch (Exception $e) {
       $_SESSION['verify-error'] = "Error: " . $e->getMessage();
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code - Wow Foods</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>CSS/style-user.css">
</head>
<body>

    <div class="login-wrapper">
        <!-- Left Side - Verify Code Form -->
        <div class="login-left">
            <div class="login-card">
                <div class="login-form-container">
                    <div class="logo">
                        <i class="fas fa-utensils"></i> Wow Foods
                    </div>

                    <h2>Verify Code</h2>
                    <p class="subtitle">Enter the 6-digit code sent to <strong><?php echo htmlspecialchars($_SESSION['reset-email']); ?></strong></p>

                    <?php 
                        // Show verification code for development (remove in production)
                        if(isset($_SESSION['verification-code']) && isset($_SESSION['code-sent'])){
                            echo '<div class="success-message">
                                    <strong>Development Mode:</strong> Your verification code is: 
                                    <span style="font-size: 1.3rem; font-weight: 700;">'.$_SESSION['verification-code'].'</span>
                                  </div>';
                            unset($_SESSION['code-sent']);
                        }
                        
                        if(isset($_SESSION['verify-error'])){
                            echo '<div class="error-message">'.$_SESSION['verify-error'].'</div>';
                            unset($_SESSION['verify-error']);
                        }
                    ?>

                    <form method="post" action="#">
                        <div class="form-group">
                            <label for="code">Verification Code</label>
                            <input type="text" name="code" id="code" placeholder="Enter 6-digit code" maxlength="6" pattern="[0-9]{6}" required>
                        </div>

                        <button type="submit" class="login-btn" name="verify-code">VERIFY</button>

                        <div class="form-footer">
                            <a href="<?php echo SITEURL; ?>forgot-password.php" class="resend-link">
                                Didn't receive code? Resend
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

    <style>
        .subtitle {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }
        
        .subtitle strong {
            color: #2c3e50;
        }
        
        #code {
            text-align: center;
            font-size: 1.5rem;
            letter-spacing: 0.5rem;
            font-weight: 600;
        }
        
        .resend-link {
            color: #4A90E2 !important;
            font-size: 0.9rem;
        }
    </style>

</body>
</html>
