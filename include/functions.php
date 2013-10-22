<?php

error_reporting(E_ALL ^ E_NOTICE);

$connection = mysqli_connect('localhost', 'gatakka', 'qwerty', 'books');

if (!$connection) {
    echo 'Error establishing a DB connection';
}

function add_author() {
    global $connection;
    mysqli_set_charset($connection, 'utf8');
    if (isset($_GET['author'])) {
        $author_name = trim($_GET['author']);
        $author_name = mysqli_real_escape_string($connection, $author_name);
        $result = 'author_name';
        $sql = 'SELECT author_name FROM authors';
        $author_name = mysqli_real_escape_string($connection, $author_name);
        $message = "This author '" . $author_name . "' already exist ";
        check_for_duplicate($author_name, $result, $sql, $message);
        mysqli_query($connection, "INSERT INTO authors(author_name) VALUES('$author_name')");
        header('Location: add_author.php');
        exit(0);
    }
}

function check_for_duplicate($customer_choice, $result, $sql, $message) {
    //TODO: solve the function
    global $connection;
    mysqli_set_charset($connection, 'utf8');

    $query = mysqli_query($connection, $sql);
    while ($user = $query->fetch_assoc()) {
        if ($user[$result] === $customer_choice) {
            echo $message;
            exit();
        }
    }
}

function authors() {
    global $connection;
    mysqli_set_charset($connection, 'utf8');
    $sql = 'SELECT * FROM authors';
    $row = mysqli_query($connection, $sql);
    echo '<table><thead><th>Authors</th></thead>';
    while ($r = $row->fetch_assoc()) {
        ?>

        <tr onclick="location.href = 'index.php?author_id=<?php echo $r['author_id']; ?>'" >
            <td><?php echo $r['author_name']; ?></td>
        </tr>
        <?php
    }
    echo '</table>';
}

function select_menue_options($sql, $id, $value) {
    global $connection;
    mysqli_set_charset($connection, 'utf8');
    $row = mysqli_query($connection, $sql);
    while ($r = $row->fetch_assoc()) {
        echo "<option value =" . $r[$id] . ">" . $r[$value] . '</option>';
    }
}

function insert_user_data($my_choices, $title, $sql, $validator) {
    global $connection;

    mysqli_set_charset($connection, 'utf8');
    if ($_POST) {
        $user = $_SESSION['user_id'];
        mysqli_query($connection, $sql);
        $book_id = mysqli_insert_id($connection);
        if (!$validator) {
            foreach ($my_choices as $author) {
                $sql2 = 'INSERT INTO `books_authors`(`book_id`,`author_id`) VALUES("' . (int) $book_id . '","' . (int) $author . '")';
                mysqli_query($connection, $sql2);
            }
        } else {

            foreach ($my_choices as $author) {
                $sql2 = 'INSERT INTO `users_comments`(`comment_id`,`user_id`,`book_id`) VALUES("' . (int) $book_id . '","' . $user . '","' . $author . '")';
                mysqli_query($connection, $sql2);
            }
        }
    }
}

function printAllBooks() {
    ?> 
    <?php
    sort_options($filter1 = 'Bsort=ON', $filter2 = 'Asort=ON');

    global $connection;
    mysqli_set_charset($connection, 'utf8');
    if ($_GET['Bsort'] === 'ON') {
        $sql = 'SELECT * FROM books ';
    } else {
        $sql = 'SELECT * FROM books order by book_title desc';
    }
    $books = mysqli_query($connection, $sql);
    ?>
    <table border= "4">
        <?php
        while ($row = $books->fetch_assoc()) {
            ?>
            <tr>
                <td ><a href= "book.php?book_id=<?php echo $row['book_id'] ?>"> <?php echo $row['book_title']; ?></a></td>
                <td>
                    <?php
                    $sql = "SELECT *
                        FROM authors 
                        JOIN books_authors ON authors.author_id=books_authors.author_id 
                        WHERE books_authors.book_id =" . $row['book_id'];

                    $authors = mysqli_query($connection, $sql);

                    while ($author = mysqli_fetch_assoc($authors)) {
                        ?>
                        <a href= "index.php?author_id=<?php echo $author['author_id']; ?>">
                            <?php
                            echo $author['author_name'] . '/ ';
                            ?></a><?php
                        }
                        ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
}

function printAllAuthors() {
    sort_options($filter1 = 'Bsort=ON', $filter2 = 'Asort=ON');
    global $connection;
    $check = false;
    mysqli_set_charset($connection, 'utf8');
    if ($_GET['author_id']) {

        if ($_GET['Bsort'] === 'ON') {
            $sql = 'SELECT * FROM books JOIN books_authors on books.book_id = books_authors.book_id WHERE books_authors.author_id =' . $_GET['author_id'] . ' ORDER BY book_title DESC';
        } else {
            $sql = 'SELECT * FROM books JOIN books_authors on books.book_id = books_authors.book_id WHERE books_authors.author_id =' . $_GET['author_id'];
        }
        $books = mysqli_query($connection, $sql);
        ?>
        <table border="4">
            <?php
            while ($row = mysqli_fetch_assoc($books)) {
                echo mysqli_error($connection);
                ?>


                <tr><td><a href= "book.php?book_id=<?php echo $row['book_id'] ?>"> <?php echo $row['book_title']; ?></a>

                    </td>
                    <?php
                    $sql = 'SELECT books.book_title,books.book_id, authors.author_name, authors.author_id
                    FROM books
                    LEFT JOIN books_authors
                    ON books.book_id = books_authors.book_id
                    LEFT JOIN authors 
                    ON authors.author_id = books_authors.author_id
                    WHERE books.book_title in 
                                           (SELECT books.book_title
                                            FROM books
                                            LEFT JOIN books_authors 
                                            ON books.book_id = books_authors.book_id
                                            LEFT JOIN authors
                                            ON authors.author_id = books_authors.author_id
                                            WHERE authors.author_id=' . $_GET['author_id'] . ')';

                    $authors = mysqli_query($connection, $sql);
                    echo '<td>';
                    while ($r = mysqli_fetch_assoc($authors)) {
                        if ($row['book_title'] === $r['book_title']) {

                            echo '<a href="index.php?author_id=' . (int) $r['author_id'] . '">' . $r['author_name'] . '/ ';
                            echo '</a>';
                        }
                    }
                }
                ?>
                </td></tr>
            <?php
        }
        ?>
    </table>
    <?php
}

function print_content() {
    if (!$_GET['author_id']) {
        printAllBooks();
    } else {
        printAllAuthors();
    }
}

function sort_options($filter1, $filter2) {
    ?>
    Sort by<div><a href ="index.php?author_id=<?php echo $_GET['author_id'] ?>&<?php echo $filter1; ?>">Books</a>
        <a href ="index.php?author_id=<?php echo $_GET['author_id'] ?>&<?php echo $filter2; ?>">Authors</a></div>
    <?php
}

function registration($connection) {
    if ($_POST) {
        if (!$connection) {
            echo 'Error';
            exit;
        } else {

            //Validate's the  user input
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $username_esc = mysqli_real_escape_string($connection, $username);
            $password_esc = mysqli_real_escape_string($connection, $password);
        }

        //Check if the username and the password matches the requirements
        if (mb_strlen($username_esc) >= 3 && mb_strlen($password_esc) >= 3 && !(int) $username_esc) {

            ///Check if the username is available
            $query = mysqli_query($connection, 'SELECT username FROM users');
            while ($user = $query->fetch_assoc()) {
                if ($user['username'] === $username_esc) {
                    echo $username . " already exist, please try with a different username";
                    exit();
                }
            }

            $sql = 'INSERT INTO users (username,password)VALUES("' . $username_esc . '","' . $password_esc . '")';
            $insert = mysqli_query($connection, $sql);
            header('Location: login.php');
            $_SESSION['user'] = $username_esc;
            $_SESSION['is_registered'] = true;
            exit;
        } else {
            echo 'Invalid username or password';
        }
    }
}

function welcome_user() {

    if ($_SESSION['is_logged']) {
        echo '<h3>Welcome, ' . $_SESSION['user'] . '</h3><br>';
        return true;
    }
    return false;
}

function order_main_menu() {
    if ($_SESSION['is_logged']) {
        ?>
        <div><a href ="logout.php">Logout</a></div>
        <?php
    } else {
        ?>
        <div><a href ="registration.php">Register</a></div>
        <div><a href="login.php">Login</a></div>
        <?php
    }
}

function login() {
    global $connection;
    mysqli_set_charset($connection, 'utf8');
    if (welcome_user()) {
        header('Location: index.php');
        exit;
    }
    if ($_POST) {
        if (!$connection) {
            echo 'Error';
            exit;
        } else {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $username = mysqli_real_escape_string($connection, $username);
            $password = mysqli_real_escape_string($connection, $password);

            $sql = "SELECT * FROM users";

            $validate_credentials = mysqli_query($connection, $sql);
            while ($row = $validate_credentials->fetch_assoc()) {
                if ($row['username'] === $username && $row['password'] === $password) {
                    echo 'Success';
                    $_SESSION['is_logged'] = true;
                    $_SESSION['user'] = $username;
                    $_SESSION['user_id'] = $row['user_id'];
                    header('Location: index.php');
                    exit;
                }
            }
            echo '<p>Invalid username or password<p>';
        }
    }
}

function display_comments() {

    global $connection;
    mysqli_set_charset($connection, 'utf8');

    $sql = "SELECT  comments.`comment`,comments.date,users.username
            FROM comments 
            JOIN users_comments  ON users_comments.comment_id = comments.comment_id
            JOIN users ON users.user_id = users_comments.user_id
            WHERE users_comments.book_id =" . $_GET['book_id'] . " group by comments.`comment` ORDER by date DESC";
    printAllBooks();
    $book_info = mysqli_query($connection, $sql);
    ?>  <table = border = "4"><thead><th>Comments</th><th>User</th><th>Date</th></thead>
    <?php
    while ($row = $book_info->fetch_assoc()) {
        ?>
        <tr><td><?php echo $row['comment']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['date']; ?></td>
            <?php
        }
        ?>
    </tr>
    </table>

    <?php
}
