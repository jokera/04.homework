<?php
// TASKS: 
// Check if the book already exists
// check the author if alredy exists 
// Create book page 



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
        mysqli_query($connection, "INSERT INTO authors(author_name) VALUES('$author_name')");
        header('Location: add_author.php');
        exit(0);
    }
}

//Prints author names
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

// select multiple authors and add a book

function add_books() {
    global $connection;
    mysqli_set_charset($connection, 'utf8');
    $sql = "SELECT * FROM authors";
    $row = mysqli_query($connection, $sql);
    while ($r = $row->fetch_assoc()) {
        echo "<option value =" . $r['author_id'] . ">" . $r['author_name'] . '</option>';
    }
}

function add() {
    global $connection;
    mysqli_set_charset($connection, 'utf8');
    if ($_POST) {
        $my_choices = $_POST['authors'];
        $title = $_POST['title'];
        $sql = "INSERT INTO books(book_title) VALUES ('$title')";
        mysqli_query($connection, $sql);
        $book_id = mysqli_insert_id($connection);
        foreach ($my_choices as $author) {
            mysqli_query($connection, 'INSERT INTO `books_authors`(`book_id`,`author_id`) VALUES("' . (int) $book_id . '","' . (int) $author . '")');
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


    while ($row = $books->fetch_assoc()) {
        ?>
        <tr>
            <td ><?php echo $row['book_title']; ?></td>
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
                        echo $author['author_name'] . ', ';
                        ?></a><?php
                }
                ?>
            </td>
        </tr>
        <?php
    }
}

function printAllAuthors() {
    sort_options($filter1 = 'Bsort=ON', $filter2 = 'Asort=ON');
    global $connection;
    $check = false;
    mysqli_set_charset($connection, 'utf8');
    if ($_GET['author_id']) {

        if ($_GET['Bsort'] === 'ON') {
            $sql = 'SELECT book_title FROM books JOIN books_authors on books.book_id = books_authors.book_id WHERE books_authors.author_id =' . $_GET['author_id'] . ' ORDER BY book_title DESC';
        } else {
            $sql = 'SELECT book_title FROM books JOIN books_authors on books.book_id = books_authors.book_id WHERE books_authors.author_id =' . $_GET['author_id'];
        }
        $books = mysqli_query($connection, $sql);
        while ($row = mysqli_fetch_assoc($books)) {
            echo mysqli_error($connection);
            echo '<tr><td>' . $row['book_title'];

            echo '</td>';

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

                    echo '<a href="index.php?author_id=' . (int) $r['author_id'] . '">' . $r['author_name'] . ', ';
                    echo '</a>';
                }
            }
        }
        ?>
        </td></tr>
        <?php
    }
}

// Create's the URL for sorting
function print_content() {
    if (!$_GET['author_id']) {
        printAllBooks();
    } else {
        printAllAuthors();
    }
}

function sort_options($filter1, $filter2) {
    ?>
    <th><a href ="index.php?author_id=<?php echo $_GET['author_id'] ?>&<?php echo $filter1; ?>">Books</a></th>
    <th><a href ="index.php?author_id=<?php echo $_GET['author_id'] ?>&<?php echo $filter2; ?>">Authors</a></th>
    </thead>
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
            echo 'Successful registration';
            $_SESSION['is_logged'] = true;
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

              $sql = "SELECT username,password FROM users where username = '".$username."' AND password ='". $password."'";
           
            $validate_credentials = mysqli_query($connection, $sql);
            while ($row = $validate_credentials->fetch_assoc()) {
                if ($row['username'] === $username && $row['password'] === $password) {       
                    echo 'Success';
                    $_SESSION['is_logged'] = true;
                    $_SESSION['user'] = $username;
                    header('Location: index.php');
                    exit;
                } 
            }
             echo '<p>Invalid username or password<p>'; 
        }
    }
}
