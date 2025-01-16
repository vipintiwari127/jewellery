<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
}
// Include database connection
include 'config.php';

// Initialize variables
$error_message = '';
$sliders = [];

// Fetch sliders
try {
    $stmt = $conn->prepare("SELECT * FROM sliders");
    $stmt->execute();
    $result = $stmt->get_result(); // Use get_result() for MySQLi
    $sliders = $result->fetch_all(MYSQLI_ASSOC); // Fetch data as an associative array
} catch (Exception $e) {
    $error_message = "Error fetching sliders: " . $e->getMessage();
}
?>
<style>
    .card {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .slider-img img {
        object-fit: cover;
        border-radius: 4px;
    }

    .checkboxs {
        position: relative;
        display: inline-block;
    }

    .checkmarks {
        position: absolute;
        top: 0;
        left: 0;
        height: 20px;
        width: 20px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 3px;
    }

    .checkboxs input:checked~.checkmarks {
        background-color: #2196F3;
    }

    .modal-dialog.modal-lg {
        max-width: 800px;
    }

    .play-video {
        padding: 0.25rem 0.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .play-video svg {
        width: 16px;
        height: 16px;
    }

    .action-table-data {
        white-space: nowrap;
    }

    .edit-delete-action {
        display: flex;
        gap: 10px;
    }




    .btn-info i {
        color: white;
        stroke-width: 2px;
    }

    .btn-info:hover i {
        color: white;
    }
</style>
</head>

<body>

    <?php include 'inc/header.php'; ?>
    <?php include 'inc/sidebar.php'; ?>

    <div class="page-wrapper">
        <div class="content">


            <div class="page-header">
                <div class="add-item">
                    <div class="page-title">
                        <h4>Slider List</h4>
                        <h6>Manage your slider</h6>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <div class="page-btn">
                        <a href="add-slider.php" class="btn btn-primary">
                            <i data-feather="plus-circle" class="me-2"></i>Add New Slider
                        </a>
                    </div>
                    <div class="page-btn">
                        <button class="btn btn-danger" onclick="deleteSelectedSliders()">
                            <i data-feather="trash-2" class="me-2"></i>Delete Data
                        </button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <input type="text" class="form-control" id="searchInput"
                                    placeholder="Search sliders...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Slider Image</th>
                                    <th>Slider Name</th>
                                    <th>Slider Videos</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($sliders)): ?>
                                    <?php foreach ($sliders as $slider): ?>
                                        <tr>
                                            <td>
                                                <label class="checkboxs">
                                                    <input type="checkbox" class="slider-checkbox"
                                                        data-id="<?php echo htmlspecialchars($slider['id']); ?>">
                                                    <span class="checkmarks"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <div class="slider-img">
                                                    <img src="<?php echo htmlspecialchars(str_replace("../uploads/", "uploads/", $slider['image_paths'])); ?>"
                                                        alt="Slider Image" style="width:80px; height:60px;">
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($slider['slider_name']); ?></td>
                                            <td>
                                                <?php if (!empty($slider['video'])): ?>
                                                    <div class="d-flex align-items-center">
                                                        <video width="80px" height="40px">
                                                            <source src="<?php echo htmlspecialchars($slider['video']); ?>"
                                                                type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                        <button class="btn btn-sm btn-primary ms-2 play-video"
                                                            data-video-url="<?php echo htmlspecialchars($slider['video']); ?>"
                                                            data-slider-name="<?php echo htmlspecialchars($slider['slider_name']); ?>">
                                                            <i data-feather="play-circle"></i> Play
                                                        </button>
                                                    </div>
                                                <?php else: ?>
                                                    <span>No video available</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="action-table-data">
                                                <div class="edit-delete-action">
                                                    <a class="btn btn-sm btn-info d-flex align-items-center justify-content-center"
                                                        href="edit-slider.php?id=<?php echo htmlspecialchars($slider['id']); ?>"
                                                        style="width: 32px; height: 32px;">
                                                        <i data-feather="edit" class="text-white"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No sliders found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoModalLabel">Video Player</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <video id="modalVideo" controls style="width: 100%;">
                        <source src="<?php echo htmlspecialchars($slider['video']); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </div>

    <!-- Required JavaScript -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Feather Icons
            feather.replace();

            // Initialize Bootstrap modal
            const videoModal = new bootstrap.Modal(document.getElementById('videoModal'));
            const modalVideo = document.getElementById('modalVideo');
            const modalTitle = document.getElementById('videoModalLabel');

            // Select all functionality
            const selectAllCheckbox = document.getElementById('select-all');
            const sliderCheckboxes = document.getElementsByClassName('slider-checkbox');

            selectAllCheckbox.addEventListener('change', function () {
                Array.from(sliderCheckboxes).forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });

            // Video player functionality
            document.querySelectorAll('.play-video').forEach(button => {
                button.addEventListener('click', function () {
                    const videoUrl = this.dataset.videoUrl;
                    const sliderName = this.dataset.sliderName;

                    modalTitle.textContent = `Video Player - ${sliderName}`;
                    modalVideo.src = videoUrl;
                    modalVideo.load();

                    videoModal.show();
                    modalVideo.play();
                });
            });

            // Stop video when modal is closed
            document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
                modalVideo.pause();
                modalVideo.currentTime = 0;
            });

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('keyup', function () {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const sliderName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    row.style.display = sliderName.includes(searchTerm) ? '' : 'none';
                });
            });
        });

        // Add this JavaScript code to your existing script
        // First, update the JavaScript function:
        function deleteSelectedSliders() {
            const selectedCheckboxes = document.querySelectorAll('.slider-checkbox:checked');
            const selectedIds = Array.from(selectedCheckboxes).map(checkbox => checkbox.dataset.id);

            if (selectedIds.length === 0) {
                Swal.fire({
                    title: 'Warning',
                    text: 'Please select sliders to delete',
                    icon: 'warning'
                });
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete ${selectedIds.length} slider${selectedIds.length > 1 ? 's' : ''}`,
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

                    fetch('delete-data/delete-slider.php', {
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
                                let message = 'Selected sliders have been deleted.';
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
                                throw new Error(data.error || 'Failed to delete sliders');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: error.message || 'An error occurred while deleting sliders',
                                icon: 'error'
                            });
                        });
                }
            });
        }
    </script>

    <?php include 'inc/footer.php'; ?>