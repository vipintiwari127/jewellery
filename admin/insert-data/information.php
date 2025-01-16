<?php
// Database configuration
include '../config.php';

// Handle file uploads
$header_logo = $_FILES['header_logo']['name'];
$footer_logo = $_FILES['footer_logo']['name'];

$header_logo_tmp = $_FILES['header_logo']['tmp_name'];
$footer_logo_tmp = $_FILES['footer_logo']['tmp_name'];

$upload_dir = '../uploads/';

if (!empty($header_logo)) {
    move_uploaded_file($header_logo_tmp, $upload_dir . $header_logo);
}

if (!empty($footer_logo)) {
    move_uploaded_file($footer_logo_tmp, $upload_dir . $footer_logo);
}

// Collect form data
$mobile_no = $_POST['mobile_no'];
$location = $_POST['location'];
$email = $_POST['email'];
$twitter_link = $_POST['twitter_link'];
$facebook_link = $_POST['facebook_link'];
$instagram_link = $_POST['instagram_link'];
$linkedin_link = $_POST['linkedin_link'];

// Check if a record exists
$sql = "SELECT * FROM information LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Update existing record
    $stmt = $conn->prepare("UPDATE information SET header_logo=?, footer_logo=?, mobile_no=?, location=?, email=?, twitter_link=?, facebook_link=?, instagram_link=?, linkedin_link=?");
    $stmt->bind_param("sssssssss", $header_logo, $footer_logo, $mobile_no, $location, $email, $twitter_link, $facebook_link, $instagram_link, $linkedin_link);
} else {
    // Insert new record
    $stmt = $conn->prepare("INSERT INTO information (header_logo, footer_logo, mobile_no, location, email, twitter_link, facebook_link, instagram_link, linkedin_link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $header_logo, $footer_logo, $mobile_no, $location, $email, $twitter_link, $facebook_link, $instagram_link, $linkedin_link);
}

// Execute the statement
if ($stmt->execute()) {
    echo "Record updated successfully";
    header("Location: ../information.php");
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>