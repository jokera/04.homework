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
    add();
    ?>
</form>
<?php
include './include/footer.php';
