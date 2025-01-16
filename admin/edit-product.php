<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'config.php';
include 'inc/header.php';
include 'inc/sidebar.php';

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
// Validate and get product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$product_id) {
    $_SESSION['error'] = "Invalid product ID";
    header("Location: product-list.php");
    exit();
}

// Fetch product details
try {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "product not found";
        header("Location: product-list.php");
        exit();
    }

    $product = $result->fetch_assoc();
    $stmt->close();
} catch (Exception $e) {
    $_SESSION['error'] = "Error fetching product details";
    header("Location: product-list.php");
    exit();
}
?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Edit Product</h4>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <div class="page-btn">
                        <a href="product-list.php" class="btn btn-secondary"><i data-feather="arrow-left"
                                class="me-2"></i>Back to Product List</a>
                    </div>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                            data-feather="chevron-up" class="feather-chevron-up"></i></a>
                </li>
            </ul>
        </div>

        <form action="./insert-data/edit-product.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            <div class="card">
                <div class="card-body add-product pb-0">
                    <div class="accordion-card-one accordion" id="accordionExample">
                        <div class="accordion-item">
                            <div class="accordion-header" id="headingOne">
                                <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                    aria-controls="collapseOne">
                                    <div class="addproduct-icon">
                                        <h5>
                                            <i data-feather="info" class="add-info"></i><span>Product Information</span>
                                        </h5>
                                        <a href="javascript:void(0);"><i data-feather="chevron-down"
                                                class="chevron-down-add"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row">
                                        <!-- Image Upload -->
                                        <div class="col-lg-4 col-sm-6 col-12">
                                            <div class="mb-3 add-product">
                                                <label class="form-label">Image</label>
                                                <input type="file" class="form-control" name="image"
                                                    onchange="previewImage(event)" />
                                                <div>
                                                    <img src="<?php echo str_replace('../uploads/', 'uploads/', $product['image_paths']); ?>"
                                                        alt="Image Preview"
                                                        style="max-width: 100px; max-height: 100px; margin-bottom: 10px;">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Product Name -->
                                        <div class="col-lg-3 col-sm-6 col-12">
                                            <div class="mb-3 add-product">
                                                <label class="form-label">Product Name</label>
                                                <input type="text" class="form-control" name="product_name"
                                                    value="<?php echo $product['product_name']; ?>" />
                                            </div>
                                        </div>

                                        <!-- Product Price -->
                                        <div class="col-lg-2 col-sm-6 col-12">
                                            <div class="mb-3 add-product">
                                                <label class="form-label">Product Price</label>
                                                <input type="text" class="form-control" name="product_price"
                                                    value="<?php echo $product['product_price']; ?>" />
                                            </div>
                                        </div>

                                        <!-- Product Discount Price -->
                                        <div class="col-lg-2 col-sm-6 col-12">
                                            <div class="mb-3 add-product">
                                                <label class="form-label">Product Discount Price</label>
                                                <input type="text" class="form-control" name="discount_price"
                                                    value="<?php echo $product['discount_price']; ?>" />
                                            </div>
                                        </div>
                                        <!-- Brand -->
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
                                        <div class="col-lg-3 col-sm-6 col-12">
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


                                        <!-- Description -->
                                        <div class="col-lg-12">
                                            <div class="input-blocks summer-description-box transfer mb-3">
                                                <label>Description</label>
                                                <textarea class="form-control h-100" name="description"
                                                    rows="5"><?php echo $product['description']; ?></textarea>
                                            </div>
                                        </div>

                                        <!-- Specification -->
                                        <!-- <div class="col-lg-12">
                                            <div class="input-blocks summer-description-box transfer mb-3">
                                                <label>Specification</label>
                                                <textarea class="form-control h-100" name="specification"
                                                    rows="5"><?php echo $product['specification']; ?></textarea>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Buttons -->
                <div class="col-lg-12">
                    <div class="btn-addproduct mb-4">
                        <!-- <button type="button" class="btn btn-cancel me-2">Cancel</button> -->
                        <button type="submit" class="btn btn-submit">Save Product</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
</div>
<script>
    function previewImage(event) {
        const imagePreview = document.getElementById('imagePreview');
        imagePreview.src = URL.createObjectURL(event.target.files[0]);
        imagePreview.onload = function () {
            URL.revokeObjectURL(imagePreview.src); // Free up memory
        };
    }
</script>
<?php include 'inc/footer.php'; ?>