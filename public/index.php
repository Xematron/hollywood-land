<?php
$host = getenv('DB_HOST');
$db = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $password);
    $status = "Connexió a la BD OK";
} catch (PDOException $e) {
    $status = "Error de connexió: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Hollywood Land</title>
</head>
<body>
    <h1>Hollywood Land</h1>
    <p><?= htmlspecialchars($status) ?></p>
</body>
</html>
