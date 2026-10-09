<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: ../login.php"); exit(); }
include('../db.php');

if (!isset($_GET['id'])) { header("Location: dashboard.php"); exit(); }
$id = intval($_GET['id']);
$error = "";

// Load the current project
$stmt = $conn->prepare("SELECT title, description, technologies, project_link FROM projects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$project) { header("Location: dashboard.php"); exit(); }

$title        = $project['title'];
$description  = $project['description'];
$technologies = $project['technologies'];
$project_link = $project['project_link'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title        = trim($_POST['title']);
    $description  = trim($_POST['description']);
    $technologies = trim($_POST['technologies']);
    $project_link = trim($_POST['project_link']);

    if ($title == "" || $description == "") {
        $error = "The title and description can't be empty.";
    } elseif ($project_link != "" && !preg_match('#^https?://#i', $project_link)) {
        $error = "The link must start with http:// or https://";
    } else {
        $stmt = $conn->prepare("UPDATE projects SET title = ?, description = ?, technologies = ?, project_link = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $title, $description, $technologies, $project_link, $id);
        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "The changes could not be saved.";
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
    <title>Edit project | Admin</title>
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
        <h1>Edit project</h1>
        <div class="panel panel-narrow">
            <?php if ($error != ""): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form action="edit_project.php?id=<?php echo $id; ?>" method="POST">
                <div class="form-group"><label for="title">Title</label>
                    <input id="title" type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required></div>
                <div class="form-group"><label for="description">Description</label>
                    <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($description); ?></textarea></div>
                <div class="form-group"><label for="technologies">Technologies</label>
                    <input id="technologies" type="text" name="technologies" value="<?php echo htmlspecialchars($technologies); ?>" placeholder="PHP, MySQL, CSS"></div>
                <div class="form-group"><label for="project_link">Project link (optional)</label>
                    <input id="project_link" type="url" name="project_link" value="<?php echo htmlspecialchars($project_link); ?>" placeholder="https://"></div>
                <div class="actions">
                    <button type="submit" class="btn">Save changes</button>
                    <a class="btn btn-ghost" href="dashboard.php">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
