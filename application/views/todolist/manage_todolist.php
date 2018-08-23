<?php
$status_array = array('pending' => 'Pending',
    'complete' => 'Complete');
?>
<div id="modal_manage_todolist" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close custom_close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="<?php echo isset($todolist_info['id']) ? "icon-pencil5" : "icon-plus-circle2"; ?> position-left"></i><?php echo isset($todolist_info['id']) ? "Edit" : "Add"; ?> Maintenace / Repair(s)</h5>
            </div>
            <form class="form-horizontal" method="post" id="manage_todolist_frm" >
                <input type="hidden" name="todolist_id" value="<?php echo isset($todolist_info['id']) ? base64_encode($todolist_info['id']) : '' ?>">
                <div class="modal-body edit_customer_wrapper">
                    <div class="userMessageDiv"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Notes</label>
                                <div class="col-sm-10">
                                    <textarea id="description" name="description" rows="6" class="form-control"><?php echo isset($todolist_info['description']) ? $todolist_info['description'] : '' ?></textarea>                                     
                                </div>
                            </div>
                        </div>  
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Status</label>
                                <div class="col-sm-10">
                                    <select class="select" id="status" name="status" class="form-control">
                                        <option value="">Select status</option>
                                        <?php foreach ($status_array as $key => $val) { ?>
                                            <option value="<?php echo $key; ?>" <?php
                                            if ((isset($todolist_info['status'])) && ($todolist_info['status'] == $key)) {
                                                echo 'selected="selected"';
                                            }
                                            ?>><?php echo $val; ?></option>
                                                <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions">
                        <button value="submit" class="btn btn-primary btn_todolist"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>
                        <button type="button" class="btn btn-default custom_close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
