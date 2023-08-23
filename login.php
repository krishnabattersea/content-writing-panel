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

// Start session
session_start();

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "content_writing_panel";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $inputUsername = $_POST["username"];
    $inputPassword = $_POST["password"];

    // Fetch user data from the database based on the entered username
    $sql = "SELECT user_id, username, role, password, email FROM users WHERE username = '$inputUsername'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($inputPassword, $user["password"])) {
            // Authentication successful, start session and redirect to a dashboard or home page
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["username"] = $inputUsername;
            if ($user["role"] == "admin") {
                $_SESSION["role"] = "admin";
            } else {
                $_SESSION["role"] = "user";
            }
            header("Location: dashboard.php"); // Replace with your desired page
            exit();
        } else {
            $errorMessage = "Invalid password";
        }
    } else {
        $errorMessage = "Invalid username";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Form container styles */
        .container {
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            /* Center the content within the container */
        }

        /* Heading styles */
        h2 {
            font-size: 24px;
            margin-bottom: 15px;
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
            font-weight: bold;
        }

        /* Input styles */
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
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

        /* Link button styles */
        .link-button {
            display: inline-block;
            padding: 10px 20px;
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

        /* Error message styles */
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($errorMessage)): ?>
            <p style="color: red;">
                <?php echo $errorMessage; ?>
            </p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Login">
                <a href="signup.php" class="link-button">Sign Up</a>
            </div>
        </form>
    </div>
</body>

</html>