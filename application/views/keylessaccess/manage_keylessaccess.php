<?php
$status_array = array('pending' => 'Pending',
    'complete' => 'Complete');
?>
<div id="modal_manage_keyless_access" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close custom_close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="<?php echo isset($keyless_access['id']) ? "icon-pencil5" : "icon-plus-circle2"; ?> position-left"></i><?php echo isset($keyless_access['id']) ? "Edit" : "Add"; ?> Keyless Access</h5>
            </div>
            <form class="form-horizontal" method="post" id="manage_keyless_access_frm">
                <input type="hidden" name="property_id" id="property_id" value="<?php echo isset($property_id) ? $property_id : '' ?>">
                <input type="hidden" name="keyless_access" value="<?php echo isset($keyless_access['id']) ? base64_encode($keyless_access['id']) : '' ?>">
                <div class="modal-body edit_customer_wrapper">
                    <div class="userMessageDiv"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-sm-2">FOB ID</label>
                                <div class="col-sm-10">
                                    <input type="text" id="fob" name="fob" class="form-control" value="<?php echo isset($keyless_access['fob']) ? $keyless_access['fob'] : '' ?>" />                                     
                                </div>
                            </div>
                        </div>  
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Unit</label>
                                <div class="col-sm-10">
                                    <select name="unit" id="unit" class="select">
                                        <option value="" data-icon="">Select Unit</option>    
                                        <?php
                                        if (isset($property_info) && !empty($property_info)) {
                                            for ($i = 1; $i <= $property_info['units']; $i++) {
                                                ?>
                                                <option  value="<?php echo $i ?>" <?php echo (isset($keyless_access['unit_no']) && $keyless_access['unit_no'] == $i) ? 'selected="selected"' : '' ?>><?php echo $i ?></option>                                                
                                                <?php
                                            }
                                        }
                                        ?>                                        
                                    </select>
                                </div>
                            </div>
                        </div>  
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Password</label>
                                <div class="col-sm-10">
                                    <input type="text" id="password" name="password" class="form-control" value="<?php echo isset($keyless_access['password']) ? $keyless_access['password'] : '' ?>" />                                     
                                </div>
                            </div>
                        </div>  
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Zone</label>
                                <div class="col-sm-10">
                                    <select multiple="multiple" name="zones[]" data-placeholder="" class="select-zones">
                                        <option value="" data-icon="">Select Zone(s)</option>    
                                        <?php
                                        if (isset($property_zones) && !empty($property_zones)) {
                                            $zone_id = array();
                                            if (isset($keyless_access['zone_id']) && !empty($keyless_access['zone_id'])) {
                                                $zone_id = explode(',', $keyless_access['zone_id']);
                                            }
                                            foreach ($property_zones as $k => $v) {
                                                ?>
                                                <option  value="<?php echo $v['id'] ?>" <?php echo in_array($v['id'], $zone_id) ? 'selected="selected"' : '' ?> data-icon="mobile2"><?php echo $v['name'] ?></option>                                                
                                                <?php
                                            }
                                        }
                                        ?>                                        
                                    </select>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions">
                        <button value="submit" class="btn btn-primary btn_keyless_access"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>
                        <button type="button" class="btn btn-default custom_close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
