<?php include 'inc/header.php'; ?>
<?php include 'inc/sidebar.php'; ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>New Offer</h4>
                    <h6>Create new Offer</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <div class="page-btn">
                        <a href="offer-list.php" class="btn btn-secondary"><i data-feather="arrow-left"
                                class="me-2"></i>Back
                            to Offer List</a>
                    </div>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                            data-feather="chevron-up" class="feather-chevron-up"></i></a>
                </li>
            </ul>
        </div>
        <form action="./insert-data/add-offer.php" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body add-product pb-0">
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
                                                            <div class="addproduct-icon list">
                                                                <h5>
                                                                    <i data-feather="image"
                                                                        class="add-info"></i><span>Offer</span>
                                                                </h5>
                                                                <a href="javascript:void(0);"><i
                                                                        data-feather="chevron-down"
                                                                        class="chevron-down-add"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-sm-6 col-12">
                                                            <div class="input-blocks add-product">
                                                                <label>Offer Image </label>
                                                                <input type="file" class="form-control" name="image_paths"
                                                                    placeholder="Choose" />
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-sm-6 col-12">
                                                            <div class="input-blocks add-product">
                                                                <label>Offer Price</label>
                                                                <input type="text" class="form-control" name="offer_price"
                                                                    placeholder="Price" />
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-sm-6 col-12">
                                                            <div class="input-blocks add-product">
                                                                <label>Status</label>
                                                                <input type="checkbox" name="status" value="1"> Active
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
</div>
<?php include 'inc/footer.php'; ?>