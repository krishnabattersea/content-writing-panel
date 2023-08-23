
<?php
 
 include 'index.php';




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
$title = $content = $published = $errorMessage = "";

// Create a new article if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $published = $_POST["published"];
    $categoryID = $_POST["category"];                              
    $userID = $_SESSION["user_id"];

    // Insert article information into the database
    $insertQuery = "INSERT INTO articles (user_id, category_id, title, content, published) VALUES ('$userID', '$categoryID', '$title', '$content', '$published')";
   $conditon= $conn->query($insertQuery);



   if ($conditon) {
   
        
             $valid= $_POST["category"]== 1;
            
            if ($valid) {
        
                echo "<script>pushNotify();</script>";
            }
            else{
            header("Location: article_management.php"); // Redirect to article management page after successful create
                
            }
        

    } else {
        $errorMessage = "Error creating article: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Article</title>
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
        <h2>Create Article</h2>
        <?php if (isset($errorMessage)): ?>
            <p style="color: red;">
                <?php echo $errorMessage; ?>
            </p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo $title; ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea id="content" name="content" rows="6" required><?php echo $content; ?></textarea>
            </div>

            <div class="form-group">
                <label for="status">Category:</label>
                <select id="category" name="category">
                    <option value="0">Select One</option>
                    <?php
            $categoryQuery = "SELECT category_id, category_name FROM categories";
            $categoryResult = $conn->query($categoryQuery);
            while ($row = $categoryResult->fetch_assoc()) {
                echo '<option value="' . $row["category_id"] . '">' . $row["category_name"] . '</option>';
            }
        ?>

                </select>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="published" name="published">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="Create Article">
            </div>
        </form>
        <a href="article_management.php" class="link-button">Back to Article Management</a>
        <a href="dashboard.php" class="link-button">Dashboard</a>
    </div>
</body>

</html>