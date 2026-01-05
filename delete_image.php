<?php
require_once "includes/auth.php";
require_once "includes/db.php";

$image_id = (int)($_GET['id'] ?? 0);
$car_id   = (int)($_GET['car_id'] ?? 0);

if ($image_id <= 0 || $car_id <= 0) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT ci.file_name, c.user_id
    FROM car_images ci
    JOIN cars c ON ci.car_id = c.id
    WHERE ci.id = ? AND ci.car_id = ?
");
$stmt->execute([$image_id, $car_id]);

$image = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$image) {
    header("Location: index.php");
    exit;
}

// 🔐 μόνο ο ιδιοκτήτης
if ($image['user_id'] != $_SESSION['user_id']) {
    http_response_code(403);
    exit;
}

// 🗑️ filesystem
$filePath = __DIR__ . "/uploads/" . $image['file_name'];
if (is_file($filePath)) {
    unlink($filePath);
}

// 🗑️ database
$stmt = $pdo->prepare("DELETE FROM car_images WHERE id = ?");
$stmt->execute([$image_id]);

// 🔁 πίσω στο όχημα
header("Location: car_view.php?id=" . $car_id);
exit;
