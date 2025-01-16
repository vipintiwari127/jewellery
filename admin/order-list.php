<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
} ?>
<?php
// Include database connection
include 'config.php';

// Initialize variables
$error_message = '';
$categories = [];

// Fetch categories
try {
    $stmt = $conn->prepare("SELECT * FROM category");
    $stmt->execute();
    $result = $stmt->get_result();
    $categories = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    $error_message = "Error fetching categories: " . $e->getMessage();
}
?>
<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Category</h4>
                    <h6>Manage your categories</h6>
                </div>
            </div>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-category"><i
                        data-feather="plus-circle" class="me-2"></i>Add New Category</a>
            </div>
            <div class="page-btn">
                <button class="btn btn-danger" onclick="deleteSelectedCategories()">Delete Selected</button>
            </div>
        </div>

        <div class="card table-list-card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-input">
                            <a href="" class="btn btn-searchset"><i data-feather="search"
                                    class="feather-search"></i></a>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th class="no-sort">
                                    <label class="checkboxs">
                                        <input type="checkbox" id="select-all">
                                        <span class="checkmarks"></span>
                                    </label>
                                </th>
                                <th>Category</th>
                                <th>Number</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <?php if (!is_null($category)): ?>
                                    <tr>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox" class="offer-checkbox" data-id="<?php echo htmlspecialchars($category['id']); ?>">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td><?php echo htmlspecialchars($category['category']); ?></td>
                                        <td><?php echo htmlspecialchars($category['number']); ?></td>
                                        <td>
                                            <img src="<?php echo htmlspecialchars(str_replace("../uploads/", "uploads/", $category['image'])); ?>"
                                                alt="category Image" style="width:80px; height:40px;">
                                        </td>
                                        <td>
                                            <span class="badge badge-linesuccess">
                                                <?php echo $category['status'] == 1 ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit-category-<?php echo $category['id']; ?>">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Category Modal -->
                                    <div class="modal fade" id="edit-category-<?php echo $category['id']; ?>">
                                        <div class="modal-dialog modal-dialog-centered custom-modal-two">
                                            <div class="modal-content">
                                                <div class="page-wrapper-new p-0">
                                                    <div class="content">
                                                        <div class="modal-header border-0 custom-modal-header">
                                                            <div class="page-title">
                                                                <h4>Edit Category</h4>
                                                            </div>
                                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body custom-modal-body">
                                                            <form action="./insert-data/edit-category.php" method="POST" enctype="multipart/form-data">
                                                                <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($category['id']); ?>">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Category Name</label>
                                                                    <input type="text" class="form-control" name="category_name" value="<?php echo htmlspecialchars($category['category']); ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Number</label>
                                                                    <input type="number" class="form-control" name="category_number" value="<?php echo htmlspecialchars($category['number']); ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Image</label>
                                                                    <input type="file" class="form-control" name="image_path">
                                                                    <?php if (!empty($category['image'])): ?>
                                                                        <div class="mt-2">
                                                                            <small class="text-muted">Current image: <?php echo htmlspecialchars($category['image']); ?></small>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="mb-0">
                                                                    <div class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                                                        <span class="status-label">Status</span>
                                                                        <input type="checkbox" id="status_<?php echo $category['id']; ?>"
                                                                               name="status" class="check"
                                                                               <?php echo $category['status'] == 1 ? 'checked' : ''; ?>>
                                                                        <label for="status_<?php echo $category['id']; ?>" class="checktoggle"></label>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer-btn">
                                                                    <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-submit">Save Changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Feather Icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // Select all functionality
        const selectAllCheckbox = document.getElementById('select-all');
        const offerCheckboxes = document.querySelectorAll('.offer-checkbox');

        selectAllCheckbox.addEventListener('change', function () {
            offerCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    });

    function deleteSelectedCategories() {
        const selectedCheckboxes = document.querySelectorAll('.offer-checkbox:checked');
        const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.dataset.id);

        if (selectedIds.length === 0) {
            Swal.fire({
                title: 'Warning',
                text: 'Please select categories to delete',
                icon: 'warning'
            });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${selectedIds.length} category${selectedIds.length > 1 ? 's' : ''}`,
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

                fetch('delete-data/delete-category.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.text().then(text => {
                            try {
                                return JSON.parse(text);
                            } catch (e) {
                                console.error('Server response:', text);
                                throw new Error('Invalid JSON response from server');
                            }
                        });
                    })
                    .then(data => {
                        if (data.success) {
                            let message = 'Selected categories have been deleted.';
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
                            throw new Error(data.error || 'Failed to delete categories');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: error.message || 'An error occurred while deleting categories',
                            icon: 'error'
                        });
                    });
            }
        });
    }
</script>
<?php include 'inc/footer.php'; ?>
