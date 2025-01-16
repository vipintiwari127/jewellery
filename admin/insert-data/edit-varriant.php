<?php
session_start();
include '../config.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate and sanitize inputs
        $varriant_id = filter_input(INPUT_POST, 'varriant_id', FILTER_VALIDATE_INT);
        $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $color = trim(filter_input(INPUT_POST, 'color', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $size = trim(filter_input(INPUT_POST, 'size', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $status = isset($_POST['status']) ? 1 : 0;

        if ($varriant_id === false || $varriant_id === null) {
            throw new Exception('Invalid variant ID');
        }
        if (empty($name)) {
            throw new Exception('Variant name cannot be empty');
        }

        // Start transaction
        $conn->begin_transaction();

        // Prepare update query using named parameters
        $query = "UPDATE varriant SET name = ?, color = ?, size = ?, status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }

        $stmt->bind_param('sssii', $name, $color, $size, $status, $varriant_id);

        if (!$stmt->execute()) {
            throw new Exception("Database error: " . $stmt->error);
        }

        $conn->commit();

        $_SESSION['success'] = "Variant updated successfully";
        // Redirect to variant-list.php
        header("Location: ../varriant-list.php");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        error_log("Variant update error: " . $e->getMessage());
        $_SESSION['error'] = "Update failed: " . $e->getMessage();
        header("Location:../varriant-list.php");
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
