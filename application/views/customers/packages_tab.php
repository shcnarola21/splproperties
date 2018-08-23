<?php
if (isset($customer)) {
    $package_data['package_info'] = $package_info;
    $addon_display = 'display:none;';

    if (isset($package_addons) && !empty($package_addons)) {
        $addon_display = 'display:block;';
    }
    $is_rental_service_available = is_rental_service_available();

    $packages_services_array = array();
    if (isset($provider_services) && !empty($provider_services)) {
        $new_provider_services = array_column($provider_services, 'service_id');
        if (isset($provider_packages) && !empty($provider_packages)) {
            foreach ($provider_packages as $p) {
                $package_service = explode(',', $p['services']);
                if (count($package_service) > 1) {
                    $packages_services_array['Bundle'][] = array('id' => $p['id'], 'name' => $p['name'], 'price' => $p['price']);
                } else {
                    if (!empty($package_service) && in_array($package_service[0], $new_provider_services)) {
                        $service_key = array_search($package_service[0], $new_provider_services);
                        if (is_numeric($service_key)) {
                            $packages_services_array[$provider_services[$service_key]['service_name']][] = array('id' => $p['id'], 'name' => $p['name'], 'price' => $p['price']);
                        }
                    }
                }
            }
        }
    }
}
?>

<div class="row">
    <form method="POST" class="form-horizontal" id="customer_package_frm" name="customer_package_frm">
        <div class="row">
            <div class="userMessageDiv col-md-12"></div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-white border-top-primary">
                    <div class="panel-heading custom_info_head">
                        <h6 class="panel-title text-semibold">Packages</h6>
                        <div class="heading-elements">
                        </div>
                    </div>
                    <div class="panel-body container-fluid">
                        <?php if ($is_rental_service_available) { ?>
                            <div class = "row select-package">
                                <input type = "hidden" name = "rental_package_id" value = "<?php echo isset($rental_package) && !empty($rental_package) ? base64_encode($rental_package['id']) : '' ?>"/>
                                <div class ="col-md-12">
                                    <div class = "form-group">
                                        <label class = "col-sm-3">Package Name</label>
                                        <div class = "col-sm-8">
                                            <input type = "text" class = "form-control" name = "package_name" id ="package_name" value = "<?php echo isset($rental_package) && !empty($rental_package) ? $rental_package['name'] : '' ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class ="col-md-12">
                                    <div class = "form-group">
                                        <label class = "col-sm-3">Package Price</label>
                                        <div class = "col-sm-8">
                                            <div class = "input-group">
                                                <span class = "input-group-addon"><i class = "icon-coin-dollar"></i></span>
                                                <input type = "text" class = "form-control" name = "package_price" id ="package_price" value = "<?php echo isset($rental_package) && !empty($rental_package) ? $rental_package['price'] : '' ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="row select-package">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3">Select Package</label>
                                        <div class="col-lg-8">
                                            <select title="Select package" class="form-control customer_order_package select-search" id="customerview_package" data-live-search="true" data-width="100%" name="customer_package">
                                                <option value="">Select Package</option>
                                                <?php
                                                if (!empty($packages_services_array)) {
                                                    foreach ($packages_services_array as $key => $value) {
                                                        ?>
                                                        <optgroup class="package_service_lable" label="<?php echo $key; ?>">
                                                            <?php
                                                            foreach ($value as $S_package) {
                                                                $selected = '';
                                                                if ($package_info['id'] == $S_package['id']) {
                                                                    $selected = 'selected="selected"';
                                                                }
                                                                ?>
                                                                <option value="<?php echo $S_package['id']; ?>" <?php echo $selected; ?>><?php echo $S_package['name']; ?> - $<?php echo $S_package['price']; ?></option>
                                                            <?php } ?>
                                                        </optgroup>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select> 
                                        </div>
                                    </div>                               
                                </div>                               
                            </div>
                            <div class="row">
                                <div class="col-md-12 custom-tab-form-content">
                                    <div class="package_info custom_info">
                                        <?php $this->load->view('customers/single_package_detail', $package_data); ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 customer_addon_cls" style="<?php echo $addon_display ?>">
                <div class="panel panel-white border-top-primary loader-wrap">
                    <div class="panel-heading custom_info_head">
                        <h6 class="panel-title text-semibold">Addon(s) 
                            <div class="loader_div hide">
                                <span><img src="assets/images/gif-loader-blue.png" alt=""/></span>
                            </div></h6>
                        <div class="heading-elements">
                            <?php if ($is_rental_service_available) { ?>
                                <a href="javascript:void(0)" class="btn btn-primary btn-labeled pull-right add_addon_tab" style="margin-left: auto;"><b><i class="icon-plus-circle2"></i></b> Add Addon</a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="panel-body container-fluid">
                        <?php
                        if ($is_rental_service_available) {
                            $class = "hide";
                            if (isset($rental_package_addons) && !empty($rental_package_addons)) {
                                $class = "";
                            }
                            ?>
                            <ul class="nav nav-tabs nav-tabs-highlight rental_addon_li <?php echo $class; ?>">
                                <?php
                                if (isset($rental_package_addons) && !empty($rental_package_addons)) {
                                    foreach ($rental_package_addons as $k => $val) {
                                        $c = $k + 1;
                                        ?>
                                        <li class="<?php echo 'li_rentaladdon_' . $c ?> <?php echo $k == 0 ? 'active' : '' ?>"><a href="#rentaladdon_<?php echo $c ?>" data-toggle="tab" data-rentaladdon="rentaladdon_<?php echo $c ?>">Addon <?php echo $c ?> <i class="text-danger icon-minus-circle2 remove_rentaladdon" data-addon="<?php echo base64_encode($val['id']) ?>"></i></a></li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                            <div class="tab-content rental_addon_tab_content">
                                <?php
                                if (isset($rental_package_addons) && !empty($rental_package_addons)) {
                                    
                                } else {
                                    ?>
                                    <div class="no_rentaladdon">
                                        No Addons 
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } else { ?>
                            <div class="row">
                                <div class="col-md-12">                                                    
                                    <div class="form-group">                                                    
                                        <label class="control-label col-lg-3">Select Addon(s)</label>
                                        <div class="col-lg-8">
                                            <select multiple="multiple" name="addons[]" class="select service_addon_cls" data-placeholder="Select Addon(s)">
                                                <?php
                                                if (isset($package_addons) && !empty($package_addons)) {
                                                    $basic_addons = array();
                                                    if (isset($customer['basic_addon']) && !empty($customer['basic_addon'])) {
                                                        $basic_addons = explode(',', $customer['basic_addon']);
                                                    }
                                                    foreach ($package_addons as $key => $value) {
                                                        ?>
                                                        <?php
                                                        $selected = '';
                                                        if (in_array($value['id'], $basic_addons)) {
                                                            $selected = 'selected="selected"';
                                                        }
                                                        ?>
                                                        <option value="<?php echo $value['id']; ?>" <?php echo $selected; ?>><?php echo $value['name']; ?> - $<?php echo $value['price']; ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>  
                                </div>  
                            </div>
                        <?php } ?>
                    </div> 

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-actions">
                    <button value="submit" class="btn btn-primary package_btn"><i class="icon-spinner9 spinner position-left hide"></i> Update <i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </div>
        </div>
    </form>
</div>
