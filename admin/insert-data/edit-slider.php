<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $slider_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $slider_name = $_POST['slider_name'] ?? '';
    
    if (!$slider_id) {
        $_SESSION['error'] = "Invalid slider ID";
        header("Location: ../slider-list.php");
        exit();
    }

    // Get existing slider data
    $stmt = $conn->prepare("SELECT image_paths, video FROM sliders WHERE id = ?");
    $stmt->bind_param("i", $slider_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $existing_slider = $result->fetch_assoc();
    $stmt->close();

    // Initialize variables for new file paths
    $new_image_path = $existing_slider['image_paths'];
    $new_video_path = $existing_slider['video'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_directory = "../uploads/";
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $image_path = $upload_directory . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            // Delete old image if exists and different
            if ($existing_slider['image_paths'] && file_exists($existing_slider['image_paths'])) {
                unlink($existing_slider['image_paths']);
            }
            $new_image_path = $image_path;
        } else {
            $_SESSION['error'] = "Failed to upload image";
            header("Location: ../slider-list.php");
            exit();
        }
    }

    // Handle video upload
    if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $upload_directory = "../uploads/";
        $video_name = time() . '_' . basename($_FILES['video']['name']);
        $video_path = $upload_directory . $video_name;

        if (move_uploaded_file($_FILES['video']['tmp_name'], $video_path)) {
            // Delete old video if exists and different
            if ($existing_slider['video'] && file_exists($existing_slider['video'])) {
                unlink($existing_slider['video']);
            }
            $new_video_path = $video_path;
        } else {
            $_SESSION['error'] = "Failed to upload video";
            header("Location: ../slider-list.php");
            exit();
        }
    }

    // Update database
    try {
        $sql = "UPDATE sliders SET slider_name = ?, image_paths = ?, video = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param("sssi", $slider_name, $new_image_path, $new_video_path, $slider_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Slider updated successfully";
        } else {
            throw new Exception($stmt->error);
        }

        $stmt->close();
        header("Location: ../slider-list.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = "Error updating slider: " . $e->getMessage();
        header("Location: ../slider-list.php");
        exit();
    }

} else {
    $_SESSION['error'] = "Invalid request method";
    header("Location: ../slider-list.php");
    exit();
}
?>