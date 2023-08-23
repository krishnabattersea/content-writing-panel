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
// Start session and check user role
session_start();
if (!isset($_SESSION["username"])) {
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

// Check if article ID is provided in the URL
if (!isset($_GET["article_id"])) {
    header("Location: article_management.php"); // Redirect to article management page if ID is not provided
    exit();
}

// Get article ID from the URL
$articleID = $_GET["article_id"];

// Fetch article data based on the provided ID and user ID
$userID = $_SESSION["user_id"];
$sql = "SELECT * FROM articles WHERE article_id = $articleID AND user_id = $userID";
$result = $conn->query($sql);

$articleData = $result->fetch_assoc();

if (!$articleData) {
    header("Location: article_management.php"); // Redirect if article does not exist or does not belong to the user
    exit();
}

// Update article data if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newTitle = $_POST["title"];
    $newContent = $_POST["content"];

    // Update article information in the database
    $updateQuery = "UPDATE articles SET title = '$newTitle', content = '$newContent' WHERE article_id = $articleID";

    if ($conn->query($updateQuery) === TRUE) {
        header("Location: article_management.php"); // Redirect to article management page after successful update
        exit();
    } else {
        $errorMessage = "Error updating article: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Article</title>
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

        /* Form group styles */
        .form-group {
            margin-bottom: 20px;
        }

        /* Label styles */
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        /* Input and textarea styles */
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 14px;
            resize: vertical;
        }

        /* Submit button styles */
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            padding: 10px 20px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Back link styles */
        a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Article</h2>
        <?php if (isset($errorMessage)): ?>
            <p style="color: red;">
                <?php echo $errorMessage; ?>
            </p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo $articleData["title"]; ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea id="content" name="content" rows="6"
                    required><?php echo $articleData["content"]; ?></textarea>
            </div>
            <div class="form-group">
                <input type="submit" value="Update Article">
            </div>
        </form>
        <a href="article_management.php">Back to Article Management</a>
    </div>
</body>

</html>