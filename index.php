<?php
session_start();
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Image Upload Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="index.css" />
    <style>
        /* Custom CSS for dashboard layout */
        .dashboard-container {
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #333;
            color: #fff;
            padding: 20px;
			display: flex;
			flex-direction: column;
			gap:20px;
			height: 100vh;
        }

        .main-content {
			display: flex;
			justify-content: center;
			align-items: center;
			flex-direction: column;
            flex-grow: 1;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Sidebar</h2>
            <a href="logout.php" class="btn btn-info">Logout</a>
            <a href="profile.php" class="btn btn-info">Profile</a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1>Main Content</h1>
            <form method="POST" action="" enctype="multipart/form-data" id="upload-form">
                <div class="form-group">
                    <input class="form-control" type="file" name="uploadfile" id="uploadfile" />
                </div>
                <div class="form-group">
                    <input class="form-control" type="text" name="description" id="description" placeholder="Image Description" />
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="button" id="upload">UPLOAD</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#upload").click(function () {
                var formData = new FormData();
                formData.append("uploadfile", $("#uploadfile")[0].files[0]);
                formData.append("description", $("#description").val());

                $.ajax({
                    type: "POST",
                    url: "upload.php", // Create this PHP file to handle the upload
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        // Handle the response from the server (e.g., show a success message)
                        alert(response);
                    },
                    error: function (xhr, status, error) {
                        // Handle errors
                        alert("Error: " + error);
                    }
                });
            });
        });
    </script>
</body>

</html>
