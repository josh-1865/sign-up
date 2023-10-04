<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

// Establish a database connection
$db = new PDO("mysql:host=localhost;dbname=login_register", "root", "1234");

// Retrieve image details from the database
$sql = "SELECT image_path, description FROM images";
$stmt = $db->query($sql);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Image Gallery</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <style>
        .upto{
            display: flex;
            justify-content: space-around;
        }
    </style>

</head>

<body>
    <div class="upto">
        <h1>Image Gallery</h1>
        <a href="index.php" class="btn btn-info">Home</a>
    </div>

    <?php foreach ($images as $image): ?>
        <div>
            <img src="<?php echo $image['image_path']; ?>" alt="Image">
            <p><?php echo $image['description']; ?></p>
        </div>
    <?php endforeach; ?>
</body>

</html>
