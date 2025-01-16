<?php include 'inc/header.php'; ?>
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



<div class="unique-container">
	<?php
	include 'admin/config.php';
	$sql = "SELECT * FROM products WHERE status='1' ";
	$result2 = mysqli_query($conn, $sql) or die("query unsuccessful");
	if (mysqli_num_rows($result2) > 0) {
		?>
		<h2 class="unique-section-title">Silver Products</h2>


		<div class="unique-card-wrapper">
			<?php
			while ($row = mysqli_fetch_assoc($result2)) {
				$offer_id = $row['offer_price']; // Corrected from $product to $row
				$sql4 = "SELECT offer_price AS offer FROM offer WHERE id = '$offer_id'";
				$query4 = mysqli_query($conn, $sql4);
				$res4 = mysqli_fetch_array($query4, MYSQLI_BOTH);
				?>
				<div class="unique-card">
					<div class="badge"><?php echo $res4['offer']; ?>% OFF</div>
					<img src="<?php echo str_replace("../uploads/", "./admin/uploads/", $row['image_paths']); ?>"
						style="height:250px; width:100%;" alt="Product">

					<div class="unique-card-content">
						<h3><?php echo $row['product_name']; ?></h3>
						<p class="unique-price">₹<?php echo $row['product_price']; ?></p>
						<button class="inquire-btn"
							onclick="openModal('<?php echo $row['product_name']; ?>', '<?php echo str_replace("../uploads/", "./admin/uploads/", $row['image_paths']); ?>', '<?php echo $row['description']; ?>')">
							Inquire Now
						</button>
					</div>
				</div>
			<?php } ?>

		</div>
		<?php
	} else {
		echo '<p>No Record found</p>';
	}
	?>
</div>

<br><br>
<div class="unique-container">
	<h2 class="unique-section-title">Featured Products</h2>
	<div class="unique-container">
		<?php
		include 'admin/config.php';
		$sql = "SELECT * FROM products";
		$result2 = mysqli_query($conn, $sql) or die("query unsuccessful");
		if (mysqli_num_rows($result2) > 0) {
			?>


			<div class="unique-card-wrapper">
				<?php
				while ($row = mysqli_fetch_assoc($result2)) {
					?>
					<div class="unique-card">

						<img src="<?php echo str_replace("../uploads/", "./admin/uploads/", $row['image_paths']); ?>"
							style="height:250px; width:100%;" alt="Product">

						<div class="unique-card-content">

							<h3><?php echo $row['product_name']; ?></h3>
							<p class="unique-price">₹<?php echo $row['product_price']; ?></p>
							<button class="inquire-btn"
								onclick="openModal('<?php echo $row['product_name']; ?>', '<?php echo str_replace("../uploads/", "./admin/uploads/", $row['image_paths']); ?>', '<?php echo $row['description']; ?>')">
								Inquire Now
							</button>
						</div>
					</div>

				<?php } ?>
			</div>
			<?php
		} else {
			echo '<p>No Record found</p>';
		}
		?>
	</div>
</div>

<br><br>

<div class="container-fluit">
	<div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active">
				<img src="./Jweelery/c1.jpg" class="d-block w-100" alt="Slide 1">
			</div>

			<div class="carousel-item">
				<img src="./Jweelery/c2.jpg" class="d-block w-100" alt="Slide 3">
			</div>
		</div>
		<button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Previous</span>
		</button>
		<button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="visually-hidden">Next</span>
		</button>
	</div>
</div>

<br><br>
<div class="unique-container">
	<?php
	include 'admin/config.php';
	$sql = "SELECT * FROM products";
	$result2 = mysqli_query($conn, $sql) or die("query unsuccessful");
	if (mysqli_num_rows($result2) > 0) {
		?>

		<h2 class="unique-section-title">Sales Products</h2>


		<div class="unique-card-wrapper">
			<?php
			while ($row = mysqli_fetch_assoc($result2)) {
				?>
				<div class="unique-card">

					<img src="<?php echo str_replace("../uploads/", "./admin/uploads/", $row['image_paths']); ?>"
						style="height:250px; width:100%;" alt="Product">

					<div class="unique-card-content">

						<h3><?php echo $row['product_name']; ?></h3>
						<p class="unique-price">₹<?php echo $row['product_price']; ?></p>
						<button class="inquire-btn"
							onclick="openModal('<?php echo $row['product_name']; ?>', '<?php echo str_replace("../uploads/", "./admin/uploads/", $row['image_paths']); ?>', '<?php echo $row['description']; ?>')">
							Inquire Now
						</button>
					</div>
				</div>

			<?php } ?>
		</div>
		<?php
	} else {
		echo '<p>No Record found</p>';
	}
	?>
</div>



<!--  -->
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

<!--  -->
<?php include 'inc/footer.php'; ?>