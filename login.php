<?php
session_start();
include('db.php');

$error = "";

// Already logged in? Go straight to the dashboard
if (isset($_SESSION['admin'])) {
    header("Location: admin/dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username == "" || $password == "") {
        $error = "Please enter your username and password.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM admins WHERE username = ? AND password = SHA2(?, 256)");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            session_regenerate_id(true);
            $_SESSION['admin'] = $username;
            header("Location: admin/dashboard.php");
            exit();
        } else {
            $error = "Wrong username or password.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin login</title>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;800&family=Figtree:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <main class="auth-card">
        <h1>Admin login</h1>
        <p class="muted">Sign in to manage projects and read messages.</p>

        <?php if ($error != ""): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-block">Sign in</button>
        </form>
        <p class="auth-back"><a href="index.php">Back to portfolio</a></p>
    </main>
</body>
</html>
