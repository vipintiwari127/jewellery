<div class="mainmenu-area home-page-2 mainmenu-area-4" id="main_h" style="height: 55px;">
	<div class="container-fluit">
		<?php
		include 'admin/config.php';

		$sql = "SELECT DISTINCT c.id AS category_id, c.category AS category_name
                FROM category c
                JOIN products p ON c.id = p.category";
		$result2 = mysqli_query($conn, $sql) or die("Query unsuccessful");

		if (mysqli_num_rows($result2) > 0) {
			?>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-lg-12">
					<div class="mainmenu hidden-xs d-flex align-item-center" style="justify-content: space-evenly;">
						<div class="logo" style="margin-left: 15px;">
							<img src="img/logo-removebg-preview.png" style="width: 53px;" alt="Logo">
						</div>
						<div>
							<ul>
								<li><a href="index.php">Home</a></li>

								<?php
								while ($row = mysqli_fetch_assoc($result2)) {
									$category_id = $row['category_id'];
									$type_query = "SELECT DISTINCT t.id AS type_id, t.type AS type_name
                                                 FROM category t
                                                 JOIN products p ON t.id = p.type
                                                 WHERE p.category = '$category_id'";
									$type_result = mysqli_query($conn, $type_query);
									?>
									<li class="mega-menu-parent">
									<a href="category.php?category=<?php echo $row['category_id']; ?>"><?php echo $row['category_name']; ?></a>
										<div class="megamenu">
											<div class="type-container">
												<?php
												while ($type_row = mysqli_fetch_assoc($type_result)) {
													$type_id = $type_row['type_id'];
													$brand_query = "SELECT DISTINCT b.brand AS brand_name
                                                                  FROM brand b
                                                                  JOIN products p ON b.id = p.brand
                                                                  WHERE p.category = '$category_id'
                                                                  AND p.type = '$type_id'";
													$brand_result = mysqli_query($conn, $brand_query);
													?>
													<div class="type-group">
														<a href="#" class="megatitle"><?php echo $type_row['type_name']; ?></a>
														<div class="brand-list">
															<?php
															while ($brand_row = mysqli_fetch_assoc($brand_result)) {
																echo '<a href="text.php?brand=' . urlencode($brand_row['brand_name']) . '">' . $brand_row['brand_name'] . '</a>';
															}
															?>
														</div>
													</div>
												<?php } ?>
											</div>
										</div>
									</li>
								<?php } ?>
								<li class="search-bar">
									<form action="search.php" method="GET">
										<input type="text" name="query" placeholder="Search..." />
										<button type="submit"><i class="fa fa-search"></i></button>
									</form>
								</li>
								<li><a href="contact.php">Contact</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<?php
		} else {
			echo '<p>No Record found</p>';
		}
		?>
	</div>
</div>

<style>
	.mega-menu-parent {
    position: relative;
}

.megamenu {
    display: none;
    position: fixed; /* Changed from absolute to fixed */
    background: white;
    padding: 10px; /* Adjusted for better spacing */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    min-width: 150px;
    left: 50%; /* Centered horizontally */
    transform: translateX(-50%);
}

.mainmenu li:hover .megamenu {
    display: block;
}

.type-container {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    gap: 5px; /* Reduced gap between items */
    justify-content: flex-start;
}

.type-group {
    min-width: 60px; /* Adjusted for better layout */
    padding: 5px; /* Reduced padding */
    flex: 0 1 auto;
}

.megatitle {
    display: block;
    font-weight: bold;
    margin-bottom: 5px; /* Reduced margin */
    color: #333;
    text-transform: uppercase;
    border-bottom: 1px solid #eee;
    padding-bottom: 2px; /* Reduced padding */
    font-size: 0.8em; /* Adjusted for readability */
}

.brand-list {
    display: flex;
    flex-direction: column;
    gap: 2px; /* Reduced gap between brand links */
}

.brand-list a {
    padding: 1px 0; /* Reduced padding for links */
    color: #666;
    text-decoration: none;
    transition: color 0.2s ease;
    font-size: 0.8em; /* Adjusted font size */
}

.brand-list a:hover {
    color: #000;
}

/* Ensure the mega menu looks good on smaller screens */
@media screen and (max-width: 1200px) {
    .megamenu {
        min-width: 120px; /* Adjusted for smaller screens */
    }

    .type-group {
        min-width: 50px; /* Adjusted for smaller screens */
    }
}

</style>



<div class="mobile-menu-area hidden-sm hidden-md hidden-lg">
    <div class="container">
        <?php
        include 'admin/config.php';

        $sql = "SELECT DISTINCT c.id AS category_id, c.category AS category_name
                FROM category c
                JOIN products p ON c.id = p.category";
        $result2 = mysqli_query($conn, $sql) or die("Query unsuccessful");

        if (mysqli_num_rows($result2) > 0) {
        ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="mobile-menu">
                        <nav id="mobile-menu">
                            <ul class="nav-menu">
                                <li class="active"><a href="index.php">Home</a></li>
                                <?php
                                while ($row = mysqli_fetch_assoc($result2)) {
                                    $category_id = $row['category_id'];
                                    $type_query = "SELECT DISTINCT t.id AS type_id, t.type AS type_name
                                                 FROM category t
                                                 JOIN products p ON t.id = p.type
                                                 WHERE p.category = '$category_id'";
                                    $type_result = mysqli_query($conn, $type_query);
                                ?>
                                    <li class="menu-item-has-children">
									<a href="category.php?category=<?php echo $category_id; ?>">
											<?php echo htmlspecialchars($row['category_name']); ?>
										</a>
										
                                        <ul class="sub-menu">
                                            <?php
                                            while ($type_row = mysqli_fetch_assoc($type_result)) {
                                                $type_id = $type_row['type_id'];
                                                $brand_query = "SELECT DISTINCT b.brand AS brand_name
                                                              FROM brand b
                                                              JOIN products p ON b.id = p.brand
                                                              WHERE p.category = '$category_id'
                                                              AND p.type = '$type_id'";
                                                $brand_result = mysqli_query($conn, $brand_query);
                                            ?>
                                                <li class="menu-item-has-children">
                                                    <a href="#" class="">
                                                        <?php echo $type_row['type_name']; ?>
                                                        
                                                    </a>
                                                    <ul class="sub-menu">
                                                        <?php
                                                        while ($brand_row = mysqli_fetch_assoc($brand_result)) {
                                                            echo '<li><a href="text.php?brand=' . urlencode($brand_row['brand_name']) . '">' . $brand_row['brand_name'] . '</a></li>';
                                                        }
                                                        ?>
                                                    </ul>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>
                                <li><a href="contact.php">Contact</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        <?php
        } else {
            echo '<p>No Record found</p>';
        }
        ?>
    </div>
</div>

<style>
.mobile-menu-area {
    background: #fff;
    padding: 10px 0;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.nav-menu {
    margin: 0;
    padding: 0;
    list-style: none;
}

.nav-menu li {
    position: relative;
    display: block;
    border-bottom: 1px solid #eee;
}

.nav-menu li:last-child {
    border-bottom: none;
}

.nav-menu li a {
    padding: 12px 15px;
    display: block;
    color: #333;
    text-decoration: none;
    font-size: 14px;
}

.dropdown-toggle {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dropdown-icon {
    font-size: 18px;
    color: #666;
    transition: transform 0.3s ease;
}

.sub-menu {
    display: none;
    list-style: none;
    padding: 0;
    margin: 0;
    background: #f9f9f9;
}

.sub-menu li a {
    padding-left: 25px;
    font-size: 13px;
}

.sub-menu .sub-menu li a {
    padding-left: 35px;
}

.menu-item-has-children.active > .dropdown-toggle .dropdown-icon {
    transform: rotate(45deg);
}

.menu-item-has-children.active > .sub-menu {
    display: block;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.parentElement;
            const isActive = parent.classList.contains('active');
            
            // Close all other open menus at the same level
            const siblings = parent.parentElement.children;
            Array.from(siblings).forEach(sibling => {
                if (sibling !== parent) {
                    sibling.classList.remove('active');
                }
            });
            
            // Toggle current menu
            parent.classList.toggle('active');
            
            // Update icon
            const icon = this.querySelector('.dropdown-icon');
            icon.textContent = isActive ? '+' : '-';
        });
    });
});
</script>