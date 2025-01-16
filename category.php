<?php
include 'inc/header.php';
include 'admin/config.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get the category from the URL and sanitize it
$category_id = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

if (empty($category_id)) {
    echo '<p class="error-message">No category specified</p>';
    exit;
}

// First, get the category name from the category table
$category_query = "SELECT category FROM category WHERE id = ?";
$category_stmt = $conn->prepare($category_query);
$category_stmt->bind_param("i", $category_id);
$category_stmt->execute();
$category_result = $category_stmt->get_result();

if ($category_result->num_rows === 0) {
    echo '<p class="error-message">Category not found</p>';
    exit;
}

$category_row = $category_result->fetch_assoc();
$category_name = $category_row['category'];

// Get all products for this category ID with offer information
$product_query = "SELECT p.*, b.brand as brand_name, o.offer_price as offer_percentage
                 FROM products p
                 LEFT JOIN brand b ON p.brand = b.id
                 LEFT JOIN offer o ON p.offer_price = o.id
                 WHERE p.category = ?";
$stmt = $conn->prepare($product_query);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <video id="carouselVideo" autoplay loop class="d-block"
                style="height: 600px; width: 100vw; object-fit: cover;">
                <source src="./gold - Made with Clipchamp.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
</div>

<!-- Products Container -->
<div class="unique-container">
    <?php if ($result->num_rows > 0) { ?>
        <h2 class="unique-section-title"><?php echo htmlspecialchars($category_name); ?> Products</h2>
        <div class="unique-card-wrapper">
            <?php while ($row = $result->fetch_assoc()) {
                $image_path = str_replace("../uploads/", "./admin/uploads/", $row['image_paths']);
                ?>
                <div class="unique-card">
                    <?php if (!empty($row['offer_percentage'])): ?>
                        <div class="badge"><?php echo htmlspecialchars($row['offer_percentage']); ?>% OFF</div>
                    <?php endif; ?>

                    <img src="<?php echo htmlspecialchars($image_path); ?>"
                        alt="<?php echo htmlspecialchars($row['product_name']); ?>"
                        style="height:250px; width:100%; object-fit: cover;">

                    <div class="unique-card-content">
                        <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                        <p class="unique-price">₹<?php echo htmlspecialchars($row['product_price']); ?></p>
                        <button class="inquire-btn" onclick="openModal(
                            '<?php echo htmlspecialchars($row['product_name'], ENT_QUOTES); ?>', 
                            '<?php echo htmlspecialchars($image_path, ENT_QUOTES); ?>', 
                            '<?php echo htmlspecialchars($row['description'], ENT_QUOTES); ?>'
                        )">
                            Inquire Now
                        </button>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <p class="no-products">No products found in <?php echo htmlspecialchars($category_name); ?></p>
    <?php }

    // Close all statements
    $category_stmt->close();
    $stmt->close();
    ?>
</div>
<!-- Inquiry Modal -->
<div id="inquireModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h3 id="modalProductName">Product Name</h3>
        <img id="modalProductImage" src="" alt="Product Image" style="width: 100%; height: 150px; margin: 10px 0;">
        <p id="modalProductDescription">Product description goes here.</p>
        <p>Contact us for more details:</p>
        <div class="contact-options">
            <a href="https://wa.me/7000479276" target="_blank" class="whatsapp-btn">
                <i class="fa fa-whatsapp"></i> WhatsApp
            </a>
            <a href="tel:7000479276" class="call-btn">
                <i class="fa fa-phone"></i> Call Now
            </a>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>