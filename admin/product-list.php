<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$error_message = '';
$products = [];

try {
    $stmt = $conn->prepare("
        SELECT * from products
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    $error_message = "Error fetching products: " . $e->getMessage();
}
?>

<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 34px;
        height: 20px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196f3;
    }

    input:checked+.slider:before {
        transform: translateX(14px);
    }

    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Product List</h4>
                    <h6>Manage your products</h6>
                </div>
            </div>
            <div class="page-btn">
                <a href="add-product.php" class="btn btn-added">
                    <i data-feather="plus-circle" class="me-2"></i>Add New Product
                </a>
            </div>
            <div class="page-btn">
                <button class="btn btn-danger" onclick="deleteSelectedProducts()">Delete Select Products</button>
            </div>
        </div>

        <div class="card table-list-card">
            <div class="card-body">
                <div class="table-responsive product-list">
                    <table id="productsTable" class="table datanew table-responsive">
                        <thead>
                            <tr>
                                <th class="no-sort">
                                    <label class="checkboxs">
                                        <input type="checkbox" id="select-all">
                                        <span class="checkmarks"></span>
                                    </label>
                                </th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Dis.Price</th>
                                <th>Brand</th>
                                <th>Type</th>
                                <th>Offer </th>
                                <th>Category</th>
                                <th>Status</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product) {
                                $category_id = $product['category'];
                                $sql = "SELECT category AS category_name FROM category WHERE id = '$category_id'";
                                $query = mysqli_query($conn, $sql);
                                $res1 = mysqli_fetch_array($query, MYSQLI_BOTH);

                                $brand_id = $product['brand'];
                                $sql2 = "SELECT brand AS brand_name FROM brand WHERE id = '$brand_id'";
                                $query2 = mysqli_query($conn, $sql2);
                                $res2 = mysqli_fetch_array($query2, MYSQLI_BOTH);

                                $type_id = $product['type'];
                                $sql3 = "SELECT type AS type_name FROM category WHERE id = '$type_id'";
                                $query3 = mysqli_query($conn, $sql3);
                                $res3 = mysqli_fetch_array($query3, MYSQLI_BOTH);

                                $offer_id = $product['offer_price'];
                                $sql4 = "SELECT offer_price AS offer FROM offer WHERE id = '$offer_id'";
                                $query4 = mysqli_query($conn, $sql4);
                                $res4 = mysqli_fetch_array($query4, MYSQLI_BOTH);
                                ?>
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox" class="product-checkbox"
                                                data-id="<?php echo $product['id']; ?>">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <img src="<?php echo str_replace("../uploads/", "uploads/", $product['image_paths']); ?>"
                                            alt="Product Image" style="width:90px; height:60px;">
                                    </td>
                                    <td><?php echo $product['product_name']; ?></td>
                                    <td><?php echo $product['product_price']; ?></td>
                                    <td><?php echo $product['discount_price']; ?></td>
                                    <td>
                                        <?php
                                        // Use the alias 'category' to access the category name
                                        echo isset($res2['brand_name']) ? $res2['brand_name'] : "Unknown";
                                        ?>
                                    </td>
                                    <td><?php
                                    // Use the alias 'category' to access the category name
                                    echo isset($res3['type_name']) ? $res3['type_name'] : "Unknown";
                                    ?></td>
                                    <td><?php
                                    // Use the alias 'category' to access the category name
                                    echo isset($res4['offer']) ? $res4['offer'] : "Unknown";
                                    ?></td>
                                    <td><?php
                                    // Use the alias 'category' to access the category name
                                    echo isset($res1['category_name']) ? $res1['category_name'] : "Unknown";
                                    ?></td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="status-toggle"
                                                data-id="<?php echo $product['id']; ?>" <?php echo $product['status'] ? 'checked' : ''; ?>>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>

                                    <td>
                                        <a href="javascript:void(0);" class="show-product-btn" data-bs-toggle="modal"
                                            data-bs-target="#productDetailsModal" data-product='<?php echo json_encode([
                                                'id' => $product['id'],
                                                'product_name' => $product['product_name'],
                                                'product_price' => $product['product_price'],
                                                'discount_price' => $product['discount_price'],
                                                'brand_name' => isset($res2['brand_name']) ? $res2['brand_name'] : 'Unknown',
                                                'category_name' => isset($res1['category_name']) ? $res1['category_name'] : 'Unknown',
                                                'type_name' => isset($res1['type_name']) ? $res1['type_name'] : 'Unknown',
                                                'offer' => isset($res1['offer']) ? $res1['offer'] : 'Unknown',
                                                'image_paths' => str_replace("../uploads/", "uploads/", $product['image_paths']),
                                                'description' => $product['description'],
                                            ]); ?>'>
                                            <i data-feather="eye" class="feather-show"></i>
                                        </a>

                                        <a class="me-2 p-2" href="edit-product.php?id=<?php echo $product['id']; ?>">
                                            <i data-feather="edit" class="feather-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Product Details Modal -->
        <div class="modal fade" id="productDetailsModal" tabindex="-1" aria-labelledby="productDetailsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productDetailsModalLabel">Product Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img id="modalProductImage" src="" alt="Product Image" class="img-fluid"
                                    style="width: 160%; height:300px;">
                            </div>
                            <div class="col-md-8">
                                <p><strong>Name:</strong> <span id="modalProductName"></span></p>
                                <p><strong>Price:</strong> <span id="modalProductPrice"></span></p>
                                <p><strong>Discount Price:</strong> <span id="modalDiscountPrice"></span></p>
                                <p><strong>Brand:</strong> <span id="modalBrand"></span></p>
                                <p><strong>Category:</strong> <span id="modalCategory"></span></p>
                                <p><strong>Category Type:</strong> <span id="modalCategorytype"></span></p>
                                <p><strong>Offer:</strong> <span id="modaloffer"></span></p>
                                <p><strong>Description:</strong> <span id="modalDescription"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        feather.replace(); // Initialize Feather Icons

        const eyeIcons = document.querySelectorAll('.show-product-btn');
        eyeIcons.forEach(icon => {
            icon.addEventListener('click', function () {
                const product = JSON.parse(this.getAttribute('data-product'));

                // Populate modal fields with product data
                document.getElementById('modalProductImage').src = product.image_paths;
                document.getElementById('modalProductName').textContent = product.product_name;
                document.getElementById('modalProductPrice').textContent = product.product_price;
                document.getElementById('modalDiscountPrice').textContent = product.discount_price;
                document.getElementById('modalBrand').textContent = product.brand_name;
                document.getElementById('modalCategory').textContent = product.category_name;
                document.getElementById('modalCategorytype').textContent = product.type_name;
                document.getElementById('modaloffer').textContent = product.offer;
                document.getElementById('modalDescription').textContent = product.description;
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const statusToggles = document.querySelectorAll('.status-toggle');

        statusToggles.forEach(toggle => {
            toggle.addEventListener('change', function () {
                const productId = this.getAttribute('data-id');
                const status = this.checked ? 1 : 0;

                fetch('update-status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${productId}&status=${status}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Updated!', 'Product status has been updated.', 'success');
                        } else {
                            Swal.fire('Error!', data.error || 'Unable to update status.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error!', 'Something went wrong!', 'error');
                    });
            });
        });
    });


    function deleteSelectedProducts() {
        const selectedCheckboxes = document.querySelectorAll('.product-checkbox:checked');
        const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.getAttribute('data-id'));

        if (selectedIds.length === 0) {
            Swal.fire('Error!', 'Please select products to delete', 'error');
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${selectedIds.length} products!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete them!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while we delete the selected products',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send delete request
                fetch('delete-data/delete-products.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'ids=' + selectedIds.join(',')
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.message || 'Selected products have been deleted.',
                                allowOutsideClick: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            throw new Error(data.error || 'Failed to delete products');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error.message || 'Something went wrong while deleting products!'
                        });
                    });
            }
        });
    }
</script>