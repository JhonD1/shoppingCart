<?php // Do not put any HTML above this line
session_start();
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = 'a8609e8d62c043243c4e201cbb342862'; 

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
        $_SESSION['error'] = "Email and password are required";
        header("Location: login.php");
        return;

    }elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: login.php");
        return;

    }else {
        $check = hash('md5', $salt.$_POST['pass']);
        if ( $check == hash('md5', $salt.'php123') ) {
            // Redirect the browser to game.php
            error_log("Login success ".$_POST['email']);
            $_SESSION['name'] = $_POST['email'];
            header("Location: index.php");
            return;

        } else {
            $_SESSION['error'] = "Incorrect Password";
            error_log("Login fail ".$_POST['email']." $check");;
            header("Location: login.php");
            return;
        }
    }
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Jhon Mariel Daroing 2c104bbf </title>
</head>
<body>
<div class="container">
<h1>Please log In</h1>
<?php
// $failure = isset($_SESSION['error']) ? $_SESSION['error'] : false;
// Note triple not equals and think how badly double
// not equals would work here...
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
<form method="POST">
User Name <input type="text" name="email"><br/>
Password <input type="text" name="pass"><br/>

<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>

</div>
</body>
