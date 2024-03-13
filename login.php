<?php
session_start();
include("database_conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if ($password == $user["password"]) {
        $_SESSION["username"] = $username;
        header('Location: dashboard.php');
        exit();
    } else {
        echo "<span class='errorMsg'>Invalid login credentials.</span>";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./assets/style.css">
</head>

<body>
    <div class="main__container">
        <div class="form__container">
            <h1>Login</h1>
            <?php if (isset($error)) : ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="form">
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" required><br>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br>
                <input type="submit" value="Login">
            </form>
            <div class="main__sub">
                <p>don't have an account? <a href="register.html">register here</a></p>
            </div>
        </div>
    </div>
</body>

</html>