<?php
if (isset($cardinfo[0])) {
    $cardinfo = $cardinfo[0];
}
$card_types = array('visa' => 'Visa',
    'mastercard' => 'MasterCard');
?>
<div class="row">
    <form method="POST" class="form-horizontal" id="customer_edit_frm" name="customer_edit_frm">
        <input type="hidden" name="payment_type" value="<?php echo isset($cardinfo['payment_type']) ? $cardinfo['payment_type'] : ''; ?>">
        <input type="hidden" name="customers_id" value="<?php echo $customer['cid']; ?>">
        <input type="hidden" name="cardinfo" value="cardinfo">
        <div class="row">
            <div class="userMessageDiv"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-white border-top-primary">
                    <div class="panel-heading custom_info_head">
                        <h6 class="panel-title text-semibold">Payment Information</h6>
                        <div class="heading-elements">
                        </div>
                    </div>
                    <div class="panel-body container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-lg-4">Name of card holder</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" id="namecard" name="namecard" value="<?php echo isset($cardinfo['namecard']) ? $cardinfo['namecard'] : ''; ?>"/>
                                    </div>
                                </div>                               
                            </div>                               
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-lg-4" >Select Credit Card type</label>
                                    <div class="col-lg-8">
                                        <select title="Select type" class="form-control select" id="card_type" data-live-search="true" data-width="100%" name="card_type">
                                            <option value="">Select Type </option>
                                            <?php
                                            foreach ($card_types as $value) {
                                                $selected = '';
                                                if (isset($cardinfo['card_type']) && !empty($cardinfo['card_type']) && $cardinfo['card_type'] == $value) {
                                                    $selected = 'selected="selected"';
                                                }
                                                ?>
                                                <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
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
                                    <label class="control-label col-lg-4">Credit card number</label>
                                    <?php
                                    $ccnumber = "";
                                    if (isset($cardinfo['ccnumber']) && !empty($cardinfo['ccnumber'])) {
                                        $ccnumber = substr_replace(convert_enc_string($cardinfo['ccnumber']), str_repeat('*', strlen(convert_enc_string($cardinfo['ccnumber'])) - 4), 0, -4);
                                    }
                                    ?>
                                    <div class="<?php echo!empty($ccnumber) ? 'col-lg-7' : 'col-lg-8' ?>">
                                        <input type="text" class="form-control cc_<?php echo $customer['cid']; ?>" id="ccnumber" name="ccnumber" value="<?php echo $ccnumber; ?>" />
                                    </div>
                                    <?php if (!empty($ccnumber)) { ?>
                                        <div class="col-lg-1">        
                                            <i data-type="customer" data-id="<?php echo isset($customer['cid']) ? $customer['cid'] : ''; ?>" class="lock_unlock_card icon-lock"></i>
                                        </div>
                                    <?php } ?>
                                </div>                               
                            </div>                               
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-lg-4" >Expiration month /year</label>
                                    <div class="col-lg-4">
                                        <select name="ccmonth" class="form-control select">
                                            <option value=''>Month </option>
                                            <?php
                                            for ($i = 1; $i <= 12; $i++) {
                                                $selected = '';
                                                if (isset($cardinfo['ccmonth']) && !empty($cardinfo['ccmonth'])) {
                                                    $ccmonth = (strlen($cardinfo['ccmonth']) > 2) ? convert_enc_string($cardinfo['ccmonth']) : $cardinfo['ccmonth'];
                                                    if ($ccmonth == $i) {
                                                        $selected = 'selected="selected"';
                                                    }
                                                }
                                                ?>
                                                <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <select name="ccyear" class="form-control select">
                                            <option value=''>Year</option>
                                            <?php
                                            $current = date("Y");
                                            for ($i = $current; $i <= ($current + 5); $i++) {
                                                $selected = '';
                                                if (isset($cardinfo['ccyear']) && !empty($cardinfo['ccyear'])) {
                                                    $ccyear = (strlen($cardinfo['ccyear']) > 4) ? convert_enc_string($cardinfo['ccyear']) : $cardinfo['ccyear'];
                                                    if ($ccyear == $i) {
                                                        $selected = 'selected="selected"';
                                                    }
                                                }
                                                ?>
                                                <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
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
                                    <label class="control-label col-lg-4">CVC code</label>
                                    <div class="col-lg-8">
                                        <?php
                                        $ccvc = '';
                                        if (isset($cardinfo['ccvc']) &&!empty($cardinfo['ccvc'])) {
                                            $ccvc = _decrypt($cardinfo['ccvc']);
                                        }
                                        ?>
                                        <input type="password" class="form-control" id="ccvc" name="ccvc" value="<?php echo $ccvc ?>" />
                                    </div>
                                </div>                               
                            </div>                               
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-lg-4">Notes</label>
                                    <div class="col-lg-8">
                                        <textarea class="form-control" id="payment_notes" name="payment_notes"><?php echo isset($cardinfo['payment_notes']) ? $cardinfo['payment_notes'] : ""; ?></textarea>
                                    </div>
                                </div>                               
                            </div>                               
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-actions">
                    <button value="submit" class="btn btn-primary package_btn"><i class="icon-spinner9 spinner position-left hide"></i> Update <i class="icon-arrow-right14 position-right"></i></button>
                    <button type="button" class="btn" id="cancelEditcustomer" onclick="get_customer_tab_detail('CardInfo', 'cardinfo_tab')">Reset</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal fade creditcard_view_password_model" id="creditcard_view_password_model" data-backdrop="static" style="display:none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Password Verification</h5>
            </div>
            <form id="creditcard_view_password_frm" method="post">
                <div class="modal-body">
                    <div class="userMessageDiv"></div>
                    <div class="form-group">
                        <label class="control-label">Enter Password</label>
                        <input type="password" autocomplete="off" data-id="" data-type="" id="creditcard_view_password" name="creditcard_view_password" class="form-control"  />
                    </div>
                </div>
                <div class="modal-footer form-actions">
                    <button type="submit" class="btn btn-primary btn_creditcard"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>