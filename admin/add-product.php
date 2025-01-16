<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Include database connection
include 'config.php';

// Fetch active brands
$brands = [];
try {
  $stmt = $conn->prepare("SELECT id, brand FROM brand WHERE status = '1'");
  $stmt->execute();
  $result = $stmt->get_result();
  $brands = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
  $error_message = "Error fetching brands: " . $e->getMessage();
}

// Fetch active categories
$categories = [];
try {
  $stmt = $conn->prepare("SELECT id, category FROM category WHERE status = '1'");
  $stmt->execute();
  $result = $stmt->get_result();
  $categories = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
  $error_message = "Error fetching categories: " . $e->getMessage();
}

// Fetch active variants for colors
$types = [];
try {
  $stmt = $conn->prepare("SELECT id, type FROM category WHERE status = '1'");
  $stmt->execute();
  $result = $stmt->get_result();
  $types = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
  $error_message = "Error fetching types: " . $e->getMessage();
}

// // Fetch active variants for sizes
$offers = [];
try {
  $stmt = $conn->prepare("SELECT id, offer_price FROM offer WHERE status = '1'");
  $stmt->execute();
  $result = $stmt->get_result();
  $offers = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
  $error_message = "Error fetching offers: " . $e->getMessage();
}
?>

<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>

<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="add-item d-flex">
        <div class="page-title">
          <h4>New Product</h4>
          <h6>Create a new product entry</h6>
        </div>
      </div>
      <ul class="table-top-head">
        <li>
          <div class="page-btn">
            <a href="product-list.php" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Back to
              Product List</a>
          </div>
        </li>
        <li>
          <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
              data-feather="chevron-up" class="feather-chevron-up"></i></a>
        </li>
      </ul>
    </div>

    <form action="./insert-data/add-product.php" method="POST" enctype="multipart/form-data">
      <div class="card">
        <div class="card-body add-product pb-0">
          <div class="accordion-card-one accordion" id="accordionExample">
            <div class="accordion-item">
              <div class="accordion-header" id="headingOne">
                <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                  aria-controls="collapseOne">
                  <div class="addproduct-icon">
                    <h5><i data-feather="info" class="add-info"></i><span>Product Information</span></h5>
                    <a href="javascript:void(0);"><i data-feather="chevron-down" class="chevron-down-add"></i></a>
                  </div>
                </div>
              </div>
              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                      <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image_paths"
                          placeholder="Enter image" />
                      </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                      <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name"
                          placeholder="Enter product name" required />
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-6 col-12">
                      <div class="mb-3">
                        <label for="product_price" class="form-label">Price (₹)</label>
                        <input type="number" class="form-control" id="product_price" name="product_price"
                          placeholder="Enter price" required />
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-6 col-12">
                      <div class="mb-3">
                        <label for="discount_price" class="form-label">Discount Price (₹)</label>
                        <input type="number" class="form-control" id="discount_price" name="discount_price"
                          placeholder="Enter discount price" />
                      </div>
                    </div>
                    <!-- <div class="col-lg-2 col-sm-6 col-12">
                      <div class="mb-3">
                        <label for="shipping_price" class="form-label">Shipping Price (₹)</label>
                        <input type="number" class="form-control" id="shipping_price" name="shippingPrice"
                          placeholder="Enter shipping  price" />
                      </div>
                    </div> -->
                    <!-- <div class="col-lg-2 col-sm-6 col-12">
                      <div class="mb-3">
                        <label for="rating" class="form-label">Rating</label>
                        <input type="number" step="0.1" class="form-control" id="rating" name="rating"
                          placeholder="Enter rating" min="0" max="10" />
                      </div>
                    </div> -->
                    <div class="col-lg-2 col-sm-6 col-12">
                      <div class="mb-3">
                        <label for="brand" class="form-label">Subcategory</label>
                        <select class="form-control" id="brand" name="brand">
                          <option value="" readonly>Select Subcategory</option>
                          <?php foreach ($brands as $brand): ?>
                            <option value="<?php echo htmlspecialchars($brand['id']); ?>">
                              <?php echo htmlspecialchars($brand['brand']); ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-6 col-12">
                      <div class="mb-3">
                        <label for="color" class="form-label">Category Type</label>
                        <select class="form-control" id="color" name="type">
                          <option value="" readonly>Select Type</option>
                          <?php foreach ($types as $type): ?>
                            <option value="<?php echo htmlspecialchars($type['id']); ?>">
                              <?php echo htmlspecialchars($type['type']); ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-6 col-12">
                      <div class="mb-3">
                        <label for="size" class="form-label">Offer</label>
                        <select class="form-control" id="size" name="offer_price">
                          <option value="" readonly>Select Offer</option>
                          <?php foreach ($offers as $offer): ?>
                            <option value="<?php echo htmlspecialchars($offer['id']); ?>">
                              <?php echo htmlspecialchars($offer['offer_price']); ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-2 col-sm-6 col-12">
                      <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-control" id="category" name="category">
                          <option value="" readonly>Select Category</option>
                          <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category['id']); ?>">
                              <?php echo htmlspecialchars($category['category']); ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <!-- <div class="col-lg-2 col-sm-6 col-12">
                      <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-control" id="gender" name="gender">
                          <option value="" readonly>Select Gender</option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                          <option value="Unisex">Unisex</option>
                        </select>
                      </div>
                    </div> -->
                    <!-- <div class="col-lg-3 col-sm-6 col-12">
                      <div class="mb-3">
                        <label for="product_type" class="form-label">Product Type</label>
                        <input type="text" class="form-control" id="product_type" name="product_type"
                          placeholder="Enter product type" />
                      </div>
                    </div> -->
                    <!-- <div class="col-lg-2 col-sm-6 col-12">
                      <div class="mb-3">
                        <label for="sales" class="form-label">Sales</label>
                        <input type="text" class="form-control" id="sales" name="sales"
                          placeholder="Enter sales count" />
                      </div>
                    </div> -->

                    <div class="col-lg-12">
                      <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5"
                          placeholder="Enter product description"></textarea>
                      </div>
                    </div>
                    <!-- <div class="col-lg-12">
                      <div class="mb-3">
                        <label for="specification" class="form-label">Specification</label>
                        <textarea class="form-control" id="specification" name="specification" rows="5"
                          placeholder="Enter product specification"></textarea>
                      </div>
                    </div> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="btn-addproduct mb-4">
          <button type="button" class="btn btn-cancel me-2">Cancel</button>
          <button type="submit" class="btn btn-submit">Save Product</button>
        </div>
      </div>
    </form>
  </div>
</div>

<?php include 'inc/footer.php'; ?>