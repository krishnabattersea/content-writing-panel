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
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
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

// Check if user ID is provided in the URL
if (!isset($_GET["user_id"])) {
    header("Location: admin_users.php"); // Redirect to user management page if ID is not provided
    exit();
}

// Get user ID from the URL
$userID = $_GET["user_id"];

// Fetch user data based on the provided ID
$sql = "SELECT * FROM users WHERE user_id = $userID";
$result = $conn->query($sql);

$userData = $result->fetch_assoc();

if (!$userData) {
    header("Location: admin_users.php"); // Redirect if user does not exist
    exit();
}

// Update user data if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newUsername = $_POST["username"];
    $newEmail = $_POST["email"];
    $newRole = $_POST["role"];
    // Update user information in the database
    $updateQuery = "UPDATE users SET username = '$newUsername', email = '$newEmail', role = '$newRole' WHERE user_id = $userID";

    if ($conn->query($updateQuery) === TRUE) {
        header("Location: admin_users.php"); // Redirect to user management page after successful update
        exit();
    } else {
        $errorMessage = "Error updating user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit User</title>
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
            max-width: 400px;
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
            font-weight: bold;
        }

        /* Input styles */
        input[type="text"],
        input[type="email"] {
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

        /* Error message styles */
        .error-message {
            color: red;
            margin-top: 10px;
        }

        /* Back link styles */
        .back-link {
            display: block;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit User</h2>
        <?php if (isset($errorMessage)): ?>
            <p style="color: red;">
                <?php echo $errorMessage; ?>
            </p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $userData["username"]; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $userData["email"]; ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name="role">
                    <option value="user" <?php if ($userData["role"] === "user")
                        echo "selected"; ?>>User</option>
                    <option value="admin" <?php if ($userData["role"] === "admin")
                        echo "selected"; ?>>Admin</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="Update User">
            </div>
        </form>
        <a href="admin_users.php">Back to User Management</a>
    </div>
</body>

</html>