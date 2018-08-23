<?php
$states = customer_states();
$is_rental = is_rental_service_available();
$show_input_address = 'display:block';
if ($is_rental) {
    $show_input_address = 'display:none';
}
$s_style = "";
$s_checked = "";

if (isset($customer['secondary_check']) && $customer['secondary_check'] == 'yes') {
    $s_style = "display:none;";
    $s_checked = "checked='checked'";
}
$t_event = 'display:none;';
$t_event_date = 'display:none;';
if ((isset($customer['status']) && $customer['status'] == 'terminated') || (!empty($customer['termination_event']))) {
    $t_event = 'display:block;';
}
if (isset($customer['termination_event']) && $customer['termination_event'] == 'specific') {
    $t_event_date = 'display:block;';
}

$teminated_status = array('now' => 'Now',
    'specific' => 'Specific date',
    'on_renewal' => 'On Renewal');
?>
<div id="edit_customer_modal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog <?php echo (isset($accountinfo) && $accountinfo == true) ? '' : 'modal-lg' ?>">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close custom_close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="icon-pencil5 position-left"></i> Edit Customer <?php echo (isset($billing) && $billing == true) ? 'Bill Info' : ((isset($accountinfo) && $accountinfo == true) ? 'Account Info' : '') ?></h5>
            </div>
            <form class="form-horizontal" method="post" id="customer_edit_frm">
                <div class="modal-body edit_customer_wrapper">
                    <div class="userMessageDiv col-md-12"></div>
                    <input type="hidden" name="tab" value="account_info">
                    <input type="hidden" name="customers_id" value="<?php echo $customer['cid']; ?>">
                    <?php if (isset($billing) && $billing == true) { ?>
                        <input type="hidden" name="billing_info" value="billing_info">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label col-sm-3">Billing Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="billing_name" name="billing_name" value="<?php echo $customer['billing_name']; ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Billing Email</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="billing_email" name="billing_email" value="<?php echo $customer['billing_email']; ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Billing Address</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="billing_address" name="billing_address" value="<?php echo $customer['billing_address']; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Billing City</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="billing_city" name="billing_city" value="<?php echo $customer['billing_city']; ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Billing State</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="billing_state" name="billing_state" value="<?php echo $customer['billing_state']; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Billing Country</label>
                                    <div class="col-sm-8">
                                        <select class="select" id="billing_country" name="billing_country" class="form-control">
                                            <option value="">Select country</option>
                                            <?php foreach ($countries as $country) { ?>
                                                <option value="<?php echo $country->country_name; ?>" <?php
                                                if ((isset($customer['country'])) && ($customer['billing_country'] == $country->country_name)) {
                                                    echo 'selected="selected"';
                                                }
                                                ?>><?php echo $country->country_name; ?></option>
                                                    <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Billing Zip / Postal code  </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="billing_zipcode" name="billing_zipcode" value="<?php echo $customer['billing_zipcode']; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Billing Phone</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="billing_phone" name="billing_phone" value="<?php echo $customer['billing_phone']; ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else if (isset($accountinfo) && $accountinfo == true) { ?>
                        <input type="hidden" name="account_info" value="account_info">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Account Status</label>
                                    <div class="col-sm-8">
                                        <select name="customer_account_status" id="customer_account_status" class="select">
                                            <option value="">Select Status</option>
                                            <?php
                                            $customer_status_array = array('active', 'suspended', 'terminated');
                                            foreach ($customer_status_array as $value) {
                                                $selected = '';
                                                if (($value == $customer['status']) || (!empty($customer['termination_event']))) {
                                                    $selected = 'selected="selected"';
                                                }
                                                ?>
                                                <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo ucfirst($value); ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="terminate_status_div" style="<?php echo $t_event; ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4">Select to terminate</label>
                                        <div class="col-sm-8">
                                            <select name="terminate_info" id="terminate_info" class="select">
                                                <option value="">Select</option>
                                                <?php
                                                foreach ($teminated_status as $key => $value) {
                                                    $selected = '';
                                                    if ($key == $customer['termination_event']) {
                                                        $selected = 'selected="selected"';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo ucfirst($value); ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row hide_terminate_specific_date" style="<?php echo $t_event_date; ?>">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4">Select Terminate Date</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="icon-calendar"></i></span>
                                                <input data-format="yyyy-MM-dd" type="text" class="form-control daterange-single start_cls" name="terminate_specific_date" id="terminate_specific_date" value="<?php echo!empty($customer['termination_specific_date']) ? date("m/d/Y", strtotime($customer['termination_specific_date'])) : '' ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if (is_provider()) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4">Start Date</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="icon-calendar"></i></span>
                                                <input data-format="yyyy-MM-dd" type="text" class="form-control daterange-single start_cls" name="start_date" value="<?php echo date("m/d/Y", strtotime($customer['start_date'])); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Renewal Date</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="icon-calendar"></i></span>
                                            <input data-format="yyyy-MM-dd" type="text" class="form-control daterange-single renewaldate_cls" name="renewal_date" value="<?php echo date("m/d/Y", strtotime($customer['renewal_date'])); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                     
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Account Payment Type</label>
                                    <div class="col-sm-8">
                                        <select name="customer_account_type" id="customer_account_type" class="select">
                                            <option <?php echo ($customer['payment_type'] == '') ? 'selected' : ''; ?> value="">Select Type</option>
                                            <option <?php echo ($customer['payment_type'] == 'Cash') ? 'selected' : ''; ?> value="Cash">Cash</option>
                                            <?php if (isset($provider_info) && $provider_info['payment_system'] == '1') { ?>
                                                <option <?php echo ($customer['payment_type'] == 'Credit') ? 'selected' : ''; ?> value="Credit">Credit</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <input type="hidden" name="customers_email" value="<?php echo $customer['email']; ?>">                
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Customer name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?php echo $customer['name']; ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Email address</label>
                                    <div class="col-sm-8">
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $customer['email']; ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Additional Email(s)</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="icon-mail5"></i></span>
                                            <input type="hidden" class="form-control tag_email tokenfield" name="additional_email" value="<?php echo $customer['additional_email']; ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Default Language</label>
                                    <div class="col-sm-8">
                                        <select class="select" name="default_language">
                                            <option <?php echo ($customer['default_language'] == "en") ? "selected='selected'" : ''; ?> value="en">English</option>
                                            <option <?php echo ($customer['default_language'] == "fr") ? "selected='selected'" : ''; ?> value="fr">French</option>
                                            <option <?php echo ($customer['default_language'] == "zh") ? "selected='selected'" : ''; ?> value="zh">Chinese</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label i-control-label col-sm-3">Type</label>
                                    <div class="col-sm-8">                                            
                                        <?php
                                        $customer_type_arr = array(
                                            'residential' => 'Residential',
                                            'comercial' => 'Comercial',
                                            'hotel' => 'Hotel',
                                            'multi_unit' => 'Multi Unit'
                                        );
                                        ?>
                                        <select class="select" name="default_customer_type">
                                            <?php
                                            if (!empty($customer)) {
                                                foreach ($customer_type_arr as $ck => $cv) {
                                                    ?>
                                                    <option <?php echo (isset($customer) && $customer['provider_payment_type'] == $ck ) ? 'selected="selected"' : ''; ?> value="<?php echo $ck; ?>"><?php echo $cv; ?></option>                                                        
                                                    <?php
                                                }
                                            }
                                            ?>                                              
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Phone number</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $customer['phone']; ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Rating</label>
                                    <div class="col-sm-8">
                                        <input type="hidden" class="rating" name="rating" data-fractions="2"  data-filled="icon-star-full2 symbol-filled medium-rate" data-empty="icon-star-empty3 symbol-empty medium-rate" value="<?php echo isset($customer['rating']) && !empty($customer['rating']) ? $customer['rating'] : 0; ?>"/>
                                        <?php if (isset($customer) && empty($customer['rating'])) { ?>
                                            <label> ( No Rating )</label>
                                        <?php } ?>
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
                                        <input type="checkbox" class="styled customer_secondary_info_cls" value="<?php echo $customer['secondary_check'] ?>" name="secondary_check" <?php echo $s_checked; ?>>
                                        Same as primary
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="secondary_info_cls" id="secondary_info" style="<?php echo $s_style; ?>">
                            <div class="row ">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3" >Name </label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" id="secondary_name" name="secondary_name" value="<?php echo $customer['secondary_name']; ?>" />
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3" >Number </label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" id="secondary_phone" name="secondary_phone" value="<?php echo $customer['secondary_phone']; ?>" />
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
                                <div class="col-md-10">
                                    <div class="form-group" >
                                        <label class="control-label col-sm-2">Property Address </label>
                                        <div class="col-sm-9 property_custom_css">
                                            <select id="property_id" class="property_address form-control select-search" name="property_id">
                                                <?php
                                                if (isset($provider_properties)) {
                                                    foreach ($provider_properties as $property) {
                                                        $selected = '';
                                                        if ($customer['property_id'] == $property['id']) {
                                                            $selected = 'selected="selected"';
                                                        }
                                                        ?>
                                                        <option value="<?php echo base64_encode($property['id']); ?>" <?php echo $selected ?>>
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
                                        <label class="control-label col-sm-3">Unit</label>
                                        <div class="col-sm-8">
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
                        <div class="input_address_div" style="display:<?php echo(!empty($customer['property_id'])) ? 'none' : 'block' ?>;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3" >Address </label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" id="address" name="address" value="<?php echo $customer['address']; ?>" />
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Country</label>
                                        <div class="col-sm-8">
                                            <select class="select-search" id="customer_country" name="country">
                                                <option value="">Select country</option>
                                                <?php foreach ($countries as $country) { ?>
                                                    <option value="<?php echo $country->country_name; ?>" <?php
                                                    if ((empty($customer['country']) && $country->country_name == 'Canada') || ((isset($customer['country'])) && ($customer['country'] == $country->country_name))) {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>><?php echo $country->country_name; ?></option>
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
                                            <input type="text" class="form-control" id="city" name="city" value="<?php echo $customer['city']; ?>" />
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3" >State/Province</label>
                                        <div class="col-lg-8">
                                            <?php
                                            $state_op_style = 'display:block';
                                            $state_txt_style = 'display:none';
                                            $state_class = 'select-search';
                                            if (isset($customer)) {
                                                if (in_array($customer['state'], $states)) {
                                                    $state_op_style = 'display:none';
                                                    $state_txt_style = 'display:block';
                                                    $state_class = '';
                                                }
                                            }
                                            ?>
                                            <select id="state_opt" class="state_opt form-control <?php echo $state_class; ?>" name="state" style="<?php echo $state_op_style; ?>">
                                                <?php foreach ($states as $state) { ?>
                                                    <option value="<?php echo $state; ?>" <?php
                                                    if ((empty($customer['state']) && $state == 'Ontario') || (isset($customer['state']) && $customer['state'] == $state)) {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?>><?php echo $state; ?></option>
                                                        <?php } ?>
                                            </select>                                            
                                            <input type="text" style="<?php echo $state_txt_style; ?>" class="form-control state_txt" name="" id="state_txt" name="state" value="<?php echo (isset($customer['state']) ? $customer['state'] : ''); ?>" />
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3" >Zip / Postal code </label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" id="zip_code" name="zip_code" value="<?php echo $customer['zip_code']; ?>" />
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <div class="form-actions">
                        <button value="submit" class="btn btn-primary btn_edit_customer"><i class="icon-spinner9 spinner position-left hide"></i> Update <i class="icon-arrow-right14 position-right"></i></button>
                        <button type="button" class="btn btn-default custom_close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
