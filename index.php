<?php
include('db.php');

// ===== Edit your details here =====
$first_name = "Adrian";
$full_name  = "Adrian Roxas";
$role       = "BS Information Technology student";
$school     = "PHINMAED University of Iloilo";
$intro      = "I'm an Information Technology student at PHINMAED University of Iloilo, learning to build reliable software and networks one project at a time.";
$about      = "Hi, I'm Adrian Roxas, a Bachelor of Science in Information Technology student at PHINMAED University of Iloilo. I enjoy turning ideas into working systems, from writing Java programs to setting up networks and building database-driven websites like this one. I'm always learning and open to opportunities to grow through projects and collaboration.";
$studying   = "BS Information Technology";
$code       = "Java, PHP, SQL";
$network    = "IP addressing, subnetting";
$building   = "This portfolio";
$contact_email = "";   // optional: shown as a link if filled in
// ==================================

// Photo: save yours as assets/photo.jpg and it shows up automatically
$photo = file_exists('assets/photo.jpg') ? 'assets/photo.jpg' : '';

$msg = "";
$msg_class = "";
$f_name = $f_email = $f_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $f_name    = trim($_POST['name']);
    $f_email   = trim($_POST['email']);
    $f_message = trim($_POST['message']);

    if ($f_name == "" || $f_email == "" || $f_message == "") {
        $msg = "Please fill in your name, email and message.";
        $msg_class = "alert-danger";
    } elseif (!filter_var($f_email, FILTER_VALIDATE_EMAIL)) {
        $msg = "That email address doesn't look right.";
        $msg_class = "alert-danger";
    } else {
        $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $f_name, $f_email, $f_message);
        if ($stmt->execute()) {
            $msg = "Thanks! Your message was sent.";
            $msg_class = "alert-success";
            $f_name = $f_email = $f_message = "";   // empty the form
        } else {
            $msg = "Something went wrong. Please try again.";
            $msg_class = "alert-danger";
        }
        $stmt->close();
    }
}

$projects = $conn->query("SELECT * FROM projects ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($full_name); ?> | Portfolio</title>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;800&family=Figtree:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <div class="wrap bar">
            <a class="brand" href="#top"><?php echo htmlspecialchars($full_name); ?></a>
            <nav>
                <a href="#about">About</a>
                <a href="#projects">Projects</a>
                <a href="#contact">Contact</a>
            </nav>
        </div>
    </header>

    <main id="top">
        <section class="hero">
            <div class="wrap hero-grid">
                <div class="hero-text">
                    <p class="hero-role"><?php echo htmlspecialchars($role); ?></p>
                    <h1>Hi, I'm <?php echo htmlspecialchars($first_name); ?>.</h1>
                    <p class="lead"><?php echo htmlspecialchars($intro); ?></p>
                    <div class="hero-actions">
                        <a class="btn" href="#projects">See my projects</a>
                        <a class="btn btn-ghost" href="#contact">Send a message</a>
                    </div>
                </div>
                <div class="portrait">
                    <?php if ($photo != ""): ?>
                        <img src="<?php echo $photo; ?>" alt="Photo of <?php echo htmlspecialchars($full_name); ?>">
                    <?php else: ?>
                        <span><?php echo htmlspecialchars(strtoupper(substr($first_name, 0, 1))); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="wrap">
                <dl class="packet">
                    <div style="flex:3"><dt>Studying</dt><dd><?php echo htmlspecialchars($studying); ?></dd></div>
                    <div style="flex:3"><dt>Code</dt><dd><?php echo htmlspecialchars($code); ?></dd></div>
                    <div style="flex:3"><dt>Networking</dt><dd><?php echo htmlspecialchars($network); ?></dd></div>
                    <div style="flex:2"><dt>Building</dt><dd><?php echo htmlspecialchars($building); ?></dd></div>
                </dl>
            </div>
        </section>

        <section id="about" class="section">
            <div class="wrap split">
                <h2>About me</h2>
                <div class="prose"><p><?php echo htmlspecialchars($about); ?></p></div>
            </div>
        </section>

        <section id="projects" class="section section-white">
            <div class="wrap">
                <h2>Projects</h2>
                <div class="project-list">
                    <?php if ($projects->num_rows > 0): ?>
                        <?php while ($row = $projects->fetch_assoc()): ?>
                            <article class="project">
                                <div class="project-head">
                                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                    <ul class="tags">
                                        <?php foreach (explode(",", $row['technologies']) as $tag): ?>
                                            <?php if (trim($tag) != ""): ?>
                                                <li><?php echo htmlspecialchars(trim($tag)); ?></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <div class="project-body">
                                    <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                                    <?php if (preg_match('#^https?://#i', $row['project_link'])): ?>
                                        <a class="text-link" href="<?php echo htmlspecialchars($row['project_link']); ?>" target="_blank">View project</a>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="empty">Projects will show up here soon.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section id="contact" class="section">
            <div class="wrap split">
                <div>
                    <h2>Contact me</h2>
                    <p class="muted">Have a question or an opportunity? Send a message and I'll reply by email.</p>
                    <?php if ($contact_email != ""): ?>
                        <p><a class="text-link" href="mailto:<?php echo htmlspecialchars($contact_email); ?>"><?php echo htmlspecialchars($contact_email); ?></a></p>
                    <?php endif; ?>
                </div>
                <div>
                    <?php if ($msg != ""): ?>
                        <div class="alert <?php echo $msg_class; ?>"><?php echo htmlspecialchars($msg); ?></div>
                    <?php endif; ?>

                    <form action="index.php#contact" method="POST">
                        <div class="form-group">
                            <label for="name">Your name</label>
                            <input id="name" type="text" name="name" value="<?php echo htmlspecialchars($f_name); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Your email</label>
                            <input id="email" type="email" name="email" value="<?php echo htmlspecialchars($f_email); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" rows="5" required><?php echo htmlspecialchars($f_message); ?></textarea>
                        </div>
                        <button type="submit" class="btn">Send message</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="wrap bar">
            <span>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($full_name); ?></span>
            <a href="login.php">Admin login</a>
        </div>
    </footer>
</body>
</html>
