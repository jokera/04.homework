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
    Comment <input type="text" name ="comment"/><br>
    Books <select name="books[]" multiple >
        <?php
        $id = 'book_id';
        $value = 'book_title';
        $sql = 'SELECT * FROM books';
        select_menue_options($sql, $id, $value);
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

