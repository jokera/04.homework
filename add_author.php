<?php 
session_start();
$title = 'Create new author';
$heading = 'Add author'; 
include './include/header.php';
include './include/functions.php';
echo '<a href="index.php">Home page</a>';
authors();
add_author();
?>
<form method="GET" action="add_author.php">
    Author<input type ="text" name ="author"/>
    <div><input type ="submit" value ="Add"></div>
</form><br>

<?php 
include './include/footer.php';
