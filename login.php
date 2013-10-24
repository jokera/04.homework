<?php
session_start();
$title = 'Login';
$heading = 'Sign in';
include './include/header.php';
include './include/functions.php';
if ($_SESSION['is_registered']) {
    echo '<p>Succesfull registration</p>';
}
?>
<form method="POST">
    <div><a href="index.php">Home page</a></div>
    <div><a href="registration.php">New registration</a></div>
    Username<div><input type ="text" name="username"/></div>
    Password<div><input type="password" name ="password"/></div>
    <div><input type="submit" value ="Log in"/></div>
    <?php
    login();
    ?>
</form>
<?php
include './include/footer.php';
?>
