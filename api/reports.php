<?php
header("Content-Type: application/json");
include '../config.php';

$today = date('Y-m-d');
// Summary
$summary = $pdo->prepare("SELECT COUNT(*) as total_trx, SUM(total_price) as omzet FROM transactions WHERE DATE(created_at) = ?");
$summary->execute([$today]);

// Top Products
$top = $pdo->prepare("SELECT p.name, SUM(td.quantity) as sold FROM transaction_details td JOIN products p ON td.product_id = p.id JOIN transactions t ON td.transaction_id = t.id WHERE DATE(t.created_at) = ? GROUP BY p.id ORDER BY sold DESC LIMIT 5");
$top->execute([$today]);

echo json_encode(['status' => 'success', 'summary' => $summary->fetch(), 'top' => $top->fetchAll()]);
?>