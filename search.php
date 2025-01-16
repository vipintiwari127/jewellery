<?php
include 'admin/config.php';

if (isset($_GET['query'])) {
    $search = mysqli_real_escape_string($conn, $_GET['query']);
    $sql = "SELECT * FROM products WHERE product_name LIKE '%$search%' OR description LIKE '%$search%'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>" . $row['product_name'] . "</p>";
        }
    } else {
        echo "<p>No results found</p>";
    }
}
?>
