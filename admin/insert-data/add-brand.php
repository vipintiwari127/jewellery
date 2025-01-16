<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config.php'; // Include your database configuration

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $brand = $_POST['brand'] ?? '';
    $category_type_id = $_POST['category_type_id'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $status = isset($_POST['status']) ? 1 : 0; // Check if the status checkbox is checked

    // // Handle image file upload if provided
    // $image = $_FILES['brand_image']['name'] ?? null;
    // $image_tmp = $_FILES['brand_image']['tmp_name'] ?? null;

    // $upload_directory = "../uploads/";

    // // Ensure the upload directory exists
    // if (!is_dir($upload_directory)) {
    //     mkdir($upload_directory, 0777, true);
    // }

    // // Initialize paths for database storage
    // $image_for_db = null;

    // // Upload image if present
    // if ($image && $image_tmp) {
    //     $image_upload_path = $upload_directory . basename($image);
    //     if (move_uploaded_file($image_tmp, $image_upload_path)) {
    //         $image_for_db = $image_upload_path;
    //     } else {
    //         echo json_encode(['success' => false, 'error' => 'Image upload failed']);
    //         exit;
    //     }
    // }

    // Prepare SQL query to insert data into the brand table
    $sql = "INSERT INTO brand (brand,category_type_id,category_id,status) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => $conn->error]);
        exit;
    }

    // Bind parameters
    $stmt->bind_param("sssi", $brand,$category_type_id, $category_id, $status);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
        header("Location: ../brand-list.php");
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
