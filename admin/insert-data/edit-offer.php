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
        if (empty($_POST['offer_id']) || empty($_POST['offer_price'])) {
            throw new Exception('Required fields are missing');
        }

        $offer_id = intval($_POST['offer_id']);
        $offer_price = floatval($_POST['offer_price']);
        $status = isset($_POST['status']) ? 1 : 0; // Handle status field

        // Validate price
        if ($offer_price < 0) {
            throw new Exception('Price cannot be negative');
        }

        // Get existing offer details
        $stmt = $conn->prepare("SELECT image_paths FROM offer WHERE id = ?");
        $stmt->bind_param("i", $offer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception('Offer not found');
        }

        $current_offer = $result->fetch_assoc();
        $stmt->close();

        // Handle image upload
        $image_path = $current_offer['image_paths']; // Keep existing image by default

        if (isset($_FILES['image_paths']) && $_FILES['image_paths']['error'] === UPLOAD_ERR_OK) {
            // Validate new image
            validateImage($_FILES['image_paths']);

            // Generate unique filename
            $file_extension = pathinfo($_FILES['image_paths']['name'], PATHINFO_EXTENSION);
            $new_filename = uniqid('offer_') . '.' . $file_extension;
            $upload_path = '../uploads/' . $new_filename; // Adjust path as needed

            // Move uploaded file
            if (!move_uploaded_file($_FILES['image_paths']['tmp_name'], $upload_path)) {
                throw new Exception('Failed to upload image');
            }

            // Delete old image if it exists
            if ($current_offer['image_paths'] && file_exists($current_offer['image_paths'])) {
                unlink($current_offer['image_paths']);
            }

            $image_path = $upload_path;
        }

        // Update database
        $stmt = $conn->prepare("UPDATE offer SET offer_price = ?, image_paths = ?, status = ? WHERE id = ?");
        $stmt->bind_param("dsii", $offer_price, $image_path, $status, $offer_id);

        if (!$stmt->execute()) {
            throw new Exception('Failed to update offer: ' . $stmt->error);
        }

        $stmt->close();
        $conn->close();

        $_SESSION['success'] = "Offer updated successfully";
        header("Location: ../offer-list.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: edit-offer.php?id=" . $offer_id);
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request method";
    header("Location: offer-list.php");
    exit();
}
?>
