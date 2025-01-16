<?php
// edit-brand.php
session_start();
include '../config.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate CSRF token (assuming you have CSRF protection implemented)
        // if (!verify_csrf_token($_POST['csrf_token'])) {
        //     throw new Exception('Invalid CSRF token');
        // }

        // Validate and sanitize inputs
        $brand_id = filter_input(INPUT_POST, 'brand_id', FILTER_VALIDATE_INT);
        $brand_name = trim(filter_input(INPUT_POST, 'brand', FILTER_SANITIZE_STRING));
        $brand_category_type_id = trim(filter_input(INPUT_POST, 'category_type_id', FILTER_SANITIZE_STRING));
        $brand_category_id = trim(filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_STRING));
        $status = isset($_POST['status']) ? 1 : 0;

        if ($brand_id === false || $brand_id === null) {
            throw new Exception('Invalid brand ID');
        }
        if (empty($brand_name)) {
            throw new Exception('Brand name cannot be empty');
        }
        if (empty($brand_category_type_id)) {
            throw new Exception('Brand name cannot be empty');
        }
        if (empty($brand_category_id)) {
            throw new Exception('Brand name cannot be empty');
        }

        // Start transaction
        $conn->begin_transaction();

        // Prepare update query using named parameters
        $updateFields = ['brand = ?', 'category_type_id = ?', 'category_id = ?', 'status = ?'];
        $params = [$brand_name, $brand_category_type_id, $brand_category_id, $status];
        $types = 'sssi';

        $params[] = $brand_id;
        $types .= 'i';

        $query = "UPDATE brand SET " . implode(', ', $updateFields) . " WHERE id = ?";

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }

        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            throw new Exception("Database error: " . $stmt->error);
        }

        $conn->commit();

        $_SESSION['success'] = "Brand updated successfully";
        header("Location: ../brand-list.php");
        exit();

    } catch (Exception $e) {
        $conn->rollback();

        error_log("Brand update error: " . $e->getMessage());
        $_SESSION['error'] = "Update failed: " . $e->getMessage();
        header("Location: ../brand-list.php");
        exit();
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
        if (isset($conn)) {
            $conn->close();
        }
    }
}
?>