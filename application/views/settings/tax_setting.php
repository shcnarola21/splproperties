<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/'); ?>"><i class="icon-stats-growth position-left"></i> Tax Setting</a></li>            
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-white border-top-primary">
                        <div class="panel-heading">
                            <div class="text-right"></div>
                            <div class="text-left" style="display: flex;">
                                <h5 class="panel-title">Tax Setting</h5>                                
                            </div>
                        </div>
                        <form class="form-horizontal" method="post" id="tax_setting_frm">
                            <div class="panel-body">                                
                                <div class="userMessageDiv"></div>
                                <input type="hidden"  name="id" value="<?php echo isset($settings['id']) ? base64_encode($settings['id']) : ""; ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-lg-8 control-label"> Auto Send Yearly Tax Receipt :</label>
                                            <div class="col-lg-3">
                                                <div class="checkbox checkbox-switchery">
                                                    <label>
                                                        <input type="checkbox" name="auto_send_yearly_tax_receipt" id="auto_send_yearly_tax_receipt" value="1" class="hide switchery-primary" <?php echo (isset($settings['auto_send_yearly_tax_receipt']) && $settings['auto_send_yearly_tax_receipt'] == '1') ? 'checked="checked"' : ''; ?> >                                                           
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <?php
                                        $date_div_style = 'display:none;';
                                        if (isset($settings['auto_send_yearly_tax_receipt']) && $settings['auto_send_yearly_tax_receipt']) {
                                            $date_div_style = 'display:block;';
                                        }
                                        ?>
                                        <div class="date_div" style="<?php echo $date_div_style; ?>">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-lg-3 control-label"> Date :</label>
                                                    <div class="col-lg-8">                                               
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="icon-calendar"></i></span>
                                                            <?php
                                                            $date = 'January 01';
                                                            if (!empty($settings['auto_send_yearly_tax_receipt_date'])) {
                                                                $date = date('F d', strtotime($settings['auto_send_yearly_tax_receipt_date']));
                                                            }
                                                            ?>
                                                            <input type="text" class="form-control" id="anytime-month-day" name="date" value="<?php echo $date; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                                                                    
                                        </div> 
                                    </div>
                                </div>

                                <div class="text-right frm-action">
                                    <button type="submit" class="btn bg-teal-400 btn_tax_setting" id="spinner-dark-6"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>                                
                                    <button type="reset" class="btn btn-info"> Reset <i class="icon-reset position-right"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>                             
                <div class="col-md-6">
                    <div class="panel panel-white border-top-primary">
                        <div class="panel-heading">
                            <div class="text-right"></div>
                            <div class="text-left" style="display: flex;">
                                <h5 class="panel-title">Yearly Auto Increase Package Prices</h5>   
                                <a href="javascript:void(0)" class="btn btn-primary btn-labeled text-right" id="btn_view_history" style="margin-left: auto;"><b><i class="icon-zoomin3"></i></b> View History</a>
                            </div>
                        </div>
                        <form class="form-horizontal" action="<?php echo base_url() ?>tax_setting/save" method="post" id="auto_increase_frm">
                            <div class="panel-body">                               
                                <div class="auto_increase_msg_div"></div>
                                <input type="hidden"  name="id" value="<?php echo isset($settings['id']) ? base64_encode($settings['id']) : ""; ?>">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Yearly Auto Increase :</label>
                                            <div class="col-lg-7">     
                                                <div class="form-group has-feedback has-feedback-left">
                                                    <input type="number" name="yearly_auto_increase_value" step="0.1" class="form-control" placeholder="Enter Value" value="<?php echo isset($settings['yearly_auto_increase_value']) ? $settings['yearly_auto_increase_value'] : ""; ?>">
                                                    <div class="form-control-feedback">
                                                        <i class="icon-percent"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Select Time :</label>
                                            <div class="col-lg-7">     
                                                <select class="form-control select" name="yearly_auto_increase_specific_time" id="select_date">
                                                    <option value="">Select One</option>
                                                    <option value="now">Now</option>
                                                    <option <?php echo (isset($settings['yearly_auto_increase_specific_time']) && $settings['yearly_auto_increase_specific_time'] == 'yes') ? "selected='selected'" : ""; ?> value="specific_date">Specific Date</option>
                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                        $dt_style = 'display:none;';
                                        if (isset($settings['yearly_auto_increase_specific_time']) && $settings['yearly_auto_increase_specific_time'] == 'yes') {
                                            $dt_style = 'display:block;';
                                        }
                                        ?>
                                        <div class="form-group auto_increase_date_div" style="<?php echo $dt_style; ?>">
                                            <label class="col-lg-4 control-label">Select Date :</label>
                                            <div class="col-lg-7">     
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="icon-calendar"></i></span>
                                                    <input data-format="yyyy-MM-dd" type="text" class="form-control daterange-single" name="yearly_auto_increase_date" value="<?php echo (isset($settings['yearly_auto_increase_date']) && !empty($settings['yearly_auto_increase_date'])) ? date("m/d/Y", strtotime($settings['yearly_auto_increase_date'])) : ""; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                                                   
                                </div>
                                <div class="text-right frm-action">
                                    <button type="submit" class="btn bg-teal-400 btn_auto_increase" id="spinner-dark-6"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>                                
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
<script type="text/javascript" src="assets/js/plugins/pickers/anytime.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.date.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.time.js"></script>
<script type="text/javascript" src="assets/js/custom_pages/tax_setting.js"></script>
