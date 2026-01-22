<?php 

include('config/constants.php');

if(isset($_POST['signIn'])){
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

       if($user){
            session_start();
            $uID = mongoIdToString($user['_id']);
            $_SESSION['uID'] = $uID;
            $_SESSION['login-status'] = true;
            header('location:'.SITEURL);
            exit();
       }
       else{
        echo "Not Found, Incorrect Email or Password";
       }
   } catch (Exception $e) {
       echo "Login Error: " . $e->getMessage();
   }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="CSS/style-user.css">
</head>
<body>

    <div class="container" id="signIn">

      <h1 class="form-title">Sign In</h1>

      <form method="post" action="#" name="signIn" novalidate>

        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <label for="email">Email</label>
            <div class="error" id="emailError"></div>
        </div>

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <label for="password">Password</label>
            <div class="error" id="passwordError"></div>
        </div>

        <input type="submit" class="btn" value="SignIn" name="signIn">

      </form>

      <div class="links">
        <p>Don't have account yet?</p>
        <a href="<?php echo SITEURL; ?>register.php">Sign-up</a>
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

        document.querySelector('form[name="signIn"]').addEventListener('submit', function(event) {
            validateEmail();
            validatePassword();

            if (document.querySelector('.error').textContent !== '') {
                event.preventDefault(); // Prevent form submission if there are validation errors
            }
        });
    </script>
</body>
</html>
