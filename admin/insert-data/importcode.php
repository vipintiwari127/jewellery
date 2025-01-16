<?php
include '../config.php';

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Check if CSV file is uploaded
    if (isset($_FILES['import_file']) && $_FILES['import_file']['error'] == 0) {
        // Get CSV file info
        $fileTmpName = $_FILES['import_file']['tmp_name'];
        $fileType = $_FILES['import_file']['type'];
        $fileSize = $_FILES['import_file']['size'];

        // Define allowed CSV file types
        $allowedFileTypes = ['text/csv', 'application/vnd.ms-excel'];

        // Check if the file type is valid and within size limits
        if (in_array($fileType, $allowedFileTypes) && $fileSize <= 5242880) {
            // Open CSV file
            $csvFile = fopen($fileTmpName, 'r');

            // Skip the header row
            fgetcsv($csvFile);

            try {
                // Create database connection using mysqli
               
                // Prepare SQL statement
                $sql = "INSERT INTO products (product_name, product_price, discount_price, rating, brand, color, size,category, gender, product_type, sales,  description, specification, image_paths) VALUES (?, ?, ?,  ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                // Array to store image paths for all products
                $allImagePaths = [];

                // Process all uploaded images
                if (isset($_FILES['image']) && !empty($_FILES['image']['name'][0])) {
                    foreach ($_FILES['image']['name'] as $index => $imageName) {
                        $imageTmpName = $_FILES['image']['tmp_name'][$index];
                        $imageType = $_FILES['image']['type'][$index];
                        $imageSize = $_FILES['image']['size'][$index];

                        // Set allowed image types and size limit
                        $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
                        if (in_array($imageType, $allowedImageTypes) && $imageSize <= 5242880) { // 5MB limit
                            // Save the image and add its path to the array
                            $targetDir = "../uploads/";
                            if (!is_dir($targetDir)) {
                                mkdir($targetDir, 0755, true);
                            }
                            $targetFilePath = $targetDir . uniqid() . "_" . basename($imageName);
                            if (move_uploaded_file($imageTmpName, $targetFilePath)) {
                                $allImagePaths[] = $targetFilePath;
                            } else {
                                echo "Error: Failed to move uploaded file $imageName.<br>";
                            }
                        } else {
                            echo "Error: Image $imageName is too large or not a valid format.<br>";
                        }
                    }
                }

                // Bind parameters type
                $stmt->bind_param("sssdsssssissss", 
                    $product_name, 
                    $product_price, 
                    $discount_price, 
                    $rating, 
                    $brand, 
                    $color, 
                    $size, 
                    $gender, 
                    $product_type, 
                    $sales, 
                    $category,
                    $description, 
                    $specification, 
                    $image_path
                );

                // Loop through each row in the CSV to insert data into the database
                $imageIndex = 0;
                while (($row = fgetcsv($csvFile, 1000, ",")) !== FALSE) {
                    // Assign values to bound variables
                    $product_name = $row[0];
                    $product_price = $row[1];
                    $discount_price = $row[2];
                    $rating = $row[3];
                    $brand = $row[4];
                    $color = $row[5];
                    $size = $row[6];
                    $category = $row[7];
                    $gender = $row[8];
                    $product_type = $row[9];
                    $sales = $row[10];
                    $description = $row[11];
                    $specification = $row[12];
                    $image_path = isset($allImagePaths[$imageIndex]) ? $allImagePaths[$imageIndex] : NULL;
                    $imageIndex++;

                    // Execute the prepared statement
                    if (!$stmt->execute()) {
                        throw new Exception("Error executing statement: " . $stmt->error);
                    }
                }

                fclose($csvFile);
                $stmt->close();
                $conn->close();

                echo "CSV file and images imported successfully!";
                header("Location: ../product-list.php");
                exit();

            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
                if (isset($stmt)) $stmt->close();
                if (isset($conn)) $conn->close();
                if (isset($csvFile)) fclose($csvFile);
            }
        } else {
            echo "Error: Only CSV files under 5MB are allowed.";
        }
    } else {
        echo "Error: No CSV file was uploaded or there was an issue with the upload.";
    }
}
?>