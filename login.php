<?php

include('config/constants.php');

if (isset($_POST['signIn'])) {
    $email = trim($_POST['email']);
    $password = md5($_POST['password']);

    try {
        // Get users collection
        $collection = $conn->selectCollection('users');

        // Find user with matching email and password
        $user = $collection->findOne([
            'email' => $email,
            'password' => $password
        ]);

        if ($user) {
            session_start();
            $uID = mongoIdToString($user['_id']);
            $_SESSION['uID'] = $uID;
            $_SESSION['login-status'] = true;
            header('location:' . SITEURL);
            exit();
        } else {
            session_start();
            $_SESSION['login-error'] = "Incorrect Email or Password";
        }
    } catch (Exception $e) {
        session_start();
        $_SESSION['login-error'] = "Login Error: " . $e->getMessage();
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MixiFoods</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo SITEURL; ?>CSS/style-user.css">
</head>

<body>

    <!-- Background Image Container -->
    <div class="login-bg-container"></div>

    <div class="login-wrapper center-layout">
        <div class="login-card centered-card">
            <div class="login-form-container">
                <div class="logo">
                    <i class="fas fa-utensils"></i> Wow Foods
                </div>

                <h2>Login</h2>

                <?php
                if (isset($_SESSION['login-error'])) {
                    echo '<div class="error-message">' . $_SESSION['login-error'] . '</div>';
                    unset($_SESSION['login-error']);
                }

                if (isset($_SESSION['login-success'])) {
                    echo '<div class="success-message">' . $_SESSION['login-success'] . '</div>';
                    unset($_SESSION['login-success']);
                }
                ?>

                <form method="post" action="#" name="signIn" novalidate>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" placeholder="" required>
                        <div class="error" id="emailError"></div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="" required>
                        <div class="error" id="passwordError"></div>
                    </div>

                    <button type="submit" class="login-btn" name="signIn">LOGIN</button>

                    <div class="form-footer">
                        <a href="<?php echo SITEURL; ?>forgot-password.php" class="forgot-link">Forgot Password?</a>
                        <a href="<?php echo SITEURL; ?>register.php">Create Account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('email').addEventListener('keyup', validateEmail);
        document.getElementById('password').addEventListener('keyup', validatePassword);

        function validateEmail() {
            const email = document.getElementById('email').value;
            const emailError = document.getElementById('emailError');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!email) {
                emailError.textContent = 'Email cannot be empty';
            } else if (!emailPattern.test(email)) {
                emailError.textContent = 'Please enter a valid email address';
            } else {
                emailError.textContent = '';
            }
        }

        function validatePassword() {
            const password = document.getElementById('password').value;
            const passwordError = document.getElementById('passwordError');

            if (!password) {
                passwordError.textContent = 'Password cannot be empty';
            } else if (password.length < 8) {
                passwordError.textContent = 'Password must be at least 8 characters long';
            } else {
                passwordError.textContent = '';
            }
        }

        document.querySelector('form[name="signIn"]').addEventListener('submit', function (event) {
            validateEmail();
            validatePassword();

            const errors = document.querySelectorAll('.error');
            let hasError = false;
            errors.forEach(error => {
                if (error.textContent !== '') hasError = true;
            });

            if (hasError) {
                event.preventDefault();
            }
        });
    </script>
</body>

</html>