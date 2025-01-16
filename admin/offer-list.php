<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
}
// Include database connection
include 'config.php';

// Initialize variables
$error_message = '';
$offers = [];

// Fetch offers
try {
    $stmt = $conn->prepare("SELECT * FROM offer");
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
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <div class="page-header">
            <div class="add-item">
                <div class="page-title">
                    <h4>Offer List</h4>
                    <h6>Manage your Offer</h6>
                </div>
            </div>

            <div class="d-flex gap-2">
                <div class="page-btn">
                    <a href="add-offer.php" class="btn btn-primary">
                        <i data-feather="plus-circle" class="me-2"></i>Add New Offer
                    </a>
                </div>
                <?php if (!empty($offers)): ?>
                    <div class="page-btn">
                        <button class="btn btn-danger" onclick="deleteSelectedoffers()">
                            <i data-feather="trash-2" class="me-2"></i>Delete Data
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
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
                                <th>Offer Image</th>
                                <th>Offer Price</th>
                                <th>Status</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($offers)): ?>
                                <?php foreach ($offers as $offer): ?>
                                    <tr>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox" class="offer-checkbox"
                                                    data-id="<?php echo htmlspecialchars($offer['id']); ?>">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="offer-img">
                                                <img src="<?php echo htmlspecialchars(str_replace("../uploads/", "uploads/", $offer['image_paths'])); ?>"
                                                    alt="offer Image" style="width:80px; height:40px;">
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($offer['offer_price']); ?></td>
                                        <td>
                                            <span class="badge badge-linesuccess">
                                                <?php echo $offer['status'] == 1 ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="btn btn-sm btn-info d-flex align-items-center justify-content-center"
                                                    href="edit-offer.php?id=<?php echo htmlspecialchars($offer['id']); ?>"
                                                    style="width: 32px; height: 32px;">
                                                    <i data-feather="edit" class="text-white"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <div class="no-data-found">
                                            <i data-feather="database"
                                                style="width: 48px; height: 48px; stroke-width: 1; color: #ccc;"></i>
                                            <p class="mt-3 mb-0 text-muted">No Data Found</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .no-data-found {
        padding: 40px 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Feather Icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        // Select all functionality
        const selectAllCheckbox = document.getElementById('select-all');
        const offerCheckboxes = document.getElementsByClassName('offer-checkbox');

        selectAllCheckbox.addEventListener('change', function () {
            Array.from(offerCheckboxes).forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    });

    function deleteSelectedoffers() {
        const selectedCheckboxes = document.querySelectorAll('.offer-checkbox:checked');
        const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.dataset.id);

        if (selectedIds.length === 0) {
            Swal.fire({
                title: 'Warning',
                text: 'Please select offers to delete',
                icon: 'warning'
            });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${selectedIds.length} offer${selectedIds.length > 1 ? 's' : ''}`,
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

                fetch('delete-data/delete-offer.php', {
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
                            let message = 'Selected offers have been deleted.';
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
                            throw new Error(data.error || 'Failed to delete offers');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: error.message || 'An error occurred while deleting offers',
                            icon: 'error'
                        });
                    });
            }
        });
    }
</script>

<?php include 'inc/footer.php'; ?>