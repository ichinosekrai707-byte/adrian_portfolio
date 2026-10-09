<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: ../login.php"); exit(); }
include('../db.php');

$messages = $conn->query("SELECT * FROM messages ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Messages | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;800&family=Figtree:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header class="admin-bar">
        <div class="wrap bar">
            <a class="brand" href="dashboard.php">Admin</a>
            <nav>
                <a href="dashboard.php">Projects</a>
                <a href="messages.php" class="active">Messages</a>
                <a href="../index.php" target="_blank">View site</a>
                <a href="../logout.php">Log out</a>
            </nav>
        </div>
    </header>
    <main class="wrap admin-main">
        <h1>Messages</h1>
        <div class="panel">
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr><th>From</th><th>Message</th><th>Received</th></tr>
                    </thead>
                    <tbody>
                    <?php if ($messages->num_rows > 0): ?>
                        <?php while ($row = $messages->fetch_assoc()): ?>
                            <tr>
                                <td class="nowrap">
                                    <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                                    <a class="text-link" href="mailto:<?php echo htmlspecialchars($row['email']); ?>"><?php echo htmlspecialchars($row['email']); ?></a>
                                </td>
                                <td class="msg-body"><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                                <td class="nowrap"><?php echo htmlspecialchars($row['created_at']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3">No messages yet.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
