<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
} ?>
<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<?php
// Database configuration
include 'config.php';
// Fetch existing data
$sql = "SELECT * FROM information LIMIT 1";
$result = $conn->query($sql);
$data = $result->fetch_assoc();
?>
<div class="page-wrapper">
  <div class="content">
    <div class="page-header">
      <div class="add-item d-flex">
        <div class="page-title">
          <h4>Information</h4>
          <h6>Manage Information</h6>
        </div>
      </div>
    </div>
    <form action="./insert-data/information.php" method="post" enctype="multipart/form-data">
      <div class="card">
        <div class="card-body add-product pb-0">
          <div class="accordion-card-one accordion" id="accordionExample">
            <div class="accordion-item">
              <div class="accordion-header" id="headingOne">
                <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                  aria-controls="collapseOne">
                  <div class="addproduct-icon">
                    <h5>
                      <i data-feather="info" class="add-info"></i><span> Information</span>
                    </h5>
                    <a href="javascript:void(0);"><i data-feather="chevron-down" class="chevron-down-add"></i></a>
                  </div>
                </div>
              </div>
              <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <div class="row">
                    <div class="col-lg-4 col-sm-6 col-12">
                      <div class="mb-3 add-product">
                        <label class="form-label">Header logo</label>
                        <input type="file" name="header_logo" class="form-control" />
                        <?php if (!empty($data['header_logo'])): ?>
                          <img src="uploads/<?php echo($data['header_logo']); ?>"
                            alt="Header Logo" style="width:200px;">
                        <?php endif; ?>
                      </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                      <div class="mb-3 add-product">
                        <label class="form-label">Footer logo</label>
                        <input type="file" name="footer_logo" class="form-control" />
                        <?php if (!empty($data['footer_logo'])): ?>
                          <img src="uploads/<?php echo $data['footer_logo']; ?>" alt="Footer Logo" style="width:200px;">

                        <?php endif; ?>
                      </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-12">
                      <div class="mb-3 add-product">
                        <label class="form-label">Mobile No.</label>
                        <input type="text" name="mobile_no" class="form-control"
                          value="<?php echo !empty($data['mobile_no']) ? $data['mobile_no'] : ''; ?>"
                          placeholder="1234567890" />
                      </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                      <div class="mb-3 add-product">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control"
                          value="<?php echo !empty($data['location']) ? $data['location'] : ''; ?>"
                          placeholder="location" />
                      </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                      <div class="mb-3 add-product">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                          value="<?php echo !empty($data['email']) ? $data['email'] : ''; ?>"
                          placeholder="abc@gmail.com" />
                      </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                      <div class="mb-3 add-product">
                        <label class="form-label">Twitter Link</label>
                        <input type="text" name="twitter_link" class="form-control"
                          value="<?php echo !empty($data['twitter_link']) ? $data['twitter_link'] : ''; ?>"
                          placeholder="twitter link" />
                      </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                      <div class="mb-3 add-product">
                        <label class="form-label">Facebook Link</label>
                        <input type="text" name="facebook_link" class="form-control"
                          value="<?php echo !empty($data['facebook_link']) ? $data['facebook_link'] : ''; ?>"
                          placeholder="facebook link" />
                      </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                      <div class="mb-3 add-product">
                        <label class="form-label">Instagram Link</label>
                        <input type="text" name="instagram_link" class="form-control"
                          value="<?php echo !empty($data['instagram_link']) ? $data['instagram_link'] : ''; ?>"
                          placeholder="instagram link" />
                      </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                      <div class="mb-3 add-product">
                        <label class="form-label">Linkedin Link</label>
                        <input type="text" name="linkedin_link" class="form-control"
                          value="<?php echo !empty($data['linkedin_link']) ? $data['linkedin_link'] : ''; ?>"
                          placeholder="linkedin link" />
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
        <div class="btn-addproduct mb-4">
          <button type="button" class="btn btn-cancel me-2">
            Cancel
          </button>
          <button type="submit" class="btn btn-submit">
            Save Product
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<?php include 'inc/footer.php'; ?>