<?php
// XAMPP default login: user "root" with an empty password
$host   = "localhost";
$user   = "root";
$pass   = "";
$dbname = "adrian_portfolio";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Could not connect to the database. Is MySQL started in XAMPP?");
}
$conn->set_charset("utf8mb4");
?>
