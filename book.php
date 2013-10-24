<?php
session_start();
$title = 'Books comments';
$heading = 'Comments';
include './include/header.php';
include './include/functions.php';
order_main_menu();
?>
<a href ="index.php">Home</a>
<?php if($_SESSION['is_logged']){?>
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
    $validator = true;
    $my_choices = $_POST['books'];
    $title = $_POST['comment'];
    $title = mysqli_real_escape_string($connection, trim($title));
    $sql = "INSERT INTO comments(comment,date) VALUES ('$title',NOW())";
    insert_user_data($my_choices, $title, $sql, $validator);
      description();
    display_comments();
      ?>
</form>
<?php
}
else {
    description();
    display_comments();
}
include './include/footer.php';

