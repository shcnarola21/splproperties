<?php
$customer_type_arr = array(
    'residential' => 'Residential',
    'comercial' => 'Comercial',
    'pick_pay' => 'Pick & Pay',
    'standalone' => 'Standalone',
    'hotel' => 'Hotel',
    'multi_unit' => 'Multi Unit'
);
$b_style = "";
$b_checked = "";
$s_style = "";
$s_checked = "";
if (isset($customer['billing_check']) && $customer['billing_check'] == 'yes') {
    $b_style = "display:none;";
    $b_checked = "checked='checked'";
}
if (isset($customer['secondary_check']) && $customer['secondary_check'] == 'yes') {
    $s_style = "display:none;";
    $s_checked = "checked='checked'";
}
$customer_address = '-';
if (isset($customer_property) && !empty($customer_property)) {
    $customer_address = $customer_property['address'] . ', ' . $customer_property['city'] . ', ' . $customer_property['state'] . ', ' . $customer_property['zip_code'] . ', ' . $customer_property['country'];
} else if (!empty($customer['address'])) {
    $customer_address = $customer['address'];
}
?> 

<div class="row custom-tab-form-content">
    <form method="POST" id="profile_update_account" name="profile_update_account">
        <input name="hnd_id" type='hidden' value="<?php echo $customer['cid']; ?>">
        <div id="successMessage"></div>
        <div class="col-md-6">
            <div class="panel panel-white border-top-primary">
                <div class="panel-heading custom_info_head">
                    <h6 class="panel-title text-semibold">Basic customer information</h6>
                    <div class="heading-elements">
                        <div class="heading-btn">
                            <a class="btn btn-primary" id="send_email" data-info="<?php echo base64_encode($customer['cid']); ?>" href="javascript:void(0)"><i class="icon-mail5"></i> Send Email</a>   
                            <a class="btn btn-primary" id="customer_edit" data-info="<?php echo base64_encode($customer['cid']); ?>" href="javascript:void(0)"><i class="icon-pencil5"></i> Edit</a>   
                        </div>
                    </div>
                </div>

                <div class="panel-body container-fluid">
                    <div class="basic_info custom_info">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3"><b>Type</b></label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <div class="span8"><span class="bg-primary text-highlight payment_type_cls"><?php echo $customer_type_arr[$customer['provider_payment_type']]; ?></span> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                               
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3"><b>Name</b></label>
                                    <div class="col-lg-5">
                                        <div class="input-group">
                                            <div class="span8"><?php echo $customer['name']; ?></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="span8"><b>Rating :</b> <input type="hidden" class="rating" data-readonly="true" data-fractions="2"  data-filled="icon-star-full2 symbol-filled" data-empty="icon-star-empty3 symbol-empty" value="<?php echo isset($customer['rating']) ? $customer['rating'] : 0; ?>"/></div>
                                    </div>

                                </div>
                            </div>                               
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3"><b>Email</b></label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <div class="span8"><?php echo $customer['email']; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>                               
                        </div>
                        <?php if ($customer['additional_email'] && !empty($customer['additional_email'])) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3"><b>Additional Email</b></label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="span8">
                                                    <?php
                                                    $additional_email = explode(',', $customer['additional_email']);
                                                    if (count($additional_email) == 1) {
                                                        echo $additional_email[0];
                                                    } else {
                                                        foreach ($additional_email as $ae) {
                                                            ?>
                                                            <?php echo $ae; ?>
                                                            <?php
                                                        }
                                                    }
                                                    ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                            </div>  
                        <?php } ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3"><b>Address</b></label>
                                    <div class="col-lg-6">
                                        <div class="span8"><?php echo $customer_address; ?></div>
                                    </div>

                                    <?php if (isset($customer_property) && !empty($customer_property)) { ?>
                                        <div class="col-lg-3">
                                            <div class="span8"><b>Unit :</b> <?php echo isset($customer['unit']) ? $customer['unit'] : '-'; ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>                               
                        </div>
                        <?php if (empty($customer_property)) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3"><b>City</b></label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="span8"><?php echo!empty($customer['city']) ? $customer['city'] : '-'; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3"><b>State/Province</b></label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="span8"><?php echo!empty($customer['state']) ? $customer['state'] : '-'; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3"><b>Country</b></label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="span8"><?php echo!empty($customer['country']) ? ucfirst($customer['country']) : '-'; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3"><b>Zip / Postal code</b></label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="span8"><?php echo!empty($customer['zip_code']) ? $customer['zip_code'] : '-'; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-lg-3"><b>Phone</b></label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <div class="span8"><?php echo!empty($customer['phone']) ? $customer['phone'] : '-'; ?></div>
                                        </div>
                                    </div>
                                     <!--<div class="span4"><input type="button" class="btn btn-primary customer_send_email" value="Send Email"></div>-->
                                </div>
                            </div>                               
                        </div>
                        <div class="row" style="<?php echo $s_style; ?>">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h5 class="control-label text-semibold">Secondary Contact</h5>     
                                </div>
                            </div>
                        </div>
                        <div class="secondary_section" style="<?php echo $s_style; ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3"><b>Name</b></label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="span8"><?php echo!empty($customer['secondary_name']) ? $customer['secondary_name'] : '-'; ?></div>
                                            </div>
                                        </div>
                                         <!--<div class="span4"><input type="button" class="btn btn-primary customer_send_email" value="Send Email"></div>-->
                                    </div>
                                </div>                               
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3"><b>Number</b></label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="span8"> <?php echo!empty($customer['secondary_phone']) ? $customer['secondary_phone'] : '-'; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                        </div>
                    </div>

                    <hr/>
                    <div class="billing_info custom_info">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <h5 class="display-block text-semibold">Billing Information</h5>     
                                    <!--<label class="display-block text-semibold">Billing Information</label>-->
                                    <label class="checkbox-inline">
                                        <input type="checkbox"  name="billing_check" class="billing_info_chk styled" <?php echo $b_checked; ?>  value="" /> 
                                        Billing address (Same as main address)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <a href="javascript:void(0);" style="<?php echo $b_style; ?>"  title="Edit Billing Information" class="btn btn-primary edit_billing_info" data-info="<?php echo base64_encode($customer['cid']) ?>"><i class="icon-pencil5"></i> Edit</a>
                                </div>
                            </div>
                        </div>
                        <div class="bill_section" style="<?php echo $b_style; ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-4"><b>Billing Name</b></label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="span8"><?php echo $customer['billing_name']; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-4"><b>Billing Email</b></label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="span8"><?php echo $customer['billing_email']; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-4"><b>Billing Address</b></label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="span8"><?php echo!empty($customer['billing_address']) ? $customer['billing_address'] : '-'; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-4"><b>Billing City</b></label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="span8"><?php echo!empty($customer['billing_city']) ? $customer['billing_city'] : '-'; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-4"><b>Billing State/Province</b></label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="span8"><?php echo!empty($customer['billing_state']) ? $customer['billing_state'] : '-'; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-4"><b>Billing Country</b></label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="span8"><?php echo!empty($customer['billing_country']) ? $customer['billing_country'] : '-'; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-4"><b>Billing Zip / Postal code</b></label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="span8"><?php echo!empty($customer['billing_zipcode']) ? $customer['billing_zipcode'] : '-'; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-4"><b>Billing Phone</b></label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="span8"><?php echo!empty($customer['billing_phone']) ? $customer['billing_phone'] : '-'; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="panel panel-white border-top-primary">
                    <div class="panel-heading custom_info_head">
                        <h6 class="panel-title text-semibold">Account information</h6>
                        <div class="heading-elements">
                            <div class="heading-btn">
                                <a class="btn btn-primary" id="customer_accountinfo_edit" data-info="<?php echo base64_encode($customer['cid']); ?>" href="javascript:void(0)"><i class=" icon-pencil5"></i> Edit</a>   
                            </div>
                        </div>
                    </div>

                    <div class="panel-body container-fluid">
                        <div class="custom_info">
                            <div id="statussuccessMessage"></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3"><b>Default Language</b></label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <?php
                                                $language_arr = array(
                                                    'en' => 'English',
                                                    'fr' => 'French',
                                                    'zh' => 'Chinese',
                                                );
                                                ?>
                                                <div class="span8"><?php echo $language_arr[$customer['default_language']]; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3"><b>Account Status</b></label>
                                        <div class="col-lg-3">
                                            <div class="input-group">
                                                <?php if ($customer['status'] == 'active') { ?>
                                                    <span class="label bg-success"><?php echo $customer['status'] ?></span>
                                                <?php } else if ($customer['status'] == 'suspended') { ?>
                                                    <span class="label bg-warning"><?php echo $customer['status'] ?></span>
                                                <?php } else if ($customer['status'] == 'terminated') { ?>
                                                    <span class="label bg-danger"><?php echo $customer['status'] ?></span>
                                                <?php } else { ?>
                                                    <span class="label bg-danger"><?php echo $customer['status'] ?></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php if (!empty($customer['termination_event'])) { ?>
                                            <div class="col-lg-6">
                                                <label class="label bg-danger"><b>Pending Termination</b></label>
                                                <div class="">
                                                    <?php echo ($customer['termination_event'] == 'on_renewal') ? 'On Renewal' : ($customer['termination_event'] == 'specific' ? 'Date: ' . date('Y-m-d', strtotime($customer['termination_specific_date'])) : '-'); ?>
                                                </div>
                                            </div>                               

                                        <?php } ?>
                                    </div>
                                </div>                               
                            </div> 
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3"><b>Account Start Date</b></label>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <?php echo (!empty($customer['start_date']) && $customer['start_date'] != '0000-00-00 00:00:00') ? date('Y-m-d', strtotime($customer['start_date'])) : '-'; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3"><b>Renewal Date</b></label>
                                        <div class="col-lg-8">
                                            <?php echo (!empty($customer['renewal_date']) && $customer['renewal_date'] != '0000-00-00 00:00:00') ? date('Y-m-d', strtotime($customer['renewal_date'])) : '-'; ?>

                                        </div>
                                    </div>
                                </div>                               
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3"><b>Account Payment Type</b></label>
                                        <div class="col-lg-8">
                                            <?php echo!empty($customer['payment_type']) ? $customer['payment_type'] : '-'; ?>

                                        </div>
                                    </div>
                                </div>                               
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="panel panel-default border-top-primary">
                    <div class="panel-heading custom_info_head">
                        <h6 class="panel-title text-semibold">Security information</h6>
                        <div class="heading-elements">
                            <div class="heading-btn">
                                <a id="call_security_model_ajax" href="javascript:void(0)" data-customer="2526" class="btn btn-primary btn-xs"><i class=" icon-pencil5"></i> Edit Security Questions</a>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body container-fluid">
                        <div class="custom_info">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3"><b>Q :</b></label>
                                        <div class="col-lg-8">
                                            <div class="span8">
                                                <?php echo isset($call_security[0]['security_question']) ? $call_security[0]['security_question'] : '-'; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>                               
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3"><b>A :</b></label>
                                        <div class="col-lg-8">
                                            <div class="span8">
                                                <?php echo isset($call_security[0]['security_answer']) ? $call_security[0]['security_answer'] : '-'; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label col-lg-3"><b>Date Of birth :</b></label>
                                        <div class="col-lg-8">
                                            <div class="span8">
                                                <?php echo isset($call_security[0]['date_of_birth']) ? $call_security[0]['date_of_birth'] : '-'; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
<div class="customer_info_editable"></div>
<div class="customer_email_editable"></div>

<div class="modal fade call_secuirty_modal" id="call_secuirty_modal" data-backdrop="static" style="display:none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="icon-pencil5 position-left"></i> Security Questions</h5>
            </div>
            <form id="call_security_frm" method="post">
                <div class="modal-body">
                    <div id="CSsuccessMessage"></div>
                    <div class="form-group">
                        <label class="control-label">Security Question:</label>
                        <input type="text"  class="form-control"name="security_question" value="" /> 
                    </div>
                    <div class="form-group">
                        <label class="control-label">Security Answer:</label>
                        <input type="text"  class="form-control" name="security_answer" value="" /> 
                    </div>
                    <div class="form-group">
                        <label class="control-label">Date of Birth:</label>
                        <div class="controls">
                            <div class="input-group dob">
                                <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                <input data-format="yyyy-MM-dd" type="text" class="form-control daterange-single" name="date_of_birth" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" id="add_hw_btn" class="btn btn-primary btn_save_call_security"><i class="icon-spinner9 spinner position-left hide"></i> Update <i class="icon-arrow-right14 position-right"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.rating').rating();
</script>