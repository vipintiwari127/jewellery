<?php
// delete-data/delete-products.php
include '../config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ids'])) {
    try {
        // Ensure database connection exists
        if (!isset($conn) || $conn->connect_error) {
            throw new Exception('Database connection failed');
        }

        $conn->begin_transaction();

        // Sanitize and validate IDs
        $ids = array_filter(
            array_map('intval', explode(',', $_POST['ids'])),
            function($id) { return $id > 0; }
        );

        if (empty($ids)) {
            throw new Exception('No valid products selected');
        }

        // Get image paths before deletion
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $stmt = $conn->prepare("SELECT id, image_paths FROM products WHERE id IN ($placeholders)");
        
        if (!$stmt) {
            throw new Exception('Failed to prepare select statement: ' . $conn->error);
        }

        $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to execute select statement: ' . $stmt->error);
        }

        $result = $stmt->get_result();
        $imagePaths = [];

        while ($product = $result->fetch_assoc()) {
            if (!empty($product['image_paths'])) {
                // Handle multiple image paths if they're stored as comma-separated
                $paths = explode(',', $product['image_paths']);
                foreach ($paths as $path) {
                    $imagePaths[] = '../uploads/' . trim(basename($path));
                }
            }
        }
        $stmt->close();

        // Delete from database
        $stmt = $conn->prepare("DELETE FROM products WHERE id IN ($placeholders)");
        
        if (!$stmt) {
            throw new Exception('Failed to prepare delete statement: ' . $conn->error);
        }

        $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);

        if (!$stmt->execute()) {
            throw new Exception('Failed to execute delete statement: ' . $stmt->error);
        }

        $deletedCount = $stmt->affected_rows;
        
        if ($deletedCount === 0) {
            throw new Exception('No products were deleted');
        }

        // Delete image files
        $deletedFiles = 0;
        foreach ($imagePaths as $path) {
            if (file_exists($path)) {
                if (unlink($path)) {
                    $deletedFiles++;
                }
            }
        }

        $conn->commit();
        
        echo json_encode([
            'success' => true,
            'message' => "Successfully deleted $deletedCount products and $deletedFiles image files"
        ]);

    } catch (Exception $e) {
        if (isset($conn)) {
            $conn->rollback();
        }
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        if (isset($conn)) {
            $conn->close();
        }
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid request method or missing product IDs'
    ]);
}
?>
