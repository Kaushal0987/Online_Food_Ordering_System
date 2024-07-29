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