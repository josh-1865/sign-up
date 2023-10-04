<?php
session_start();
if (isset($_SESSION["user"])) {
  header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .error {color: #FF0000;}
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <?php
        // Define variables and set them to empty values
        $nameErr = $emailErr = $passwordErr = "";
        $name = $email = $password = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require_once "database.php";

            if (empty($_POST["name"])) {
                $nameErr = "Name is required";
            } else {
                $name = test_input($_POST["name"]);
                // Check if name only contains letters and whitespace
                if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                    $nameErr = "Only letters and white space allowed";
                }
            }

            if (empty($_POST["password"])) {
                $passwordErr = "Password is required";
            } else {
                $password = test_input($_POST["password"]);

                if (strlen($_POST["password"]) < 8) {
                    $passwordErr = "Your Password Must Contain At Least 8 Characters!";
                } elseif (!preg_match("#[0-9]+#", $password)) {
                    $passwordErr = "Your Password Must Contain At Least 1 Number!";
                } elseif (!preg_match("#[A-Z]+#", $password)) {
                    $passwordErr = "Your Password Must Contain At Least 1 Capital Letter!";
                } elseif (!preg_match("#[a-z]+#", $password)) {
                    $passwordErr = "Your Password Must Contain At Least 1 Lowercase Letter!";
                }
            }

            if (empty($_POST["email"])) {
                $emailErr = "Email is required";
            } else {
                $email = test_input($_POST["email"]);
                // Check if e-mail address is well-formed
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Invalid email format";
                }
            }

            // Check if the email already exists in the database
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $rowCount = mysqli_num_rows($result);
                if ($rowCount > 0) {
                    $emailErr = "Email already exists";
                } else {
                    // Hash the password securely using password_hash
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Insert the user into the database
                    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
                    $stmt = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($stmt, $sql)) {
                        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashedPassword);
                        mysqli_stmt_execute($stmt);
                        echo "<div class='alert alert-success'>You are Registered Successfully</div>";
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                }
            }
        }

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
    ?>

    <div class="container">
        <form action="signup.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="name" value="<?php echo $name;?>" placeholder="Full Name">
                <span class="error"><?php echo $nameErr; ?></span>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="email" value="<?php echo $email;?>" placeholder="Email">
                <span class="error"><?php echo $emailErr; ?></span>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" value="<?php echo $password;?>" placeholder="Password">
                <span class="error"><?php echo $passwordErr; ?></span>
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="SIGNUP" name="submit">
            </div>
        </form>
        <div><p>Already Have an Account <a href="login.php">Register Here</a></p></div>
    </div>
</body>
</html>
