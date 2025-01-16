<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config.php'; // Include your database configuration

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'] ?? '';
    $color = $_POST['color'] ?? '';
    $size = $_POST['size'] ?? '';
    $status = isset($_POST['status']) ? 1 : 0; // Check if the status checkbox is checked

    // Prepare SQL query to insert data into the varriant table
    $sql = "INSERT INTO varriant (name,color, size, status) VALUES (?, ?,?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => $conn->error]);
        exit;
    }

    // Bind parameters
    $stmt->bind_param("sssi", $name, $color, $size, $status);

    // Execute the query
    if ($stmt->execute()) { 
        // Redirect to varriant-list.php
        header("Location:../varriant-list.php");
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
