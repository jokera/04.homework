<?php
session_start();
$title = 'Welcome';
$heading = 'Home page';
include './include/header.php';
include './include/functions.php';
welcome_user();
?>
<div>
    <a href ="add_book.php">Add new book </a>
    <a href ="add_author.php"> Add new author</a></div>
<?php
order_main_menu();
print_content();
?>   

<p></p>
<?php
include './include/footer.php';
