<div id="modal_add_device" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="icon-plus-circle2 position-left"></i> Add Device</h5>
            </div>
            <div class="modal-body">
                <div class="userMessageDiv"></div>
                <form id="add_device_frm" class="form-horizontal">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label col-sm-3">Device Name : </label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Enter Device Name" name="device_name" class="form-control">
                            </div>
                        </div>   
                        
                        <div class="form-group">
                            <label class="control-label col-sm-3">Device Code :</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Enter Device Code" name="code" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-3">Select Folder(s) :</label>
                            <div class="col-sm-8">
                                <select multiple="multiple" name="folders[]" data-placeholder="Select Hardware" class="select-folder">
                                    <optgroup label="Select Folder">
                                        <?php
                                        if (!empty($folders)) {
                                            foreach ($folders as $f) {
                                                ?>
                                                <option value="<?php echo $f['id'] ?>" data-icon="folder-open"><?php echo $f['name'] ?></option>                                                
                                                <?php
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn bg-teal-400" id="spinner-dark-6"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>