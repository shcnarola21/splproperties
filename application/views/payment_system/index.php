<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/'); ?>"><i class="icon-list2 position-left"></i> Settings</a></li>            
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-6">
            <?php $this->load->view('alert_view'); ?>
            <div class="row">                              
                <div class="col-md-12 send_test_email_div">
                    <div class="panel panel-white border-top-primary">
                        <div class="panel-heading">
                            <div class="text-right"></div>
                            <div class="text-left" style="display: flex;">
                                <h5 class="panel-title">Settings</h5>                                
                            </div>
                        </div>                       
                        <form class="form-horizontal" id="payment_system_frm" method="post">
                            <div class="panel-body">
                                <div class="userMessageDiv"></div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Payment Setting</label>
                                    <div class="col-lg-4">
                                        <label style="padding-top: 5px;">
                                            <input type="checkbox" name="payment_system" id="payment_system" value="1" class="hide switchery-primary" <?php echo (isset($provider_info['payment_system']) && $provider_info['payment_system'] == '1') ? 'checked="checked"' : ''; ?> >                                                           
                                        </label>
                                    </div>
                                </div>
                                <?php
                                $div_style = 'display:none;';
                                if (isset($provider_info['payment_system']) && $provider_info['payment_system'] == '1') {
                                    $div_style = '';
                                }
                                ?>
                                <div class="payment_div" style="<?php echo $div_style; ?>">
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Payment Type:</label>
                                        <div class="col-lg-5">
                                            <select name="payment_system_type" class="select">
                                                <option value="">Select</option>
                                                <option <?php echo (isset($payment_system['payment_system_user']) && $payment_system['payment_system_type'] == 'PayPal') ? 'selected="selected"' : ''; ?> value="PayPal">PayPal</option>
                                            </select> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">User:</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="payment_system_user" placeholder="Enter Payment System User" value="<?php echo (isset($payment_system['payment_system_user']) && !empty($payment_system['payment_system_user'])) ? $payment_system['payment_system_user'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Password:</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="payment_system_password" placeholder="Enter Payment System Password" value="<?php echo (isset($payment_system['payment_system_password']) && !empty($payment_system['payment_system_password'])) ? $payment_system['payment_system_password'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Signature:</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="payment_system_signature" placeholder="Enter Payment System Signature" value="<?php echo (isset($payment_system['payment_system_signature']) && !empty($payment_system['payment_system_signature'])) ? $payment_system['payment_system_signature'] : ''; ?>">
                                        </div>
                                    </div>    
                                </div>
                                <div class="text-right frm-actions">
                                    <button type="submit" class="btn bg-teal-400" id="spinner-dark-6"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>                                
                                    <button type="reset" class="btn btn-info"> Reset <i class="icon-reset position-right"></i></button>                                    
                                </div>
                            </div>
                        </form>
                    </div>
                </div>                    
            </div>
        </div>
    </div>
    <div class="modal_div"></div>
    <?php $this->load->view('Templates/footer'); ?>
</div>
<script type="text/javascript" src="assets/js/custom_pages/payment_system.js"></script>
