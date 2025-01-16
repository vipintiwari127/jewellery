<?php include 'inc/header.php'; ?>


<style>
	.contact-banner {
		text-align: center;
		margin-bottom: 20px;
	}

	.contact-banner img {
		width: 100%;
		max-height: 200px;
		object-fit: cover;
	}

	.contact-container {
		margin: 30px auto;
		max-width: 1200px;
	}

	.contact-form input,
	.contact-form textarea {
		margin-bottom: 15px;
	}

	.map-container {
		margin-top: 30px;
	}
</style>


<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
	<div class="carousel-inner">
		<div class="carousel-item active">
			<video id="carouselVideo" autoplay loop class="d-block" style="height: 600px; width: 100vw; object-fit: cover;">
				<source src="./gold - Made with Clipchamp.mp4" type="video/mp4">
				Your browser does not support the video tag.
			</video>
		</div>
	</div>
</div>


<div class="container contact-container">
	<!-- Banner -->
	<!-- <div class="contact-banner">
			<img src="https://via.placeholder.com/1200x200?text=20%25+off+All+Dresses" alt="20% off All Product">
		</div> -->

	<div class="row">
		<!-- Contact Form -->
		<div class="col-md-6">
			<h2>Contact us.</h2>
			<form action="submit-contact.php" Method="Post" class="contact-form" enctype="multipart/form-data" >
				<div class="row">
					<div class="col-md-6">
						<input type="text" class="form-control" name="fname" placeholder="First Name">
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="lname" placeholder="Last Name">
					</div>
				</div>
				<input type="email" class="form-control" name="email" placeholder="E-mail">
				<input type="text" class="form-control" name="subject" placeholder="Subject">
				<textarea class="form-control" rows="4" name="message" placeholder="Message"></textarea>
				<button type="submit" class="btn btn-outline-dark mt-2">Send Message</button>
			</form>
		</div>

		<!-- Contact Info -->
		<div class="col-md-6">
			<?php
			include 'admin/config.php';
			$sql = "SELECT * FROM information";
			$result2 = mysqli_query($conn, $sql) or die("query unsuccessful");
			if (mysqli_num_rows($result2) > 0) {
				?> 	<?php
					 while ($row = mysqli_fetch_assoc($result2)) {
						 ?>
					<h4>Location</h4>
					<p><?php echo $row['location']; ?>
					</p>
					<h4>Phone</h4>
					<p> <?php echo $row['mobile_no']; ?></p>
					<h4>E-mail</h4>
					<p><?php echo $row['email']; ?></p>
				<?php } ?>
				<?php
			} else {
				echo '<p>No Record found</p>';
			}
			?>
		</div>
	</div>

	<!-- Map -->
	<div class="map-container">
		<iframe
			src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509895!2d-122.41941608468166!3d37.77492977975968!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085809c5c06c5e3%3A0x7adca473dffb6a51!2sChinatown%2C%20San%20Francisco%2C%20CA!5e0!3m2!1sen!2sus!4v1682525017263!5m2!1sen!2sus"
			width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
	</div>
</div>

<?php include 'inc/footer.php'; ?>