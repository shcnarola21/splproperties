<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.date.js"></script>

<script type="text/javascript" src="assets/js/plugins/forms/tags/tagsinput.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/tags/tokenfield.min.js"></script>
<?php
$states = customer_states();
$customer_type_arr = customer_types();
$packages_services_array = array();
$package_data = array();
$is_rental = is_rental_service_available();
$show_input_address = 'display:block';
if ($is_rental) {
    $show_input_address = 'display:none';
}
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
?>

<div class = "page-header page-header-default">
    <div class = "breadcrumb-line">
        <ul class = "breadcrumb">
            <li><a href = "<?php echo base_url(); ?>customers"><i class = "icon-users position-left"></i> Customers</a></li>
            <li class = "active"><?php echo (isset($user['id'])) ? 'Edit' : 'Add' ?>  Customer</li>
        </ul>
    </div>
</div>
<div class="content">   
    <!-- Horizontal form options -->
    <div class="row">

        <div class="col-md-12">
            <div class="panel panel-white border-top-primary">
                <div class="panel-heading">
                    <h6 class="panel-title"><?php echo $title; ?></h6>                    
                </div>

                <div class="panel-body">
                    <div class="userMessageDiv"></div>
                    <div class="tabbable">
                        <form method="post" id="frm_customer_add" class="form-horizontal customer-cls form-wrapper">
                            <ul class="nav nav-tabs nav-tabs-highlight">
                                <li class="li_customer_tab active"><a href="#customer_tab" data-toggle="tab"><i class="icon-users position-left"></i> Customer Detail</a></li>                         
                                <li class="li_package_tab"><a href="#package_tab" data-toggle="tab"><i class="icon-list position-left"></i> Packages</a></li>                         
                                <li class="li_security_tab"><a href="#security_tab" data-toggle="tab"><i class="icon-lock position-left"></i> Security</a></li>                         
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active has-padding" id="customer_tab">
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="control-label col-lg-3">Name <span class="text-danger">*</span></label>
                                                <div class="col-lg-8">
                                                    <input type="text" class="form-control" id="customer_name" name="customer_name" />
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label  class="control-label col-lg-3">Default Language </label>
                                                <div class="col-lg-8">
                                                    <select name="default_language" class="form-control select">
                                                        <option selected="selected" value="en">English</option>
                                                        <option value="fr">French</option>
                                                        <option value="zh">Chinese</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>                                     
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label  class="control-label col-lg-3">Email <span class="text-danger">*</span></label>
                                                <div class="col-lg-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="icon-mail5"></i></span>
                                                        <input type="text" class="form-control" id="email" name="email" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-lg-3" >Type </label>
                                                <div class="col-lg-8">
                                                    <select name="default_customer_type" class="form-control select">
                                                        <?php
                                                        foreach ($customer_type_arr as $ck => $cv) {
                                                            ?>
                                                            <option value="<?php echo $ck; ?>"><?php echo $cv; ?></option>                                                        
                                                            <?php
                                                        }
                                                        ?>                                              
                                                    </select>
                                                </div>
                                            </div>
                                        </div>                      
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="control-label col-lg-3" >Additional Email(s) </label>
                                                <div class="col-lg-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="icon-mail5"></i></span>
                                                        <input type="text" class="form-control tokenfield" value="" name="additional_email" id="additional_email"> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-lg-3" >Phone number </label>
                                                <div class="col-lg-8">
                                                    <input type="text" class="form-control" id="phone" name="phone" value="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h5 class="display-block text-semibold">Secondary Contact</h5>     
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" class="styled customer_secondary_info_cls" value="yes" name="secondary_check" <?php echo "checked='checked'"; ?>>
                                                    Same as primary
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="secondary_info_cls" id="secondary_info" style="display: none;">
                                        <div class="row ">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-lg-3" >Name </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="secondary_name" name="secondary_name" value="" />
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-lg-3" >Number </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="secondary_phone" name="secondary_phone" value="" />
                                                    </div>
                                                </div>
                                            </div> 

                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h5 class="display-block text-semibold">Address Information</h5>     
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rental_property_div" style="display:<?php echo $is_rental && !empty($provider_properties) ? 'block' : 'none' ?>;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-3">Property Address </label>
                                                    <div class="col-sm-8">
                                                        <select id="property_id" class="property_address form-control select-search" name="property_id">
                                                            <option value="" selected="selected">Select One</option>
                                                            <?php
                                                            if (isset($provider_properties)) {
                                                                foreach ($provider_properties as $property) {
                                                                    ?>
                                                                    <option value="<?php echo base64_encode($property['id']); ?>">
                                                                        <?php echo $property['address'] . ', ' . $property['city'] . ', ' . $property['state'] . ', ' . $property['zip_code'] . ', ' . $property['country'] ?>
                                                                    </option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            <option value="other">Other</option>
                                                        </select>  
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row property_unit_select">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-lg-3">Unit</label>
                                                    <div class="col-lg-8">
                                                        <select id="property_unit" class="property_unit form-control select-search" name="property_unit">
                                                            <option value="">Select Unit</option>
                                                            <?php
                                                            if (isset($customer_property)) {
                                                                for ($i = 1; $i <= $customer_property['units']; $i++) {
                                                                    $selected = '';
                                                                    if ($customer['unit'] == $i) {
                                                                        $selected = 'selected="selected"';
                                                                    }
                                                                    ?>
                                                                    <option value="<?php echo $i; ?>" <?php echo $selected ?>><?php echo $i ?></option>
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
                                    <div class="input_address_div" style="display:<?php echo !$is_rental ? 'block' : 'none' ?>;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-lg-3" >Address </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="address" name="address" value="" />
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-lg-3">Country </label>
                                                    <div class="col-lg-8">
                                                        <select id="customer_country" class="country_list form-control select-search" name="country">
                                                            <option value="">Select country</option>
                                                            <?php foreach ($countries as $country) { ?>
                                                                <option value="<?php echo $country->country_name; ?>" <?php echo $country->country_name == 'Canada' ? 'selected="selected"' : '' ?> ><?php echo $country->country_name; ?></option>
                                                            <?php } ?>
                                                        </select>  
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-lg-3" >City </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="city" name="city" value="" />
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-lg-3" >State/Province</label>
                                                    <div class="col-lg-8">
                                                        <select id="state_opt" class="state_opt form-control select" name="state" style="display:block;">
                                                            <?php foreach ($states as $state) { ?>
                                                                <option value="<?php echo $state; ?>" <?php
                                                                if ($state == "Ontario") {
                                                                    echo 'selected="selected"';
                                                                }
                                                                ?>><?php echo $state; ?></option>
                                                                    <?php } ?>
                                                        </select>                                            
                                                        <input type="text" style="display:none;" class="form-control state_txt" name="" id="state_txt" name="state" value="" />
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="control-label col-lg-3" >Zip / Postal code </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="zip_code" name="zip_code" value="" />
                                                    </div>
                                                </div>
                                            </div> 

                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <h5 class="display-block text-semibold">Billing Information</h5>     
                                                <!--<label class="display-block text-semibold">Billing Information</label>-->
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" class="styled customer_billing_info_cls" value="yes" name="billing_check" <?php echo "checked='checked'"; ?>>
                                                    Billing address is same as above
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="billing_info_cls" id="billing_info" style="display: none;">
                                        <div class="row ">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-lg-3" >Billing Name </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="billing_name" name="billing_name" value="" />
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-lg-3" >Country </label>
                                                    <div class="col-lg-8">
                                                        <select id="billing_country" class="billing_country_list form-control select" name="billing_country">
                                                            <option value="">Select country</option>
                                                            <?php foreach ($countries as $country) { ?>
                                                                <option value="<?php echo $country->country_name; ?>"><?php echo $country->country_name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="row ">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-lg-3" >Address </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="billing_address" name="billing_address" value="" />
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-lg-3" >Zip / Postal code </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="billing_zipcode" name="billing_zipcode" value="" />
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="row ">
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="control-label col-lg-3" >City </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="billing_city" name="billing_city" value="" />
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-lg-3" >Phone </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="billing_phone" name="billing_phone" value="" />
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="row ">
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label class="control-label col-lg-3" >State/Province </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="billing_state_txt" name="billing_state_txt" value="" />
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-lg-3" >Email </label>
                                                    <div class="col-lg-8">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="icon-mail5"></i></span>
                                                            <input type="text" class="form-control input-xs" id="billing_email" name="billing_email" value="" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>

                                    </div>
                                </div>
                                <div class="tab-pane has-padding" id="package_tab">
                                    <div class="tabbable">
                                        <ul class="nav nav-tabs nav-tabs-highlight">
                                            <li class="active"><a href="#basic_tab" data-toggle="tab">Packages</a></li>                         
                                            <li style="display:none;" class="li_addon"><a href="#addon_tab" data-toggle="tab">Addon(s)</a></li>                         
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="basic_tab">
                                                <?php if ($is_rental) {
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input type="hidden" name="rental_package_id" value="<?php echo isset($rental_package) && !empty($rental_package) ? base64_encode($rental_package['id']) : '' ?>"/>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label col-lg-3">Package Price</label>
                                                                    <div class="col-lg-8">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon"><i class="icon-coin-dollar"></i></span>
                                                                            <input type="text" class="form-control" name="package_price" id="icon-coin-dollar" value="<?php echo isset($rental_package) && !empty($rental_package) ? $rental_package['price'] : '' ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>                               
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label class="control-label col-lg-3" >Select Package <span class="text-danger">*</span></label>
                                                            <div class="col-lg-8">
                                                                <select title="Select package" class="form-control customer_order_package select-search" id="customer_package" data-live-search="true" data-width="100%" name="customer_package">
                                                                    <option value="">Select Package</option>
                                                                    <?php
                                                                    if (!empty($packages_services_array)) {
                                                                        foreach ($packages_services_array as $key => $value) {
                                                                            ?>
                                                                            <optgroup class="package_service_lable" label="<?php echo $key; ?>">
                                                                                <?php
                                                                                foreach ($value as $S_package) {
                                                                                    ?>
                                                                                    <option value="<?php echo $S_package['id']; ?>" ><?php echo $S_package['name']; ?> - $<?php echo $S_package['price']; ?></option>
                                                                                <?php } ?>
                                                                            </optgroup>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select> 
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="package_info custom_info custom-tab-form-content">
                                                                <?php $this->load->view('customers/single_package_detail', $package_data); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="tab-pane has-padding" id="addon_tab">
                                                <div class="row customer_addon_cls" style="display:none;">
                                                    <span class="col-md-6">                                                    
                                                        <div class="form-group">
                                                            <label class="control-label col-lg-3" >Select Addon(s)</label>
                                                            <div class="col-lg-8">
                                                                <select multiple="multiple" name="addons[]" class="select service_addon_cls" data-placeholder="Select Addon(s)">

                                                                </select>
                                                            </div>
                                                        </div>
                                                    </span>  
                                                </div>    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane has-padding" id="security_tab">
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label class="control-label col-lg-3" >Security Question: </label>
                                                <div class="col-lg-8">
                                                    <input type="text" class="form-control" id="security_question" name="security_question" value="" />
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="col-md-6">
                                            <label class="control-label col-lg-3">Date of Birth:</label>
                                            <div class="col-lg-8">
                                                <div class="controls">
                                                    <div class="input-group dob">
                                                        <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                                        <input data-format="yyyy-MM-dd" type="text" class="form-control daterange-single" name="date_of_birth" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-lg-3" >Security Answer: </label>
                                                <div class="col-lg-8">
                                                    <input type="text" class="form-control" id="security_answer" name="security_answer" value=""/>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="text-right form-actions">
                                <button type="submit" class="btn bg-teal-400" id="customer_btn"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>                                
                                <a href="<?php echo base_url(); ?>customers" class="btn btn-info">Cancel <i class="icon-reset position-right"></i></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="assets/js/custom_pages/customer_view.js"></script>
    <?php $this->load->view('Templates/footer'); ?>
    <!-- /vertical form options -->
</div>
