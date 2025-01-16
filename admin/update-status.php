<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['id']);
    $status = intval($_POST['status']);

    try {
        $stmt = $conn->prepare("UPDATE products SET status = ? WHERE id = ?");
        $stmt->bind_param('ii', $status, $product_id);
        $stmt->execute();

        if ($status === 0) {
            // Disable offer when status is off
            $offer_stmt = $conn->prepare("UPDATE products SET offer_price = NULL WHERE id = ?");
            $offer_stmt->bind_param('i', $product_id);
            $offer_stmt->execute();
        }

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>
