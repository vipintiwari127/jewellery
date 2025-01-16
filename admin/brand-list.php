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
$brands = [];

// Fetch brands
try {
	$stmt = $conn->prepare("SELECT * FROM brand");
	$stmt->execute();
	$result = $stmt->get_result();
	$brands = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
	$error_message = "Error fetching brands: " . $e->getMessage();
}


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
?>
<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Subcategory</h4>
                    <h6>Manage your Subcategory</h6>
                </div>
            </div>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-brand"><i
                        data-feather="plus-circle" class="me-2"></i>Add New Subcategory</a>
            </div>
            <div class="page-btn">
                <button class="btn btn-danger" onclick="deleteSelectedBrands()">Delete Selected</button>
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
                                <th>Subcategory</th>
                                <th>Category Name</th>
                                <th>Category Type</th>
                                <th>Status</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($brands as $brand): ?>
                                <?php
                                    $category_id = $brand['category_id'];
                                    $sql = "SELECT category AS category_name FROM category WHERE id = '$category_id'";
                                    $query = mysqli_query($conn, $sql);
                                    $res1 = mysqli_fetch_array($query, MYSQLI_BOTH);

                                    $type_id = $brand['category_type_id'];
                                    $sql3 = "SELECT type AS type_name FROM category WHERE id = '$type_id'";
                                    $query3 = mysqli_query($conn, $sql3);
                                    $res3 = mysqli_fetch_array($query3, MYSQLI_BOTH);
                                ?>
                                <?php if (!is_null($brand)): ?>
                                    <tr>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox" class="offer-checkbox"
                                                    data-id="<?php echo htmlspecialchars($brand['id']); ?>">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td><?php echo htmlspecialchars($brand['brand']); ?></td>
                                        <td><?php echo htmlspecialchars($res1['category_name']); ?></td>
                                        <td><?php echo htmlspecialchars($res3['type_name']); ?></td>
                                        <td>
                                            <span class="badge badge-linesuccess">
                                                <?php echo $brand['status'] == 1 ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit-brand-<?php echo $brand['id']; ?>">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Brand Modal -->
                                    <div class="modal fade" id="edit-brand-<?php echo htmlspecialchars($brand['id']); ?>">
                                        <div class="modal-dialog modal-dialog-centered custom-modal-two">
                                            <div class="modal-content">
                                                <div class="page-wrapper-new p-0">
                                                    <div class="content">
                                                        <div class="modal-header border-0 custom-modal-header">
                                                            <div class="page-title">
                                                                <h4>Edit Brand</h4>
                                                            </div>
                                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body custom-modal-body">
                                                            <form action="./insert-data/edit-brand.php" method="POST"
                                                                enctype="multipart/form-data">
                                                                <input type="hidden" name="brand_id"
                                                                    value="<?php echo htmlspecialchars($brand['id']); ?>">

                                                                <div class="mb-3">
                                                                    <label class="form-label">Category Name</label>
                                                                    <select class="form-control" id="category" name="category_id">
                                                                        <option value="" readonly>Select Category</option>
                                                                        <?php foreach ($categories as $category): ?>
                                                                            <option
                                                                                value="<?php echo htmlspecialchars($category['id']); ?>"
                                                                                <?php echo $category['id'] == $brand['category_id'] ? 'selected' : ''; ?>>
                                                                                <?php echo htmlspecialchars($category['category']); ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Category Type</label>
                                                                    <select class="form-control" id="color" name="category_type_id">
                                                                        <option value="" readonly>Select Type</option>
                                                                        <?php foreach ($types as $type): ?>
                                                                            <option
                                                                                value="<?php echo htmlspecialchars($type['id']); ?>"
                                                                                <?php echo $type['id'] == $brand['category_type_id'] ? 'selected' : ''; ?>>
                                                                                <?php echo htmlspecialchars($type['type']); ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Subcategory Name</label>
                                                                    <input type="text" class="form-control" name="brand"
                                                                        value="<?php echo htmlspecialchars($brand['brand']); ?>"
                                                                        required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <div
                                                                        class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                                                        <span class="status-label">Status</span>
                                                                        <input type="checkbox"
                                                                            id="status_<?php echo $brand['id']; ?>"
                                                                            name="status" class="check" <?php echo $brand['status'] == 1 ? 'checked' : ''; ?>>
                                                                        <label for="status_<?php echo $brand['id']; ?>"
                                                                            class="checktoggle"></label>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer-btn">
                                                                    <button type="submit" class="btn btn-submit">Save
                                                                        Changes</button>
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

<div class="modal fade" id="add-brand">
	<div class="modal-dialog modal-dialog-centered custom-modal-two">
		<div class="modal-content">
			<div class="page-wrapper-new p-0">
				<div class="content">
					<div class="modal-header border-0 custom-modal-header">
						<div class="page-title">
							<h4>Create Subcategory</h4>
						</div>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body custom-modal-body">
						<form action="./insert-data/add-brand.php" method="POST" enctype="multipart/form-data">
							<div class="mb-3">
								<label class="form-label">Category Type</label>
								<!-- <input type="text" class="form-control" name="category_id" required> -->
								<select class="form-control" id="color" name="category_type_id">
									<option value="" readonly>Select Type</option>
									<?php foreach ($types as $type): ?>
										<option value="<?php echo htmlspecialchars($type['id']); ?>">
											<?php echo htmlspecialchars($type['type']); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="mb-3">
								<label class="form-label">Category Name</label>
								<!-- <input type="text" class="form-control" name="category_id" required> -->
								<select class="form-control" id="category" name="category_id">
									<option value="" readonly>Select Category</option>
									<?php foreach ($categories as $category): ?>
										<option value="<?php echo htmlspecialchars($category['id']); ?>">
											<?php echo htmlspecialchars($category['category']); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="mb-3">
								<label class="form-label">Subcategory</label>
								<input type="text" class="form-control" name="brand" required>
							</div>
							<!-- <div class="mb-3">
								<label class="form-label">Image</label>
								<input type="file" class="form-control" name="brand_image" required>
							</div> -->
							<div class="mb-0">
								<div
									class="status-toggle modal-status d-flex justify-content-between align-items-center">
									<span class="status-label">Status</span>
									<input type="checkbox" id="status" name="status" class="check" checked>
									<label for="status" class="checktoggle"></label>
								</div>
							</div>
							<div class="modal-footer-btn">
								<!-- <button type="button" class="btn btn-cancel me-2"
									data-bs-dismiss="modal">Cancel</button> -->
								<button type="submit" class="btn btn-submit">Create Brand</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function () {
		// Image preview functionality
		const imageInputs = document.querySelectorAll('input[type="file"]');
		imageInputs.forEach(input => {
			input.addEventListener('change', function (e) {
				if (this.files && this.files[0]) {
					const file = this.files[0];

					// Validate file size
					if (file.size > 5 * 1024 * 1024) {
						alert('File size must be less than 5MB');
						this.value = '';
						return;
					}

					// Validate file type
					const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
					if (!validTypes.includes(file.type)) {
						alert('Please select a valid image file (JPG, PNG, or GIF)');
						this.value = '';
						return;
					}

					// Preview image
					const reader = new FileReader();
					reader.onload = function (e) {
						const previewContainer = input.parentElement.querySelector('.mt-2');
						if (previewContainer) {
							const img = previewContainer.querySelector('img');
							if (img) {
								img.src = e.target.result;
							}
						} else {
							const newPreview = document.createElement('div');
							newPreview.className = 'mt-2';
							newPreview.innerHTML = `
							<img src="${e.target.result}" 
								 alt="Brand image preview" 
								 class="img-thumbnail" 
								 style="max-height: 100px;">
						`;
							input.parentElement.appendChild(newPreview);
						}
					}
					reader.readAsDataURL(file);
				}
			});
		});
	});
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

	function deleteSelectedBrands() {
		const selectedCheckboxes = document.querySelectorAll('.offer-checkbox:checked');
		const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.dataset.id);

		if (selectedIds.length === 0) {
			Swal.fire({
				title: 'Warning',
				text: 'Please select brands to delete',
				icon: 'warning'
			});
			return;
		}

		Swal.fire({
			title: 'Are you sure?',
			text: `You are about to delete ${selectedIds.length} brand${selectedIds.length > 1 ? 's' : ''}`,
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

				fetch('delete-data/delete-brand.php', {
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
							let message = 'Selected Subcategory have been deleted.';
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
							throw new Error(data.error || 'Failed to delete Subcategory');
						}
					})
					.catch(error => {
						console.error('Error:', error);
						Swal.fire({
							title: 'Error!',
							text: error.message || 'An error occurred while deleting Subcategory',
							icon: 'error'
						});
					});
			}
		});
	}
</script>
<?php include 'inc/footer.php'; ?>