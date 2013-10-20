<?php
session_start();
$title = 'Add a book';
$heading = 'Add new book';
include './include/header.php';
include './include/functions.php';
?>
<a href ="index.php">Books</a>
<form method="POST">
    Title <input type="text" name ="title"/><br>
    Authors <select name="authors[]" multiple >
        <?php
        $id = 'author_id';
        $value = 'author_name';
        $sql = 'SELECT * FROM authors';
        select_menue_options($sql, $id, $value);
        ?>
    </select><br>
    <input type="hidden" value="1"  name="isAddedBook">
    <input type="submit" value="Add"/>
    <?php
    $my_choices = $_POST['authors'];
    $title = $_POST['title'];
    $sql = "INSERT INTO books(book_title) VALUES ('$title')";
    insert_user_data($my_choices, $title, $sql);
    ?>
</form>
<?php
include './include/footer.php';
