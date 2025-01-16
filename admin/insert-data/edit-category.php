<?php
// edit-category.php
session_start();
include '../config.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Debug: Print received data
        error_log("Received POST  " . print_r($_POST, true));

        // Validate and sanitize inputs
        $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
        $category_name = isset($_POST['category_name']) ? trim($_POST['category_name']) : '';
        $category_type = isset($_POST['type']) ? trim($_POST['type']) : '';
        $status = isset($_POST['status']) ? 1 : 0;

        // Validate required data
        if ($category_id <= 0) {
            throw new Exception('Invalid category ID');
        }
        if (empty($category_name)) {
            throw new Exception('Category name cannot be empty');
        }

        // Initialize update query components
        $updateFields = [];
        $queryParams = [];
        $types = '';

        // Basic fields
        $updateFields[] = "category = ?";
        $updateFields[] = "type = ?";
        $queryParams[] = $category_name;
        $queryParams[] = $category_type;
        $types .= "ss"; // string, integer

        // Add status to update
        $updateFields[] = "status = ?";
        $queryParams[] = $status;
        $types .= "i";

        // Add category_id for WHERE clause
        $queryParams[] = $category_id;
        $types .= "i";

        // Construct and execute update query
        $query = "UPDATE category SET " . implode(", ", $updateFields) . " WHERE id = ?";

        // Debug logging
        error_log("Update query: " . $query);
        error_log("Parameters: " . print_r($queryParams, true));

        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param($types, ...$queryParams);

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        if ($stmt->affected_rows === 0 && $stmt->errno === 0) {
            $_SESSION['info'] = "No changes were made to the category";
        } else {
            $_SESSION['success'] = "Category updated successfully";
        }

        $stmt->close();
        $conn->close();

        header("Location: ../category-list.php");
        exit();

    } catch (Exception $e) {
        error_log("Error in category update: " . $e->getMessage());
        $_SESSION['error'] = $e->getMessage();
        header("Location: ../category-list.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request method";
    header("Location: ../category-list.php");
    exit();
}
?>
