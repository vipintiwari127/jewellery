<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
} ?>
<?php
session_start();
include 'config.php';
include 'inc/header.php';
include 'inc/sidebar.php';

// Validate and get offer ID
$offer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$offer_id) {
    $_SESSION['error'] = "Invalid offer ID";
    header("Location: offer-list.php");
    exit();
}

// Fetch offer details
try {
    $stmt = $conn->prepare("SELECT * FROM offer WHERE id = ?");
    $stmt->bind_param("i", $offer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Offer not found";
        header("Location: offer-list.php");
        exit();
    }

    $offer = $result->fetch_assoc();
    $stmt->close();
} catch (Exception $e) {
    $_SESSION['error'] = "Error fetching offer details";
    header("Location: offer-list.php");
    exit();
}
?>

<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Edit Offer</h4>
                </div>
            </div>
            <div class="page-btn">
                <a href="offer-list.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Offer List
                </a>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="card">
            <div class="card-body">
                <form action="./insert-data/edit-offer.php" method="POST" enctype="multipart/form-data"
                    id="editOfferForm">
                    <input type="hidden" name="offer_id" value="<?php echo htmlspecialchars($offer['id']); ?>">

                    <div class="row">
                        <!-- Offer Price -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Offer Price <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="offer_price"
                                    value="<?php echo htmlspecialchars($offer['offer_price']); ?>" required step="0.01"
                                    min="0">
                            </div>
                        </div>

                        <!-- Current Image Preview -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Current Image</label>
                                <div class="current-image mt-2">
                                    <?php if ($offer['image_paths']): ?>
                                        <img id="imagePreview"
                                            src="<?php echo str_replace("../uploads/", "uploads/", $offer['image_paths']); ?>"
                                            alt="Current Offer Image" class="img-thumbnail" style="max-width: 200px;">
                                    <?php else: ?>
                                        <p class="text-muted">No image currently uploaded</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- New Image Upload -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Upload New Image</label>
                                <input type="file" class="form-control" name="image_paths" accept="image/*"
                                    onchange="previewImage(this)">
                                <small class="text-muted">Accepted formats: JPG, PNG, GIF. Max size: 2MB</small>
                            </div>
                            <!-- New Image Preview -->
                            <div id="imagePreview" class="mt-2" style="display: none;">
                                <img src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div><br>

                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <div class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                <span class="status-label">Status</span>
                                <input type="checkbox" id="status_<?php echo $offer['id']; ?>" name="status"
                                    class="check" <?php echo $offer['status'] == 1 ? 'checked' : ''; ?>>
                                <label for="status_<?php echo $offer['id']; ?>" class="checktoggle"></label>
                            </div>
                        </div>
                    </div>

                    <!-- Form Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                Update Offer
                            </button>
                            <a href="offer-list.php" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Script -->
<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const previewImg = preview.querySelector('img');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function (e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }

    // Form validation
    document.getElementById('editOfferForm').addEventListener('submit', function (e) {
        const priceInput = document.querySelector('input[name="offer_price"]');
        const price = parseFloat(priceInput.value);

        if (isNaN(price) || price < 0) {
            e.preventDefault();
            alert('Please enter a valid positive price');
            return false;
        }

        const fileInput = document.querySelector('input[name="image_paths"]');
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (file.size > maxSize) {
                e.preventDefault();
                alert('Image size must be less than 2MB');
                return false;
            }

            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                e.preventDefault();
                alert('Please upload only JPG, PNG, or GIF images');
                return false;
            }
        }
    });
</script>

<?php include 'inc/footer.php'; ?>
