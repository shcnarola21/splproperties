<?php
$service_name = isset($service) && !empty($service) ? '(' . $service[0]['service_name'] . ')' : '';
$states_arr = array('Ontario', 'Quebec', 'Other');
?>
<div id="modal_add_setupfee" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="<?php echo isset($setup_fees_arr['id']) ? "icon-pencil" : "icon-plus-circle2"; ?> position-left"></i> <?php echo isset($setup_fees_arr['id']) ? "Edit" : "Add"; ?> Setup Fee <?php echo $service_name ?></h5>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" id="setup_fee_frm">
                    <div class="userMessageDiv"></div>
                    <input type="hidden" id="service_id" name="fee_id" value="<?php echo isset($setup_fees_arr['id']) ? base64_encode($setup_fees_arr['id']) : ''; ?>" class="form-control">
                    <input type="hidden" id="service_id" name="service_id" value="<?php echo isset($service_id) ? base64_encode($service_id) : ''; ?>" class="form-control">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label col-lg-3">Province /State :</label>
                            <div class="col-lg-8">
                                 <select class="form-control select" name="provience" id="provience">
                                    <?php foreach ($states_arr as $value) { ?>
                                        <option value="<?php echo $value ?>" <?php echo isset($setup_fees_arr['provience']) && $setup_fees_arr['provience'] == $value ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group has-feedback has-feedback-left">                                        
                            <label class="control-label col-lg-3">Price</label>
                            <div class="col-lg-8">
                                <input type="number" name="setup_fees" class="form-control" placeholder="Fee" value="<?php echo isset($setup_fees_arr['fees']) ? $setup_fees_arr['fees'] : ''; ?>">
                                <div class="form-control-feedback">
                                    <i class="icon-coin-dollar"></i>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn bg-teal-400" id="add_setupfee_btn"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
