<?php
$user_type = $this->session->userdata('type');
?>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('settings/templates'); ?>"><i class="icon-list position-left"></i> Templates</a></li> 
            <li class="active">Edit Template</li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-12">
            <div class="userMessageDiv"></div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h5 class="panel-title">Edit Template</h5>                                
                            </div>
                            <div class="panel-body">
                                <form method="POST" id="template_edit_frm">
                                    <div class="col-xs-12">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email : </label>
                                                <div>                                                    
                                                    <input type="text" class="form-control" disabled  value="<?php echo isset($email_templates['email_for']) ? $email_templates['email_for'] : ''; ?>">
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="display-block">Status : </label>
                                                <div class="checkbox checkbox-switch">
                                                    <label>
                                                        <input type="checkbox" name="enable_send_email" data-on-color="primary" data-off-color="danger" data-on-text="Enable" data-off-text="Disable" class="switch hide" <?php echo (isset($email_templates['is_enable']) && $email_templates['is_enable'] == 1) ? 'checked="checked"' : ''; ?> >                                                      
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Subject:</label>
                                                <div>
                                                    <input type="text" class="form-control" placeholder="Subject" name="email_subject" value="<?php echo isset($email_templates['email_subject']) ? $email_templates['email_subject'] : ''; ?>">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-xs-12" id="template_text_email_wrapper">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Text: </label>
                                                <div>
                                                    <textarea name="text_email" id="editor-email" class="form-control"><?php echo isset($email_templates['email_text']) ? $email_templates['email_text'] : ''; ?>
                                                    </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right frm-action">
                                        <button type="submit" class="btn bg-teal-400" id="spinner-dark-6"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>
                                        <a href="<?php echo site_url('settings/templates'); ?>" class="btn btn-info">Cancel <i class="icon-reset position-right"></i></a>
                                    </div>

                                </form>

                            </div>
                        </div>

                    </div>                    
                </div>
            </div>            
        </div>
    </div>
    <div class="modal_div"></div>
    <?php $this->load->view('devices/add'); ?>
    <?php $this->load->view('Templates/footer'); ?>
</div>
<script type="text/javascript" src="assets/ckeditor/ckeditor.js"></script>
<script>
    $(document).ready(function () {
        CKEDITOR.replace('editor-email', {
            height: '200px',
            extraPlugins: 'forms'
        });
    });

</script>     