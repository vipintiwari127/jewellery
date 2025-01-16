<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config.php'; // Include your database configuration

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $gallery_name = $_POST['gallery_name'] ?? '';

  // Handle image file uploads if provided
  $upload_directory = "../uploads/";

  // Initialize paths for database storage
  $image_paths_for_db = [];

  // Upload images if present
  if (isset($_FILES['image_path']) && !empty($_FILES['image_path']['name'][0])) {
    foreach ($_FILES['image_path']['name'] as $key => $image) {
      $image_tmp = $_FILES['image_path']['tmp_name'][$key];
      $image_upload_path = $upload_directory . basename($image);
      if (move_uploaded_file($image_tmp, $image_upload_path)) {
        $image_paths_for_db[] = $image_upload_path;
      } else {
        echo json_encode(['success' => false, 'error' => 'Image upload failed']);
        exit;
      }
    }
  }

  // Prepare SQL query to insert data into the gallery table
  $sql = "INSERT INTO gallery (gallery_name, image_path) VALUES (?, ?)";
  $stmt = $conn->prepare($sql);

  if (!$stmt) {
    echo json_encode(['success' => false, 'error' => $conn->error]);
    exit;
  }

  // Bind parameters and execute the query for each image
  foreach ($image_paths_for_db as $image_path_for_db) {
    $stmt->bind_param("ss", $gallery_name, $image_path_for_db);
    if (!$stmt->execute()) {
      echo json_encode(['success' => false, 'error' => $stmt->error]);
      exit;
    }
  }

  echo json_encode(['success' => true]);
  header("Location: ../gallery-list.php");

  // Close statement and database connection
  $stmt->close();
  $conn->close();
} else {
  echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
