<?php // Do not put any HTML above this line
session_start();

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1'; //pswd php123

// Create "open" database access
$pdo = new PDO('mysql:host=localhost; port=8889; dbname=misc',
    'fred', 'zap');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function loginError($errormessage) {
    $_SESSION['error'] = $errormessage;
    header("Location: login.php");
}

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        loginError("Email and password are required");
        return;
    } else {
        if ( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
            loginError("Email must have an at-sign (@)");
            return;
        } else {
            $check = hash('md5', $salt . $_POST['pass']);
            if ( $check == $stored_hash ) {
                error_log("Login success " . $_POST['email']);
                $_SESSION['name'] = $_POST['email'];
                header("Location: view.php");
                return;
            } else {
                error_log("Login fail " . $_POST['email'] . " $check");
                loginError("Incorrect password");
                return;
            }
        }
    }
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
    <head>
    <title>Jere Tofferi Autos Database</title>
</head>
<body>
    <div class="container">
        <h1>Please Log In</h1>
        <?php
            if ( isset($_SESSION['error']) ) {
                echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
                unset($_SESSION['error']);
            }
        ?>
        <form method="POST">
            <label for="em">Email</label>
            <input type="text" name="email" id="em"><br/>
            <label for="id_1723">Password</label>
            <input type="password" name="pass" id="id_1723"><br/>
            <input type="submit" value="Log In">
            <input type="submit" name="cancel" value="Cancel">
        </form>
    </div>
</body>
