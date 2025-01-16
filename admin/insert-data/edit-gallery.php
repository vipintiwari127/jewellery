<?php
session_start();
include '../config.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to validate image
function validateImage($file) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 2 * 1024 * 1024; // 2MB

    if ($file['size'] > $maxSize) {
        throw new Exception('Image size must be less than 2MB');
    }

    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception('Only JPG, PNG, and GIF images are allowed');
    }

    return true;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        if (empty($_POST['gallery_id']) || empty($_POST['gallery_name'])) {
            throw new Exception('Required fields are missing');
        }

        $gallery_id = intval($_POST['gallery_id']);
        $gallery_name = $_POST['gallery_name'];

        // Validate gallery name
        if (empty($gallery_name)) {
            throw new Exception('Gallery name cannot be empty');
        }

        // Get existing gallery details
        $stmt = $conn->prepare("SELECT image_path FROM gallery WHERE id = ?");
        $stmt->bind_param("i", $gallery_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception('Gallery not found');
        }

        $current_gallery = $result->fetch_assoc();
        $stmt->close();

        // Handle image upload
        $image_path = $current_gallery['image_path']; // Keep existing image by default

        if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] === UPLOAD_ERR_OK) {
            // Validate new image
            validateImage($_FILES['image_path']);

            // Generate unique filename
            $file_extension = pathinfo($_FILES['image_path']['name'], PATHINFO_EXTENSION);
            $new_filename = uniqid('gallery_') . '.' . $file_extension;
            $upload_path = '../uploads/' . $new_filename; // Adjust path as needed

            // Move uploaded file
            if (!move_uploaded_file($_FILES['image_path']['tmp_name'], $upload_path)) {
                throw new Exception('Failed to upload image');
            }

            // Delete old image if it exists
            if ($current_gallery['image_path'] && file_exists($current_gallery['image_path'])) {
                unlink($current_gallery['image_path']);
            }

            $image_path = $upload_path;
        }

        // Update database
        $stmt = $conn->prepare("UPDATE gallery SET gallery_name = ?, image_path = ? WHERE id = ?");
        $stmt->bind_param("ssi", $gallery_name, $image_path, $gallery_id);

        if (!$stmt->execute()) {
            throw new Exception('Failed to update gallery: ' . $stmt->error);
        }

        $stmt->close();
        $conn->close();

        $_SESSION['success'] = "Gallery updated successfully";
        header("Location: ../gallery-list.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: edit-gallery.php?id=" . $gallery_id);
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request method";
    header("Location: gallery-list.php");
    exit();
}
?>
