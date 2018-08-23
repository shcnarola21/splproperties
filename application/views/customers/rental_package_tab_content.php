<?php $current_index = isset($index) ? $index : '1'?>
<input type="hidden" name="rental_addon_id[<?php echo $current_index ?>]" value="">
<div class = "row select-package">
    <div class ="col-md-12">
        <div class = "form-group">
            <label class = "col-sm-3">Name</label>
            <div class = "col-sm-8">
                <input type="text" class="form-control" name="rental_adon_name[<?php echo $current_index ?>]" value="">
            </div>
        </div>
    </div>
    <div class ="col-md-12">
        <div class = "form-group">
            <label class = "col-sm-3">Price</label>
            <div class = "col-sm-8">
                <div class = "input-group">
                    <span class = "input-group-addon"><i class = "icon-coin-dollar"></i></span>
                    <input type="text" class="form-control" name="rental_adon_price[<?php echo $current_index ?>]" value="">
                </div>
            </div>
        </div>
    </div>
</div>