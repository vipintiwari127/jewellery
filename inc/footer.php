<!-- footer-area-start -->
<hr style="color: black;">
<div class="comtainer">
	<?php
	include 'admin/config.php';
	$sql = "SELECT * FROM information";
	$result2 = mysqli_query($conn, $sql) or die("query unsuccessful");
	if (mysqli_num_rows($result2) > 0) {
		?> 	<?php
			 while ($row = mysqli_fetch_assoc($result2)) {
				 ?>
			<div class="footer-area footer-area-4 ptb-80">
				<!-- <div class="container-fluit"> -->
				<div class="container">
					<div class="row">
						<div class="col-lg-4 col-md-5 col-sm-6 col-xs-12 mar_b-30">
							<div class="footer-wrapper">
								<div class="footer-logo">
									<a href="index.php"><img src="admin/uploads/<?php echo $row['footer_logo'];?>"
                                        style="height:100px; w-100;" alt=""></a>
								</div>

								<p>MP Jewellers: Where tradition meets elegance. Discover timeless designs crafted with
									precision to celebrate your precious moments.</p>

								<ul class="footer-social">
									<li><a href="<?php echo $row['facebook_link'];?>"><i class="fa fa-facebook"></i></a></li>
									<li><a href="<?php echo $row['twitter_link'];?>"><i class="fa fa-twitter"></i></a></li>
									<li><a href="<?php echo $row['linkedin_link'];?>"><i class="fa fa-youtube"></i></a></li>
									<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
									<li><a href="<?php echo $row['instagram_link'];?>"><i class="fa fa-instagram"></i></a></li>
								</ul>
							</div>
						</div>
						<div class="col-lg-2 col-md-3 hidden-sm col-xs-12 mar_b-30">
							<div class="footer-wrapper">
								<!-- <div class="footer-title">
								<a href="#"><h3> links</h3></a>
							</div>
							<div class="footer-wrapper">
								<ul class="usefull-link">
									<li><a href="#">Contact us</a></li>
								
									<li><a href="#">About us</a></li>
								
									<li><a href="#">Custom service</a></li>
								</ul>
							</div> -->
							</div>
						</div>
						<div class="col-lg-3 hidden-md hidden-sm col-xs-12 mar_b-30">
							<div class="footer-wrapper">
								<div class="footer-title">
									<a href="#">
										<h3> links</h3>
									</a>
								</div>
								<div class="footer-wrapper">
									<ul class="usefull-link">
										<li><a href="women.php">Women Jeweller</a></li>

										<li><a href="kids.php">Kids Jeweller</a></li>

										<li><a href="arti.php">Artificial Jeweller</a></li>
										<li><a href="silver.php">Silver Jeweller</a></li>
										<li><a href="stiling.php">Sterling Jeweller</a></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<div class="footer-wrapper">
								<div class="footer-title">
									<a href="#">
										<h3>Contact</h3>
									</a>
								</div>
								<div class="footer-wrapper">
									<ul class="usefull-link">
										<li>
											<h4>Address</h4>
											<?php echo $row['location'];?>

										</li>
										<li>
											<h4>Phone</h4>
											<a href="tel:<?php echo $row['mobile_no'];?>"><?php echo $row['mobile_no'];?></a>
										</li>
										<li>
											<h4>Email</h4>
											<a href="mailto:<?php echo $row['email'];?>"><?php echo $row['email'];?></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php
	} else {
		echo '<p>No Record found</p>';
	}
	?>
	<!-- Bootstrap JS (including Popper.js) -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
		integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
		crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
		integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
		crossorigin="anonymous"></script>
	<script src="js/vendor/jquery-1.12.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<script src="js/jquery.meanmenu.js"></script>
	<script src="js/jquery-ui.min.js"></script>
	<script src="js/wow.min.js"></script>
	<script src="js/jquery.scrolly.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/jquery.countdown.min.js"></script>
	<script src="js/jquery.nivo.slider.js"></script>
	<script src="js/plugins.js"></script>
	<script src="js/main.js"></script>
	<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"></script>

	</body>

	</html>