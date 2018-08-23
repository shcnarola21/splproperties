<?php
$device_folder_arr = array();
if (!empty($user_folders)) {
    foreach ($user_folders as $u) {
        $device_folder_arr[] = $u['folder_id'];
    }
}
?>
<div id="modal_assign_folder" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="icon-plus-circle2 position-left"></i> Assign Folder</h5>
            </div>
            <div class="modal-body">
                <div class="userMessageDiv"></div>
                <form id="assign_folder_frm" class="form-horizontal">
                    <input type="hidden" name="device_id" value="<?php echo (isset($device_id)) ? $device_id : ''; ?>" />
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label col-sm-2">Folder</label>
                            <div class="col-sm-9">
                                <select multiple="multiple" name="folders[]" data-placeholder="Select Hardware" class="select-folder">
                                    <optgroup label="Select Folder">
                                        <?php
                                        if (!empty($folders)) {
                                            foreach ($folders as $f) {
                                                ?>
                                                <option <?php echo (in_array($f['id'], $device_folder_arr)) ? "selected='selected'" : '' ?> value="<?php echo $f['id'] ?>" data-icon="folder-open"><?php echo $f['name'] ?></option>                                                
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