<?php
session_start();
$title = 'Books comments';
$heading = 'Comments';
include './include/header.php';
include './include/functions.php';
order_main_menu();
?>
<a href ="index.php">Home</a>
<form method="POST">
    Title <input type="text" name ="title"/><br>
    Authors <select name="authors[]" multiple >
        <?php
select_menue_options()
        ?>
    </select><br>
    <input type="hidden" value="1"  name="isAddedBook">
    <input type="submit" value="Add"/>
    <?php
    //add();
    // TODO add() 
    ?>
</form>

<?php 
include './include/footer.php';

