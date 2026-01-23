<?php 

include('config/constants.php');

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user has verified the code
if(!isset($_SESSION['verified']) || !isset($_SESSION['reset-email'])){
    header('location:'.SITEURL.'forgot-password.php');
    exit();
}

if(isset($_POST['reset-password'])){
   $password = $_POST['password'];
   $confirm_password = $_POST['confirm_password'];
   $email = $_SESSION['reset-email'];
   
   if($password !== $confirm_password){
       $_SESSION['reset-error'] = "Passwords do not match.";
   } elseif(strlen($password) < 8){
       $_SESSION['reset-error'] = "Password must be at least 8 characters long.";
   } else {
       try {
           // Update password
           $collection = $conn->selectCollection('users');
           $hashed_password = md5($password);
           
           $result = $collection->updateOne(
               ['email' => $email],
               ['$set' => [
                   'password' => $hashed_password
               ],
               '$unset' => [
                   'reset_code' => '',
                   'reset_expiry' => ''
               ]]
           );

           if($result->getModifiedCount() > 0){
               // Clear session variables
               unset($_SESSION['reset-email']);
               unset($_SESSION['verified']);
               
               // Set success message and redirect to login
               $_SESSION['login-success'] = "Password reset successful! Please login with your new password.";
               header('location:'.SITEURL.'login.php');
               exit();
           } else {
               $_SESSION['reset-error'] = "Failed to reset password. Please try again.";
           }
       } catch (Exception $e) {
           $_SESSION['reset-error'] = "Error: " . $e->getMessage();
       }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Wow Foods</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>CSS/style-user.css">
</head>
<body>

    <div class="login-wrapper">
        <!-- Left Side - Reset Password Form -->
        <div class="login-left">
            <div class="login-card">
                <div class="login-form-container">
                    <div class="logo">
                        <i class="fas fa-utensils"></i> Wow Foods
                    </div>

                    <h2>Reset Password</h2>
                    <p class="subtitle">Enter your new password below.</p>

                    <?php 
                        if(isset($_SESSION['reset-error'])){
                            echo '<div class="error-message">'.$_SESSION['reset-error'].'</div>';
                            unset($_SESSION['reset-error']);
                        }
                    ?>

                    <form method="post" action="#" id="resetForm">
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" name="password" id="password" placeholder="" required>
                            <div class="error" id="passwordError"></div>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" placeholder="" required>
                            <div class="error" id="confirmError"></div>
                        </div>

                        <button type="submit" class="login-btn" name="reset-password">RESET PASSWORD</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Side - Food Image -->
        <div class="login-right">
            <img src="<?php echo SITEURL; ?>images/food/Food-Name-7751.jpg" alt="Delicious Food">
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirm_password');
        const passwordError = document.getElementById('passwordError');
        const confirmError = document.getElementById('confirmError');
        const form = document.getElementById('resetForm');

        passwordInput.addEventListener('keyup', validatePassword);
        confirmInput.addEventListener('keyup', validateConfirmPassword);

        function validatePassword() {
            const password = passwordInput.value;
            
            if (!password) {
                passwordError.textContent = 'Password cannot be empty';
            } else if (password.length < 8) {
                passwordError.textContent = 'Password must be at least 8 characters long';
            } else {
                passwordError.textContent = '';
            }
            
            // Also validate confirm if it has value
            if(confirmInput.value) {
                validateConfirmPassword();
            }
        }

        function validateConfirmPassword() {
            const password = passwordInput.value;
            const confirmPassword = confirmInput.value;
            
            if (!confirmPassword) {
                confirmError.textContent = 'Please confirm your password';
            } else if (password !== confirmPassword) {
                confirmError.textContent = 'Passwords do not match';
            } else {
                confirmError.textContent = '';
            }
        }

        form.addEventListener('submit', function(event) {
            validatePassword();
            validateConfirmPassword();

            const errors = document.querySelectorAll('.error');
            let hasError = false;
            errors.forEach(error => {
                if(error.textContent !== '') hasError = true;
            });

            if (hasError) {
                event.preventDefault();
            }
        });
    </script>

    <style>
        .subtitle {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }
    </style>

</body>
</html>
