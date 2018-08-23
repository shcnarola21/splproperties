<div class="col-md-12">
    <div class="panel panel-flat border-top-primary">
        <div class="panel-heading">
            <h6 class="panel-title"><?php echo $title; ?></h6>                    
        </div>

        <div class="panel-body">

            <div class="tabbable">
                <form method="post" id="package_add_frm" class="form-wrapper">
                    <div class="userMessageDiv"></div>
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="li_service_info active"><a href="#tab1" data-toggle="tab"><i class="icon-task"></i> &nbsp;Services</a></li>
                        <li class="li_package_info"><a href="#tab2" data-toggle="tab"><i class="icon-list"></i> &nbsp;Package</a></li>       
                        <li class="li_setupfee_info"><a href="#tab3" data-toggle="tab"><i class=" icon-coin-dollar"></i> &nbsp;Setup Fees(s)</a></li>                      
                    </ul>


                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <input type="hidden" name="package_id" value="<?php echo isset($package['id']) ? $package['id'] : ""; ?>" id="package_id">
                            <input type="hidden" name="name" value="<?php echo isset($package['name']) ? $package['name'] : ""; ?>">
                            <input type="hidden" name="old_services" class="old_services_cls" value="<?php echo isset($package['services']) ? $package['services'] : ""; ?>" >
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="display-block text-semibold">Select Services:</label>
                                        <?php
                                        if (isset($provider_services) && !empty($provider_services)) {
                                            $package_services = array();
                                            $checked = false;
                                            if (isset($package['services']) && !empty($package['services'])) {
                                                $package_services = explode(',', $package['services']);
                                            } else if (count($provider_services) == 1) {
                                                $checked = true;
                                            }
                                            foreach ($provider_services as $service) {
                                                ?>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="styled service_ck" name="package_services[]" value="<?php echo $service['service_id'] ?>" <?php echo ((in_array($service['service_id'], $package_services)) || $checked) ? 'checked="checked"' : ''; ?>>
                                                        <?php echo $service['service_name'] ?>
                                                    </label>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>                                   
                            </div>
                        </div>

                        <div class="tab-pane" id="tab2">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Type</label>
                                        <select name="package_type" id="package_type" class="form-control package_type select">
                                            <option value="Basic">Basic</option>
                                            <option value="Addon">Addon</option>
                                        </select>                                        
                                    </div>
                                </div>  
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Package Name</label>
                                        <input type="text" name="package_name" id="package_name" class="form-control" placeholder="Package Name" value="<?php echo isset($package['name']) ? $package['name'] : ''; ?>">
                                    </div>
                                </div>                                   
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group has-feedback has-feedback-left">                                        
                                        <label>Package Price</label>
                                        <input type="number" name="package_price" id="package_price" class="form-control input-xs " placeholder="Package Price" value="<?php echo isset($package['price']) ? $package['price'] : ''; ?>">
                                        <div class="form-control-feedback">
                                            <i class="icon-coin-dollar"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Term (Month(s))</label>
                                        <select name="term" id="term" class="form-control select">
                                            <?php if (isset($package['type']) && $package['type'] != 'Basic') { ?>
                                                <option <?php echo (isset($package['term']) && $package['term'] == 1) ? "selected='selected'" : "" ?> value="1">1</option>
                                            <?php } else {
                                                ?>
                                                <option value="">Select Month</option>
                                                <?php for ($i = 1; $i <= 12; $i++) { ?>
                                                    <option value="<?php echo $i ?>" <?php echo (isset($package['term']) && $package['term'] == $i) ? "selected='selected'" : "" ?>><?php echo $i; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>  
                                    </div>
                                </div>                                     
                            </div>
                        </div>
                        <div class="tab-pane" id="tab3">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Setup Fee</label>
                                        <select name="setup_fee_id" id="setupfee_options" class="form-control setupfee_options select">
                                            <option value="">Select Fee</option>  
                                            <?php
                                            if (isset($setup_fees) && !empty($setup_fees)) {
                                                foreach ($setup_fees as $setup_fee) {
                                                    ?>
                                                    <option value="<?php echo $setup_fee['id'] ?>" <?php echo (isset($package['setup_fee_id']) && $package['setup_fee_id'] == $setup_fee['id']) ? "selected='selected'" : "" ?>><?php echo $setup_fee['provience'] . '(' . $setup_fee['fees'] . ')'; ?></option>
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
                    <div class="text-right">
                        <button type="submit" class="btn bg-teal-400" id="package_btn"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>                                
                        <a href="javascript:void(0)" class="btn btn-info" id="package_cancel_btn">Cancel <i class="icon-reset position-right"></i></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
