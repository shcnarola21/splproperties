<?php
$credit_types = array('General' => 'General',
    'Last Month' => 'Last Month',
    'Damage(s)' => 'Damage(s)');
?>
<div id="modal_manage_credits" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close custom_close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="<?php echo isset($credit_info['id']) ? "icon-pencil5" : "icon-plus-circle2"; ?> position-left"></i><?php echo isset($credit_info['id']) ? "Edit" : "Add"; ?> Credit</h5>
            </div>
            <form class="form-horizontal" method="post" id="manage_credit_frm">
                <input type="hidden" name="note_id" value="<?php echo isset($credit_info['id']) ? base64_encode($credit_info['id']) : '' ?>">
                <div class="modal-body edit_customer_wrapper">
                    <div class="userMessageDiv"></div>
                    <input type="hidden" value="<?php echo isset($credit_for) ? $credit_for : '' ?>" name="credit_for"/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Type</label>
                                <div class="col-sm-10">
                                    <select class="select" id="credit_type" name="credit_type" class="form-control">
                                        <option value="">Select type</option>
                                        <?php foreach ($credit_types as $key => $val) { ?>
                                            <option value="<?php echo $key; ?>" <?php
                                            if (((isset($credit_info['type'])) && ($credit_info['status'] == $key)) || ($key == 'General')) {
                                                echo 'selected="selected"';
                                            }
                                            ?>><?php echo $val; ?></option>
                                                <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Credit</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-coin-dollar"></i></span>
                                        <input type="text" class="form-control" name="credit_amount" id="credit_amount" value="<?php echo isset($credit_info['id']) ? base64_encode($credit_info['id']) : '' ?>">
                                    </div>
                                </div>
                            </div>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" rows="2" name="description" id="description"><?php echo isset($credit_info['id']) ? base64_encode($credit_info['id']) : '' ?></textarea>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions">
                        <button value="submit" class="btn btn-primary btn_credits"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>
                        <button type="button" class="btn btn-default custom_close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
