<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: ../login.php"); exit(); }
include('../db.php');

$projects = $conn->query("SELECT id, title, technologies FROM projects ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Projects | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;800&family=Figtree:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header class="admin-bar">
        <div class="wrap bar">
            <a class="brand" href="dashboard.php">Admin</a>
            <nav>
                <a href="dashboard.php" class="active">Projects</a>
                <a href="messages.php">Messages</a>
                <a href="../index.php" target="_blank">View site</a>
                <a href="../logout.php">Log out</a>
            </nav>
        </div>
    </header>
    <main class="wrap admin-main">
        <h1>Projects</h1>

        <div class="panel">
            <div class="toolbar">
                <h2 style="font-size:1.4rem">All projects</h2>
                <a class="btn" href="add_project.php">Add project</a>
            </div>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr><th>Title</th><th>Technologies</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                    <?php if ($projects->num_rows > 0): ?>
                        <?php while ($row = $projects->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['technologies']); ?></td>
                                <td>
                                    <div class="actions">
                                        <a class="btn btn-ghost btn-sm" href="edit_project.php?id=<?php echo $row['id']; ?>">Edit</a>
                                        <form action="delete_project.php" method="POST" onsubmit="return confirm('Delete this project?');">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3">No projects yet. Click "Add project" to create one.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
