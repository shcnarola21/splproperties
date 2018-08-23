<?php
if (isset($services_arr) && !empty($services_arr)) {
    $services_arr = $services_arr[0];
}
?>
<div id="modal_manage_service" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close custom_close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="<?php echo isset($services_arr['id']) ? "icon-pencil" : "icon-plus-circle2"; ?> position-left"></i> <?php echo isset($services_arr['id']) ? "Edit" : "Add"; ?> Service</h5>
            </div>
            <div class="modal-body">
                <div class="userMessageDiv"></div>
                <form class="form-horizontal" method="post" id="service_frm">
                    <input type="hidden" id="service_id" name="service_id" value="<?php echo (isset($services_arr['id']) ? base64_encode($services_arr['id']) : ''); ?>" class="form-control">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label col-sm-3">Name :</label>
                            <div class="col-sm-8">
                                <input type="text" id="name" name="name" value="<?php echo (isset($services_arr['service_name']) ? $services_arr['service_name'] : ''); ?>" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-teal-400" id="add_service_btn"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>
                        <button type="button" class="btn btn-default custom_close" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
