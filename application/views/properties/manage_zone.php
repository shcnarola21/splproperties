<div id="modal_manage_zone" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close custom_close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="<?php echo isset($zone_info['id']) ? "icon-pencil5" : "icon-plus-circle2"; ?> position-left"></i><?php echo isset($zone_info['id']) ? "Edit" : "Add"; ?> Property Zone</h5>
            </div>
            <form class="form-horizontal" method="post" id="manage_zone_frm" >
                <input type="hidden" name="zone" value="<?php echo isset($zone_info['id']) ? base64_encode($zone_info['id']) : '' ?>">
                <input type="hidden" name="property" value="<?php echo isset($property) ? $property : '' ?>">
                <div class="modal-body edit_customer_wrapper">
                    <div class="userMessageDiv"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" id="name" name="name" class="form-control" value="<?php echo isset($zone_info['name']) ? $zone_info['name'] : '' ?>">                             
                                </div>
                            </div>
                        </div>  
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Password</label>
                                <div class="col-sm-10">
                                    <input type="text" id="password" name="password" class="form-control" value="<?php echo isset($zone_info['password']) ? $zone_info['password'] : '' ?>">                                  
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions">
                        <button value="submit" class="btn btn-primary btn_zone"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>
                        <button type="button" class="btn btn-default custom_close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
