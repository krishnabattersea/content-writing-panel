<!--
    The database 'content_writing_panel' and the tables 'articles' and 'users'
    have been created on the local MySQL server using phpMyAdmin(Xammp)
    The users table structure is ->
    Column Name  |	    Data Type	            |  Description
    user_id              |  	INT (Primary Key)	|   Unique identifier for the user
    username          |	    VARCHAR(50)	        |   User's chosen username
    email                 |     VARCHAR(100)        |	User's email address
    password           |   VARCHAR(255)	         |   Hashed password
    role	                |   ENUM ('user', 'admin')	|   User role (normal user or admin)

and the articles table structure is ->
    Column Name     |	Data Type                                 |	Description
    article_id              |      	INT (Primary Key)                 |	Unique identifier for the article
    user_id                 |	INT	                                          |   Foreign key referencing users table
    title                      |   	VARCHAR(255)                        | 	Title of the article
    content                 |	TEXT                                         |	Content of the article
    published              |	ENUM('published', 'draft')	      |    Whether the article is published or not
    created_at             |	DATETIME                                |	Timestamp of article creation
    updated_at            |	DATETIME                                   |	Timestamp of last article update
-->
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Start session and check admin role
session_start();
if (!isset($_SESSION["username"]) ) {
    header("Location: login.php"); // Redirect unauthorized users to login page
    exit();
}

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "content_writing_panel";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch published articles with user information
$sql = "SELECT articles.article_id AS article_id, articles.title AS article_title, articles.content AS article_content, users.user_id AS user_id, users.username AS username
        FROM articles
        INNER JOIN users ON articles.user_id = users.user_id
        WHERE articles.published = 'published'";
$result = $conn->query($sql);

$publishedArticles = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $publishedArticles[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Published Articles (user based)</title>
    <style>
        /* Reset default margin and padding */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
}

/* Container styles */
.container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Heading styles */
h2 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #333;
}

/* Table styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table,
th,
td {
    border: 1px solid #ccc;
}

th,
td {
    padding: 10px;
    text-align: left;
}

/* Message styles */
p {
    margin-bottom: 10px;
    color: #333;
}

/* Link styles */
a {
    text-decoration: none;
    color: #007bff;
}

a:hover {
    text-decoration: underline;
}

/* Delete link button styles */
.delete-button {
    background-color: #ff3333;
    color: #fff;
    padding: 5px 10px;
    border: none;
    border-radius: 3px;
    text-decoration: none;
    cursor: pointer;
}

.delete-button:hover {
    background-color: #cc0000;
}
/* Link button styles */
.link-button {
            display: inline-block;
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            text-decoration: none;
            cursor: pointer;
        }

        .link-button:hover {
            background-color: #0056b3;
        }

    </style>
</head>

<body>
    <div class="container">
        <h2>Published Articles</h2>
        <?php if (empty($publishedArticles)) : ?>
            <p>No published articles found.</p>
        <?php else : ?>
            <table>
                <tr>
                    <th>User</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($publishedArticles as $article): ?>
                    <tr>
                        <td><?php echo $article["username"]; ?></td>
                        <td><?php echo $article["article_title"]; ?></td>
                        <td><?php echo $article["article_content"]; ?></td>
                        <td>
                            <a href="edit_article.php?id=<?php echo $article["article_id"]; ?>">Edit</a>
                            <a href="delete_article.php?id=<?php echo $article["article_id"]; ?>"
                                onclick="return confirm('Are you sure you want to delete this article?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        <a href="dashboard.php" class="link-button">Dashboard</a>
    </div>
</body>

</html>
