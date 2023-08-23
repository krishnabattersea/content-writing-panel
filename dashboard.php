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
// Start session and check user role
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php"); // Redirect unauthorized users to login page
    exit();
}



?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
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

        h3 {
            font-size: 18px;
            margin-top: 15px;
            color: #333;
        }

        /* List styles */
        ul {
            list-style: none;
            margin-left: 20px;
        }

        li {
            margin-bottom: 10px;
        }

        /* Link styles */
        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Logout link style */
        p {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Welcome,
            <?php echo $_SESSION["username"]; ?>!
        </h2>
        <?php if ($_SESSION["role"] === "admin"): ?>
            <h3>Admin Actions</h3>
            <ul>
                <li><a href="admin_users.php">Manage Users</a></li>
                <li><a href="admin_publish_articles.php">Publish Articles</a></li>
                <li><a href="admin_all_users.php">View All Users</a></li>
            </ul>
        <?php endif; ?>

        <h3>User Actions</h3>
        <ul>
            <li><a href="article_management.php">Manage Articles</a></li>
            <li><a href="create_article.php">Create Article</a></li>
            <li><a href="published_articles.php">Published Articles</a></li>
            <li><a href="edit_profile.php">Edit Profile</a></li>
        </ul>

        <p><a href="logout.php">Logout</a></p>
    </div>
</body>

</html>