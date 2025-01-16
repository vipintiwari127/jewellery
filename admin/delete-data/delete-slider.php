<?php
include '../config.php';

$response = ['success' => false, 'error' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['error'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

if (!isset($_POST['ids'])) {
    $response['error'] = 'No IDs provided';
    echo json_encode($response);
    exit;
}

$ids = explode(',', $_POST['ids']);
$ids = array_map('intval', $ids); // Ensure all IDs are integers

try {
    // Prepare the SQL statement to fetch sliders
    $stmt = $conn->prepare("SELECT id, image_paths, video FROM sliders WHERE id IN (" . implode(',', array_fill(0, count($ids), '?')) . ")");
    $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
    $stmt->execute();
    $result = $stmt->get_result();
    $sliders = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Prepare the SQL statement to delete sliders
    $deleteStmt = $conn->prepare("DELETE FROM sliders WHERE id IN (" . implode(',', array_fill(0, count($ids), '?')) . ")");
    $deleteStmt->bind_param(str_repeat('i', count($ids)), ...$ids);
    $deleteStmt->execute();
    $deleteStmt->close();

    // Delete associated files from the uploads folder
    $warnings = [];
    foreach ($sliders as $slider) {
        $imagePath = str_replace("../uploads/", "../uploads/", $slider['image_paths']);
        $videoPath = $slider['video'];

        if (!empty($imagePath) && file_exists($imagePath)) {
            if (!unlink($imagePath)) {
                $warnings[] = "Failed to delete image: $imagePath";
            }
        } else {
            $warnings[] = "Image path does not exist: $imagePath";
        }

        if (!empty($videoPath) && file_exists($videoPath)) {
            if (!unlink($videoPath)) {
                $warnings[] = "Failed to delete video: $videoPath";
            }
        } else {
            $warnings[] = "Video path does not exist: $videoPath";
        }
    }

    $response['success'] = true;
    $response['warnings'] = $warnings;
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    $response['error'] = 'Failed to delete sliders';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
