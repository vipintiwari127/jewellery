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
    // Prepare the SQL statement to fetch categories
    // $sql = "SELECT id, image, category, number, status FROM category WHERE id IN (" . implode(',', $ids) . ")";
    // $result = $conn->query($sql);
    // $categories = $result->fetch_all(MYSQLI_ASSOC);

    // Prepare the SQL statement to delete categories
    $deleteSql = "DELETE FROM category WHERE id IN (" . implode(',', $ids) . ")";
    $conn->query($deleteSql);

    // Delete associated files from the uploads folder
    // $warnings = [];
    // foreach ($categories as $category) {
    //     $imagePath = str_replace("../uploads/", "../uploads/", $category['image']);

    //     if (!empty($imagePath) && file_exists($imagePath)) {
    //         if (!unlink($imagePath)) {
    //             $warnings[] = "Failed to delete image: $imagePath";
    //         }
    //     } else {
    //         $warnings[] = "Image path does not exist: $imagePath";
    //     }
    // }

    $response['success'] = true;
    // $response['warnings'] = $warnings;
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    $response['error'] = 'Failed to delete categories';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
