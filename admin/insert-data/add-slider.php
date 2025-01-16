<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config.php'; // Include your database configuration

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $slider_name = $_POST['slider_name'] ?? '';

  // Handle image file upload if provided
  $image = $_FILES['image_paths']['name'] ?? null;
  $image_tmp = $_FILES['image_paths']['tmp_name'] ?? null;

  // Handle video file upload if provided
  $video = $_FILES['video']['name'] ?? null;
  $video_tmp = $_FILES['video']['tmp_name'] ?? null;

  $upload_directory = "../uploads/";

  // Initialize paths for database storage
  $image_path_for_db = null;
  $video_path_for_db = null;

  // Upload image if present
  if ($image && $image_tmp) {
    $image_upload_path = $upload_directory . basename($image);
    if (move_uploaded_file($image_tmp, $image_upload_path)) {
      $image_path_for_db = $image_upload_path;
    } else {
      echo json_encode(['success' => false, 'error' => 'Image upload failed']);
      exit;
    }
  }

  // Upload video if present
  if ($video && $video_tmp) {
    $video_upload_path = $upload_directory . basename($video);
    if (move_uploaded_file($video_tmp, $video_upload_path)) {
      $video_path_for_db = $video_upload_path;
    } else {
      echo json_encode(['success' => false, 'error' => 'Video upload failed']);
      exit;
    }
  }

  // Prepare SQL query to insert data into the sliders table
  $sql = "INSERT INTO sliders (slider_name, video, image_paths) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);

  if (!$stmt) {
    echo json_encode(['success' => false, 'error' => $conn->error]);
    exit;
  }

  // Bind parameters
  $stmt->bind_param("sss", $slider_name, $video_path_for_db, $image_path_for_db);

  // Execute the query
  if ($stmt->execute()) {
    echo json_encode(['success' => true]);
      header("Location: ../slider-list.php");
  } else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
  }
  // header("Location: ../slider-list.php");
  // Close statement and database connection
  $stmt->close();
  $conn->close();
} else {
  echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
