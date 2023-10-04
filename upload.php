<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["uploadfile"]) && isset($_POST["description"])) {
    $uploadDir = "uploads/"; // Directory to store uploaded files

    // Ensure the directory exists; create it if necessary
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create directory recursively
    }

    $uploadedFile = $uploadDir . basename($_FILES["uploadfile"]["name"]);
    $description = $_POST["description"];

    // Check if the file has been uploaded successfully
    if (move_uploaded_file($_FILES["uploadfile"]["tmp_name"], $uploadedFile)) {
        // Insert image details into the database
        $db = new PDO("mysql:host=localhost;dbname=login_register", "root", "1234");
        $sql = "INSERT INTO images (image_path, description) VALUES (:image_path, :description)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":image_path", $uploadedFile);
        $stmt->bindParam(":description", $description);

        if ($stmt->execute()) {
            echo "Image uploaded and data inserted successfully.";
        } else {
            echo "Error inserting data into the database.";
        }
    } else {
        echo "Error uploading the file.";
    }
} else {
    echo "Invalid request.";
}
?>
