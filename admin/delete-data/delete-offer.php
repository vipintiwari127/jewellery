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
    // Prepare the SQL statement to fetch offers
    $sql = "SELECT id, image_paths, offer_price FROM offer WHERE id IN (" . implode(',', $ids) . ")";
    $result = $conn->query($sql);
    $offers = $result->fetch_all(MYSQLI_ASSOC);

    // Prepare the SQL statement to delete offers
    $deleteSql = "DELETE FROM offer WHERE id IN (" . implode(',', $ids) . ")";
    $conn->query($deleteSql);

    // Delete associated files from the uploads folder
    $warnings = [];
    foreach ($offers as $offer) {
        $imagePath = str_replace("../uploads/", "../uploads/", $offer['image_paths']);

        if (!empty($imagePath) && file_exists($imagePath)) {
            if (!unlink($imagePath)) {
                $warnings[] = "Failed to delete image: $imagePath";
            }
        } else {
            $warnings[] = "Image path does not exist: $imagePath";
        }
    }

    $response['success'] = true;
    $response['warnings'] = $warnings;
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    $response['error'] = 'Failed to delete offers';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
