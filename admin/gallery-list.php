<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
}
// Include database connection
include 'config.php';

// Initialize variables
$error_message = '';
$galleries = [];

// Fetch galleries
try {
    $sql = "SELECT * FROM gallery";
    $result = $conn->query($sql);
    $galleries = $result->fetch_all(MYSQLI_ASSOC); // Fetch data as an associative array
} catch (Exception $e) {
    $error_message = "Error fetching gallery: " . $e->getMessage();
}
?>
<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>

<div class="page-wrapper">
    <div class="content">
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Gallery List</h4>
                    <h6>Manage your gallery</h6>
                </div>
            </div>

            <div class="page-btn">
                <a href="add-gallery.php" class="btn btn-added"><i data-feather="plus-circle" class="me-2"></i>Add New
                    Gallery</a>
            </div>
            <div class="page-btn">
                <button class="btn btn-danger" onclick="deleteSelectedgallery()">Delete Selected</button>
            </div>
        </div>

        <div class="card table-list-card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-input">
                            <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search"
                                    class="feather-search"></i></a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive product-list">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th class="no-sort">
                                    <label class="checkboxs">
                                        <input type="checkbox" id="select-all">
                                        <span class="checkmarks"></span>
                                    </label>
                                </th>
                                <th>Gallery Image</th>
                                <th>Gallery Image Name</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($galleries)): ?>
                                <?php foreach ($galleries as $gallery): ?>
                                    <tr>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox" class="gallery-checkbox"
                                                    data-id="<?php echo $gallery['id']; ?>">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="productimgname">
                                                <a href="javascript:void(0);" class="product-img stock-img">
                                                    <img src="<?php echo str_replace("../uploads/", "uploads/", $gallery['image_path']); ?>"
                                                        alt="" style="width:80px; height:40px;">
                                                </a>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($gallery['gallery_name']); ?></td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a href="edit-gallery.php?id=<?php echo $gallery['id']; ?>"
                                                    class="btn btn-info">Edit</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No galleries found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'inc/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Select all functionality
        const selectAllCheckbox = document.getElementById('select-all');
        const galleryCheckboxes = document.getElementsByClassName('gallery-checkbox');

        selectAllCheckbox.addEventListener('change', function () {
            Array.from(galleryCheckboxes).forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function () {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const galleryName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    row.style.display = galleryName.includes(searchTerm) ? '' : 'none';
                });
            });
        }
    });

    function deleteSelectedgallery() {
        const selectedCheckboxes = document.querySelectorAll('.gallery-checkbox:checked');
        const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.dataset.id);

        if (selectedIds.length === 0) {
            Swal.fire({
                title: 'Warning',
                text: 'Please select galleries to delete',
                icon: 'warning'
            });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${selectedIds.length} gallery${selectedIds.length > 1 ? 's' : ''}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while we delete the selected items',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const formData = new FormData();
                formData.append('ids', selectedIds.join(','));

                fetch('./delete-data/delete-gallery.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            let message = 'Selected galleries have been deleted.';
                            if (data.warnings && data.warnings.length > 0) {
                                message += '\n\nNote: Some files could not be deleted:';
                                data.warnings.forEach(warning => {
                                    message += '\n- ' + warning;
                                });
                            }

                            Swal.fire({
                                title: 'Deleted!',
                                text: message,
                                icon: 'success'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            throw new Error(data.error || 'Failed to delete galleries');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: error.message || 'An error occurred while deleting galleries',
                            icon: 'error'
                        });
                    });
            }
        });
    }
</script>