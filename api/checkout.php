<?php
header("Content-Type: application/json");
include '../config.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) { echo json_encode(['status' => 'error']); exit; }

try {
    $pdo->beginTransaction();
    $invoice = "INV-" . time();

    // Insert Header
    $stmt = $pdo->prepare("INSERT INTO transactions (invoice_number, total_price, paid_amount, change_amount) VALUES (?, ?, ?, ?)");
    $stmt->execute([$invoice, $data['total_price'], $data['paid_amount'], $data['change_amount']]);
    $trxId = $pdo->lastInsertId();

    // Insert Detail & Kurangi Stok
    foreach ($data['items'] as $item) {
        // Cek stok
        $stokCheck = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
        $stokCheck->execute([$item['id']]);
        if ($stokCheck->fetchColumn() < $item['qty']) throw new Exception("Stok Habis: " . $item['name']);

        // Insert Item
        $stmtDetail = $pdo->prepare("INSERT INTO transaction_details (transaction_id, product_id, quantity, price_at_sale) VALUES (?, ?, ?, ?)");
        $stmtDetail->execute([$trxId, $item['id'], $item['qty'], $item['price']]);

        // Update Stok
        $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?")->execute([$item['qty'], $item['id']]);
    }

    $pdo->commit();
    echo json_encode(['status' => 'success', 'invoice' => $invoice]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>