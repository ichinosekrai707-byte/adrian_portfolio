<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: ../login.php"); exit(); }
include('../db.php');

$error = "";
$title = $description = $technologies = $project_link = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title        = trim($_POST['title']);
    $description  = trim($_POST['description']);
    $technologies = trim($_POST['technologies']);
    $project_link = trim($_POST['project_link']);

    if ($title == "" || $description == "") {
        $error = "Please enter a title and a description.";
    } elseif ($project_link != "" && !preg_match('#^https?://#i', $project_link)) {
        $error = "The link must start with http:// or https://";
    } else {
        $stmt = $conn->prepare("INSERT INTO projects (title, description, technologies, project_link) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $description, $technologies, $project_link);
        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "The project could not be saved.";
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
    <title>Add project | Admin</title>
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
        <h1>Add project</h1>
        <div class="panel panel-narrow">
            <?php if ($error != ""): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form action="add_project.php" method="POST">
                <div class="form-group"><label for="title">Title</label>
                    <input id="title" type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required></div>
                <div class="form-group"><label for="description">Description</label>
                    <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($description); ?></textarea></div>
                <div class="form-group"><label for="technologies">Technologies</label>
                    <input id="technologies" type="text" name="technologies" value="<?php echo htmlspecialchars($technologies); ?>" placeholder="PHP, MySQL, CSS"></div>
                <div class="form-group"><label for="project_link">Project link (optional)</label>
                    <input id="project_link" type="url" name="project_link" value="<?php echo htmlspecialchars($project_link); ?>" placeholder="https://"></div>
                <div class="actions">
                    <button type="submit" class="btn">Save project</button>
                    <a class="btn btn-ghost" href="dashboard.php">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
