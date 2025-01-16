<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $product_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    if (!$product_id) {
        $_SESSION['error'] = "Invalid product ID";
        header("Location: ../product-list.php");
        exit();
    }

    // Collect all form data
    $product_data = [
        'product_name' => $_POST['product_name'] ?? '',
        'product_price' => $_POST['product_price'] ?? '',
        'discount_price' => $_POST['discount_price'] ?? '',
        'shippingPrice' => $_POST['shippingPrice'] ?? '',
        'rating' => $_POST['rating'] ?? '',
        'brand' => $_POST['brand'] ?? '',
        'color' => $_POST['color'] ?? '',
        'size' => $_POST['size'] ?? '',
        'category' => $_POST['category'] ?? '',
        'gender' => $_POST['gender'] ?? '',
        'product_type' => $_POST['product_type'] ?? '',
        'sales' => $_POST['sales'] ?? '',
        'description' => $_POST['description'] ?? '',
        'specification' => $_POST['specification'] ?? ''
    ];

    // Get existing product data for image
    $stmt = $conn->prepare("SELECT image_paths FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $existing_product = $result->fetch_assoc();
    $stmt->close();

    // Handle image upload
    $new_image_path = $existing_product['image_paths'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_directory = "../uploads/";
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $image_path = $upload_directory . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            // Delete old image if exists and different
            if ($existing_product['image_paths'] && 
                file_exists($existing_product['image_paths']) && 
                $existing_product['image_paths'] !== $image_path) {
                unlink($existing_product['image_paths']);
            }
            $new_image_path = $image_path;
        } else {
            $_SESSION['error'] = "Failed to upload image";
            header("Location: ../product-list.php");
            exit();
        }
    }

    // Update database
    try {
        $sql = "UPDATE products SET 
                product_name = ?,
                product_price = ?,
                discount_price = ?,
                shippingPrice = ?,
                rating = ?,
                brand = ?,
                color = ?,
                size = ?,
                category = ?,
                gender = ?,
                product_type = ?,
                sales = ?,
                description = ?,
                specification = ?,
                image_paths = ?
                WHERE id = ?";
                
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param("sssssssssssssssi", 
            $product_data['product_name'],
            $product_data['product_price'],
            $product_data['discount_price'],
            $product_data['shippingPrice'],
            $product_data['rating'],
            $product_data['brand'],
            $product_data['color'],
            $product_data['size'],
            $product_data['category'],
            $product_data['gender'],
            $product_data['product_type'],
            $product_data['sales'],
            $product_data['description'],
            $product_data['specification'],
            $new_image_path,
            $product_id
        );
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Product updated successfully";
        } else {
            throw new Exception($stmt->error);
        }

        $stmt->close();
        header("Location: ../product-list.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = "Error updating product: " . $e->getMessage();
        header("Location: ../product-list.php");
        exit();
    }

} else {
    $_SESSION['error'] = "Invalid request method";
    header("Location: ../product-list.php");
    exit();
}
?>