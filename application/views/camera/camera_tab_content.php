<?php $current_index = isset($index) ? $index : '1'?>
<input type="hidden" name="camera[<?php echo $current_index ?>]" value="" class="camera_no">
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label col-sm-2">Name</label>
            <div class="col-sm-8">
                <input type="text" name="name[<?php echo $current_index ?>]" id="name" class="form-control" value="">
            </div>
        </div>
    </div>  
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label col-sm-2">Stream URL</label>
            <div class="col-sm-8">
                <input type="text" name="url[<?php echo $current_index ?>]" id="url" class="form-control" value="">
            </div>
        </div>
    </div>  
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label col-sm-2">Video URL</label>
            <div class="col-sm-8">
                <input type="text" name="video_url[<?php echo $current_index ?>]" id="video_url" class="form-control" value="">
            </div>
        </div>
    </div>  
</div>