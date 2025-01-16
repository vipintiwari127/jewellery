<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config.php'; // Include your database configuration

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $category = $_POST['category'] ?? '';
    $type = $_POST['type'] ?? '';
    $status = isset($_POST['status']) ? 1 : 0; // Check if the status checkbox is checked

    // Prepare SQL query to insert data into the category table
    $sql = "INSERT INTO category (category, type, status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => $conn->error]);
        exit;
    }

    // Bind parameters
    $stmt->bind_param("ssi", $category, $type, $status);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
        header("Location: ../category-list.php");
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
