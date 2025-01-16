<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config.php'; // Include your database configuration

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $product_name = $_POST['product_name'];
  $product_price = $_POST['product_price'];
  $discount_price = $_POST['discount_price'];
  // $shippingPrice = $_POST['shippingPrice'];
  // $rating = $_POST['rating'];
  $brand = $_POST['brand'];
  $type = $_POST['type'];
  $offer_price = $_POST['offer_price'];
  $category = $_POST['category'];
  // $gender = $_POST['gender'];
  // $product_type = $_POST['product_type'];
  // $sales = $_POST['sales'];
  $description = $_POST['description'];
  // $specification = $_POST['specification'];

  // Handle file upload if an image was provided
  $image = isset($_FILES['image_paths']['name']) ? $_FILES['image_paths']['name'] : null;
  $image_tmp = isset($_FILES['image_paths']['tmp_name']) ? $_FILES['image_paths']['tmp_name'] : null;

  if ($image && $image_tmp) {
    // Define upload path
    $upload_directory = "../uploads/";
    $upload_file_path = $upload_directory . basename($image);

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($image_tmp, $upload_file_path)) {
      // Concatenate the upload path with the image name for database storage
      $image_path_for_db = $upload_directory . $image;

      // Prepare SQL query to insert data into the products table
      $sql = "INSERT INTO products (product_name, product_price, discount_price, brand, type, offer_price,category, description, image_paths) VALUES (?, ?, ?,?,?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);

      // Check if preparation is successful
      if (!$stmt) {
        echo json_encode(['success' => false, 'error' => $conn->error]);
        exit;
      }

      // Bind parameters - ensure that the string has the correct number of placeholders
      $stmt->bind_param(
        "sddssdsss",  // String and integer types for each parameter
        $product_name,
        $product_price,
        $discount_price,
        $brand,
        $type,
        $offer_price,
        $category,
        $description,
        $image_path_for_db  // Path to the uploaded image with the directory
      );

      // Execute the query and check for success
      if ($stmt->execute()) {
        echo json_encode(['success' => true]);
      } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
      }

      // Close the statement
      $stmt->close();
    } else {
      echo json_encode(['success' => false, 'error' => 'File upload failed']);
    }
  } else {
    echo json_encode(['success' => false, 'error' => 'No file was uploaded']);
  }
  header("Location: ../product-list.php");
  // Close the database connection
  $conn->close();
} else {
  echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>