<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
}
include 'config.php';
include 'inc/header.php';
include 'inc/sidebar.php';

// Validate and get slider ID
$slider_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$slider_id) {
    $_SESSION['error'] = "Invalid slider ID";
    header("Location: slider-list.php");
    exit();
}

// Fetch slider details
try {
    $stmt = $conn->prepare("SELECT * FROM sliders WHERE id = ?");
    $stmt->bind_param("i", $slider_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "slider not found";
        header("Location: slider-list.php");
        exit();
    }

    $slider = $result->fetch_assoc();
    $stmt->close();
} catch (Exception $e) {
    $_SESSION['error'] = "Error fetching slider details";
    header("Location: slider-list.php");
    exit();
}
?>

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Edit Slider</h4>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <div class="page-btn">
                        <a href="slider-list.php" class="btn btn-secondary"><i data-feather="arrow-left"
                                class="me-2"></i>Back to Slider List</a>
                    </div>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                            data-feather="chevron-up" class="feather-chevron-up"></i></a>
                </li>
            </ul>
        </div>

        <form action="./insert-data/edit-slider.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $slider['id']; ?>">
            <div class="card">
                <div class="card-body add-slider pb-0">
                    <div class="accordion-card-one accordion" id="accordionExample2">
                        <div class="accordion-item">
                            <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo"
                                data-bs-parent="#accordionExample2">
                                <div class="accordion-body">
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                            aria-labelledby="pills-home-tab">
                                            <div class="accordion-card-one accordion" id="accordionExample3">
                                                <div class="accordion-item">
                                                    <div class="accordion-header" id="headingThree">
                                                        <div class="accordion-button" data-bs-toggle="collapse"
                                                            data-bs-target="#collapseThree"
                                                            aria-controls="collapseThree">
                                                            <div class="addslider-icon list">
                                                                <h5>
                                                                    <i data-feather="image"
                                                                        class="add-info"></i><span>Slider</span>
                                                                </h5>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>

                                                    <div class="row">
                                                        <div class="col-lg-12 col-sm-6 col-12">
                                                            <div class="input-blocks add-slider">
                                                                <label>Slider Image </label>
                                                                <input type="file" class="form-control" name="image"
                                                                    onchange="previewImage(event)" />
                                                                <div>
                                                                    <img src="<?php echo str_replace("../uploads/", "uploads/", $slider['image_paths']); ?>"
                                                                        alt=""
                                                                        style="max-width: 100px; max-height: 100px; margin-bottom: 10px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-sm-6 col-12">
                                                            <div class="input-blocks add-slider">
                                                                <label>Slider Video</label>
                                                                <input type="file" class="form-control" name="video"
                                                                    onchange="previewVideo(event)" />
                                                                <div>
                                                                    <video width="100" height="100" controls>
                                                                        <source
                                                                            src="<?php echo str_replace("../uploads/", "uploads/", $slider['video']); ?>"
                                                                            type="video/mp4">
                                                                        Your browser does not support the video tag.
                                                                    </video>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-sm-6 col-12">
                                                            <div class="input-blocks add-slider">
                                                                <label>Slider Name </label>
                                                                <input type="text" class="form-control"
                                                                    name="slider_name"
                                                                    value="<?php echo $slider['slider_name']; ?>" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="btn-addslider mb-4">
                    <button type="button" class="btn btn-cancel me-2">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-submit">
                        Save slider
                    </button>
                </div>
            </div>
        </form>
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
    function previewVideo(event) {
        const videoPreview = document.getElementById('videoPreview');
        videoPreview.src = URL.createObjectURL(event.target.files[0]);
        videoPreview.onload = function () {
            URL.revokeObjectURL(videoPreview.src); // Free up memory
        };
    }
</script>
<?php include 'inc/footer.php'; ?>