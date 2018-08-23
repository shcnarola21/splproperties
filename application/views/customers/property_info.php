<?php if (isset($property) && !empty($property)) { ?>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group" >
                <label class="col-lg-3">Address </label>
                <div class="col-lg-8">
                    <div><?php echo $property['address'] . ', ' . $property['city'] . ', ' . $property['state'] . ', ' . $property['zip_code'] . ', ' . $property['country'] ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group" >
                <label class="col-lg-3">Property Unit(s) </label>
                <div class="col-lg-8">
                    <div><?php echo $property['units'] ?></div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>