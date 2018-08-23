<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>upload"><i class="icon-gear position-left"></i> Settings</a></li>  
            <li class="active">Email Configurations</li>
        </ul>
    </div>
</div>
<div class="content">   
    <!-- Horizontal form options -->
    <div class="row">
        <div class="userMessageDiv"></div>
        <div class="col-md-12">
            <form method="post" id="send_email_config_frm">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Email Configurations</h5>                    
                        <a class="heading-elements-toggle"><i class="icon-more"></i></a>
                    </div>                  
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <fieldset>
                                    <legend class="text-semibold"><i class="icon-reading position-left"></i> SMTP details</legend>                                    
                                    <div class="form-group">
                                        <label>SMTP Username:</label>
                                        <input type="text" name="smtp_username" class="form-control" placeholder="Enter SMTP Username" value="<?php echo (isset($send_email_config['smtp_username'])) ? $send_email_config['smtp_username'] : ''; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>SMTP Password:</label>
                                        <input type="text" name="smtp_password" class="form-control" placeholder="Enter SMTP Password" value="<?php echo (isset($send_email_config['smtp_password'])) ? $send_email_config['smtp_password'] : ''; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>SMTP Host:</label>
                                        <input type="text" name="smtp_host" class="form-control" placeholder="Enter SMTP Host" value="<?php echo (isset($send_email_config['smtp_host'])) ? $send_email_config['smtp_host'] : ''; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>SMTP PORT:</label>
                                        <input type="text" name="smtp_port" class="form-control" placeholder="Enter SMTP Port" value="<?php echo (isset($send_email_config['smtp_port'])) ? $send_email_config['smtp_port'] : ''; ?>">
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <div class="text-left">
                            <button type="submit" class="btn bg-teal-400" id="spinner-dark-6"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
    <!-- /vertical form options -->
</div>