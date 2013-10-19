<?php
session_start();
$title = 'New registration';
$heading = 'Registration';
include './include/header.php';
include './include/functions.php';
?>
<div><a href="index.php">Home page</a></div>
<div><a href="login.php">Login page</a></div>
<form method="POST">
    Username<div><input type ="text" name="username"/></div>
    Password<div><input type="password" name ="password"/></div>
    <div><input type="submit" value ="Registration"/></div>
    <?php registration($connection =  mysqli_connect('localhost', 'gatakka', 'qwerty', 'books'));
    ?>
</form>
<?php 
include './include/footer.php';

