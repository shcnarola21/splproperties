<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/'); ?>"><i class="icon-mail5 position-left"></i> Send Email Configuration</a></li>            
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-white border-top-primary">
                        <div class="panel-heading">
                            <div class="text-right"></div>
                            <div class="text-left" style="display: flex;">
                                <h5 class="panel-title">Send Email Configuration</h5>                                
                            </div>
                        </div>
                        <form class="form-horizontal" action="<?php echo base_url() ?>send_email/save" method="post" id="send_email_settings_frm">
                            <div class="panel-body">
                                <div class="userMessageDiv"></div>
                                <input type="hidden"  name="id" placeholder="SMTP Username" value="<?php echo isset($email_settings['id']) ? base64_encode($email_settings['id']) : ""; ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">Smtp Username:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" name="smtp_username" placeholder="SMTP Username" value="<?php echo isset($email_settings['smtp_username']) ? $email_settings['smtp_username'] : ""; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">Smtp Password:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" name="smtp_password" placeholder="SMTP Password" value="<?php echo isset($email_settings['smtp_password']) ? $email_settings['smtp_password'] : ""; ?>">
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">Smtp Host:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" name="smtp_host" placeholder="SMTP Host" value="<?php echo isset($email_settings['smtp_host']) ? $email_settings['smtp_host'] : ""; ?>">
                                            </div>
                                        </div>
                                    </div>                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">Port ( 25 / 465 ):</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" name="smtp_port" placeholder="SMTP Port" value="<?php echo isset($email_settings['smtp_port']) ? $email_settings['smtp_port'] : ""; ?>">
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="text-right frm-action">
                                    <button type="button" class="btn bg-teal-400 send_test_email"> <i class="icon-mail5  position-left"></i> Send Test Email </button>                                
                                    <button type="submit" class="btn bg-teal-400" id="spinner-dark-6"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>                                
                                    <button type="reset" class="btn btn-info"> Reset <i class="icon-reset position-right"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>                    
                <div class="col-md-12 send_test_email_div" style="display:none;">
                    <div class="panel panel-white border-top-primary">
                        <div class="panel-heading">
                            <div class="text-right"></div>
                            <div class="text-left" style="display: flex;">
                                <h5 class="panel-title">Send Test Email</h5>                                
                            </div>
                        </div>
                        <form class="form-horizontal" method="post" id="send_test_email_frm">
                            <div class="panel-body">
                                <div class="emailMessageDiv"></div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Email Address:</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control" name="email_address" placeholder="Enter Email Address" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Subject:</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control" name="subject" placeholder="Enter Subject" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Message:</label>
                                    <div class="col-lg-9">                                        
                                        <textarea name="message" id="editor-full" rows="4" cols="4"></textarea>
                                    </div>
                                </div> 
                                <div class="text-right frm-actions">
                                    <button type="submit" class="btn bg-teal-400" id="spinner-dark-6"><i class="icon-spinner9 spinner position-left hide"></i> Send <i class="icon-arrow-right14 position-right"></i></button>                                
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
<script type="text/javascript" src="assets/js/custom_pages/send_email.js"></script>
