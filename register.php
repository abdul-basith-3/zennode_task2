<?php
session_start();
include("database_conn.php");
// Server-side validation
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $confirm_password = $_POST["confirm_password"];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Add more validation if necessary
    if (empty($errors)) {
        // Hash password
        $hashed_password = $password; //password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION["username"] = $username;
            echo $_POST["password"];
            header("Location: dashboard.php");
            exit();
        } else {
            $errors[] = "Registration failed.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <div class="main__container">
        <div class="form__container">
            <h1>Register</h1>
            <?php if (!empty($errors)) : ?>
                <div class="error">
                    <?php foreach ($errors as $error) : ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="register.php" method="post" id="form">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" required><br>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br>
                <label for="confirm_password">Confirm Password:</label><br>
                <input type="password" id="confirm_password" name="confirm_password" required><br>
                <input type="submit" value="Register">
            </form>
            <div class="main__sub">
                <p>already have an account? <a href="login.html">login here</a></p>
            </div>

        </div>
</body>

</html>