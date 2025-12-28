<?php
header("Content-Type: application/json");
include '../config.php';

$stmt = $pdo->query("SELECT * FROM products ORDER BY name ASC");
echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll()]);
?>