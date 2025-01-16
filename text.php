<?php include 'inc/header.php'; ?>

<!-- Video Carousel Section -->
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
	<?php
	include 'admin/config.php';

	// Enable error reporting for debugging
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	// Get the brand from the URL and sanitize it
	$brand = isset($_GET['brand']) ? mysqli_real_escape_string($conn, $_GET['brand']) : '';

	if (empty($brand)) {
		echo '<p class="error-message">No brand specified</p>';
		exit;
	}

	// First, get the brand ID from the brand table
	$brand_query = "SELECT id FROM brand WHERE brand = ?";
	$brand_stmt = $conn->prepare($brand_query);
	$brand_stmt->bind_param("s", $brand);
	$brand_stmt->execute();
	$brand_result = $brand_stmt->get_result();

	if ($brand_result->num_rows === 0) {
		echo '<p class="error-message">Brand not found</p>';
		exit;
	}

	$brand_row = $brand_result->fetch_assoc();
	$brand_id = $brand_row['id'];

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

	if ($result->num_rows > 0) {
		?>
		<h2 class="unique-section-title"><?php echo htmlspecialchars(ucfirst($brand)); ?> Products</h2>
		<div class="unique-card-wrapper">
			<?php
			while ($row = $result->fetch_assoc()) {
				
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
		<?php
	} else {
		echo '<p class="no-products">No products found for ' . htmlspecialchars($brand) . '</p>';
	}

	// Close all statements
	$brand_stmt->close();
	$stmt->close();
	?>
</div>
<div id="inquireModal" class="modal">
	<div class="modal-content">
		<span class="close-btn" onclick="closeModal()">&times;</span>
		<h3 id="modalProductName">Product Name</h3>
		<img id="modalProductImage" src="" alt="Product Image" style="width: 100%; height: 150px; margin: 10px 0;">
		<p id="modalProductDescription">Product description goes here.</p>
		<p>Contact us for more details:</p>
		<div class="contact-options">
			<a href="https://wa.me/7000479276" target="_blank" class="whatsapp-btn"><i class="fa fa-whatsapp"></i>
				WhatsApp</a>
			<a href="tel:7000479276" class="call-btn"><i class="fa fa-phone"></i> Call Now</a>
		</div>
	</div>
</div>
<?php include 'inc/footer.php'; ?>