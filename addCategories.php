<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
// Start session and check user role
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

// Initialize variables
$Category= $errorMessage = "";

// Create a new article if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
   
    $Category = $_POST["category"];
    $Category_type=$_POST["category_type"];
//    echo $category_type;

    // Insert article information into the database
    $insertQuery = 'INSERT INTO categories (category_name, type_category) VALUES ("'.$Category.'", "'.$Category_type.'")';
    


    if ($conn->query($insertQuery) === TRUE) {
        header("Location: create_article.php");
  
        exit();
    } else {
        $errorMessage = "Error while adding a Category: " . $conn->error;
    }
}
?>



<!DOCTYPE html>
<html>

<head>

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

        /* Select styles */
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 14px;
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
        <h2>Add Article Category</h2>
        <?php if (isset($errorMessage)): ?>
            <p style="color: red;">
                <?php echo $errorMessage; ?>
            </p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="category">Category Name</label>
                <input type="text" id="Category" name="category" required>
            </div>

            <div class="form-group">
                <label for="category-type">Category type</label>
                <input type="text" id="Category_type"  name="category_type" required>
            </div>


            <div class="form-group">
                <input type="submit" value ="Add Category">
            </div>
        </form>
        
        <a href="dashboard.php" class="link-button">Dashboard</a>
    </div>
</body>

</html>