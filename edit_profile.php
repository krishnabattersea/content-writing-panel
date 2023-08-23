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

$userID = $_SESSION["user_id"];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newUsername = $_POST["new_username"];
    $newEmail = $_POST["new_email"];

    // Update user profile
    $updateQuery = "UPDATE users SET username = '$newUsername', email = '$newEmail' WHERE id = '$userID'";
    if ($conn->query($updateQuery) === TRUE) {
        $_SESSION["username"] = $newUsername; // Update session username
        header("Location: edit_profile.php?success=1");
        exit();
    } else {
        $errorMessage = "Error updating profile: " . $conn->error;
    }
}

// Fetch user data for pre-filling the form
$sql = "SELECT username, email FROM users WHERE user_id = '$userID'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $userData = $result->fetch_assoc();
} else {
    $errorMessage = "User data not found.";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Profile</title>
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

        /* Form styles */
        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        button[type="submit"] {
            padding: 8px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Link styles */
        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Message styles */
        p {
            margin-bottom: 10px;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Profile</h2>
        <?php if (isset($errorMessage)): ?>
            <p>
                <?php echo $errorMessage; ?>
            </p>
        <?php elseif (isset($_GET["success"])): ?>
            <p>Your profile has been updated successfully.</p>
        <?php endif; ?>
        <form method="post">
            <label for="new_username">New Username:</label>
            <input type="text" id="new_username" name="new_username" value="<?php echo $userData["username"]; ?>"
                required><br>
            <label for="new_email">New Email:</label>
            <input type="email" id="new_email" name="new_email" value="<?php echo $userData["email"]; ?>" required><br>
            <button type="submit">Update Profile</button>
        </form>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>

</html>