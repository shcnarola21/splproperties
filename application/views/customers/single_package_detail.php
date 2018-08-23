<?php if (isset($package_info) && !empty($package_info)) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <h5 class="col-lg-12 display-block text-semibold">Package Information</h5>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-lg-4"><i class="icon-grid6 text-primary"></i>  <b>Package Name</b></label>
                <div class="col-lg-8">
                    <div class="input-group">
                        <div class="package_name"><?php echo isset($package_info['name']) ? $package_info['name'] : '-'; ?></div>
                    </div>
                </div>
            </div>
        </div>                               
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-lg-4"><i class="icon-cash text-primary"></i>  <b>Package Price</b></label>
                <div class="col-lg-8">
                    <div class="input-group">
                        <div class="package_price"><?php echo isset($package_info['price']) ? $package_info['price'] : '-'; ?></div>
                    </div>
                </div>
            </div>
        </div>                               
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-lg-4"><i class="icon-books text-primary"></i>  <b>Terms</b></label>
                <div class="col-lg-8">
                    <div class="input-group">
                        <div class="package_term"><?php echo isset($package_info['term']) ? $package_info['term'] : '-'; ?></div>
                    </div>
                </div>
            </div>
        </div>                               
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-lg-4"><i class="icon-task text-primary"></i>  <b>Services</b></label>
                <div class="col-lg-8">
                    <div class="input-group">
                        <div class="package_service"><?php echo isset($package_info['service_names']) ? $package_info['service_names'] : '-'; ?></div>
                    </div>
                </div>
            </div>
        </div>                               
    </div>
<?php } ?>