<?php 
  include('config/constants.php');

  if(isset($_POST['signUp'])){
      $userName=$_POST['username'];
      $email=$_POST['email'];
      $address=$_POST['address'];
      $password=$_POST['password'];
      $password=md5($password);

      $checkEmail="SELECT * From tbl_users where email='$email'";
      $result=$conn->query($checkEmail);
      if($result->num_rows>0){
          $_SESSION['signup-error'] = "Error Signning Up";
          echo "Email Address Already Exists !";
      }
      else{
          $insertQuery="INSERT INTO tbl_users(username,email, address, password)
                        VALUES ('$userName','$email', '$address', '$password')";
              if($conn->query($insertQuery)==TRUE){
                  header("location: login.php");
              }
              else{
                  $_SESSION['signup-error'] = "Error Signning Up";
                  header("location: signup.php");
                  echo "Error:".$conn->error;
              }
      }
    

  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="CSS/style-user.css">
</head>
<body>
    <div class="container" id="signup">

      <h1 class="form-title">Register</h1>

      <form method="post" action="#" name="signUp" novalidate>

        <div class="input-group">
           <i class="fas fa-user"></i>
           <input type="text" name="username" id="username" placeholder="User Name" required>
           <label for="username">Username</label>
           <div class="error" id="usernameError"></div>
        </div>
        
        <div class="input-group">
          <i class="fas fa-envelope"></i>
          <input type="email" name="email" id="email" placeholder="Email" required>
          <label for="email">Email</label>
          <div class="error" id="emailError"></div>
        </div>

        <div class="input-group">
            <i class="fa-solid fa-address-book"></i>
            <input type="text" name="address" id="address" placeholder="Address" required>
            <label for="address">Address</label>
            <div class="error" id="addressError"></div>
        </div>

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <label for="password">Password</label>
            <div class="error" id="passwordError"></div>
        </div>

       <input type="submit" class="btn" value="SignUp" name="signUp">

      </form>

      <div class="links">
        <p>Already Have Account ?</p>
        <a href="<?php echo SITEURL; ?>login.php">Sign-in</a>
      </div>

    </div>

    <script>
        document.getElementById('username').addEventListener('keyup', validateUsername);
        document.getElementById('email').addEventListener('keyup', validateEmail);
        document.getElementById('address').addEventListener('keyup', validateAddress);
        document.getElementById('password').addEventListener('keyup', validatePassword);

        function validateUsername() {
            const username = document.getElementById('username').value;
            const usernameError = document.getElementById('usernameError');
            const usernamePattern = /^[a-zA-Z0-9_]+$/;

            if (!username) {
                usernameError.textContent = 'Username cannot be empty';
            } else if (!usernamePattern.test(username)) {
                usernameError.textContent = 'Username cannot contain special characters';
            } else {
                usernameError.textContent = '';
            }
        }

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

        function validateAddress() {
            const address = document.getElementById('address').value;
            const addressError = document.getElementById('addressError');
            const addressPattern = /^[a-zA-Z0-9\s,'-]*$/;

            if (!address) {
                addressError.textContent = 'Address cannot be empty';
            } else if (!addressPattern.test(address)) {
                addressError.textContent = 'Address contains invalid characters';
            } else {
                addressError.textContent = '';
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

        document.querySelector('form[name="signUp"]').addEventListener('submit', function(event) {
            validateUsername();
            validateEmail();
            validateAddress();
            validatePassword();

            if (document.querySelector('.error').textContent !== '') {
                event.preventDefault(); // Prevent form submission if there are validation errors
            }
        });
    </script>
</body>
</html>