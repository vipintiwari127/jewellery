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

// Validate and get gallery ID
$gallery_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$gallery_id) {
    $_SESSION['error'] = "Invalid gallery ID";
    header("Location: gallery-list.php");
    exit();
}

// Fetch gallery details
try {
    $stmt = $conn->prepare("SELECT * FROM gallery WHERE id = ?");
    $stmt->bind_param("i", $gallery_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Gallery not found";
        header("Location: gallery-list.php");
        exit();
    }

    $gallery = $result->fetch_assoc();
    $stmt->close();
} catch (Exception $e) {
    $_SESSION['error'] = "Error fetching gallery details";
    header("Location: gallery-list.php");
    exit();
}
?>

<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Edit Gallery</h4>
                </div>
            </div>
            <div class="page-btn">
                <a href="gallery-list.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Gallery List
                </a>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="card">
            <div class="card-body">
                <form action="./insert-data/edit-gallery.php" method="POST" enctype="multipart/form-data"
                    id="editgalleryForm">
                    <input type="hidden" name="gallery_id" value="<?php echo htmlspecialchars($gallery['id']); ?>">

                    <div class="row">
                        <!-- Gallery Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gallery Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="gallery_name"
                                    value="<?php echo htmlspecialchars($gallery['gallery_name']); ?>">
                            </div>
                        </div>

                        <!-- Current Image Preview -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Current Image</label>
                                <div class="current-image mt-2">
                                    <?php if ($gallery['image_path']): ?>
                                        <img id="imagePreview"
                                            src="<?php echo str_replace("../uploads/", "uploads/", $gallery['image_path']); ?>"
                                            alt="Current Gallery Image" class="img-thumbnail" style="max-width: 200px;">
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
                                <input type="file" class="form-control" name="image_path" accept="image/*"
                                    onchange="previewImage(this)">
                                <small class="text-muted">Accepted formats: JPG, PNG, GIF. Max size: 2MB</small>
                            </div>
                            <!-- New Image Preview -->
                            <div id="imagePreview" class="mt-2" style="display: none;">
                                <img src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>
                    </div>

                    <!-- Form Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Update Gallery</button>
                            <a href="gallery-list.php" class="btn btn-secondary">Cancel</a>
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
    document.getElementById('editgalleryForm').addEventListener('submit', function (e) {
        const nameInput = document.querySelector('input[name="gallery_name"]');
        const name = nameInput.value.trim();

        if (name === '') {
            e.preventDefault();
            alert('Gallery name cannot be empty');
            return false;
        }

        const fileInput = document.querySelector('input[name="image_path"]');
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