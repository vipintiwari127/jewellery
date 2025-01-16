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
    // Prepare the SQL statement to fetch variants
    $sql = "SELECT id FROM varriant WHERE id IN (" . implode(',', $ids) . ")";
    $result = $conn->query($sql);
    $varriants = $result->fetch_all(MYSQLI_ASSOC);

    // Prepare the SQL statement to delete variants
    $deleteSql = "DELETE FROM varriant WHERE id IN (" . implode(',', $ids) . ")";
    $conn->query($deleteSql);

    $response['success'] = true;
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    $response['error'] = 'Failed to delete variants';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
