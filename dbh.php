<?php
$host = getenv("DB_HOST") ?: "localhost";
$dbname = getenv("DB_NAME") ?: "your_database_name";
$user = getenv("DB_USER") ?: "your_database_user";
$password = getenv("DB_PASSWORD") ?: "your_database_password";

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Database connection failed.");
}
?>
