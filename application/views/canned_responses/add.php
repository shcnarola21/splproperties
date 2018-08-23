<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/'); ?>"><i class="icon-bubbles2 position-left"></i> <?php echo isset($canned_responses['id']) ? 'Edit' : 'Add'; ?> Canned Response</a></li>            
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">            
            <div class="row">                                   
                <div class="col-md-12">
                    <div class="panel panel-white border-top-primary">
                        <div class="panel-heading">
                            <div class="text-right"></div>
                            <div class="text-left" style="display: flex;">
                                <h5 class="panel-title"><i class="<?php echo isset($canned_responses['id']) ? 'icon-pencil7' : 'icon-plus-circle2'; ?>"></i> <?php echo isset($canned_responses['id']) ? 'Edit' : 'Add'; ?>  Canned Response</h5>                                
                            </div>
                        </div>
                        <form class="form-horizontal" method="post" id="canned_responses_frm">
                            <div class="panel-body">
                                <div class="userMessageDiv"></div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Title:</label>
                                    <div class="col-lg-4">
                                        <input type="hidden" name="id" value="<?php echo isset($canned_responses['id']) ? base64_encode($canned_responses['id']) : ''; ?>">
                                        <input type="text" class="form-control" name="title" placeholder="Enter Title" value="<?php echo isset($canned_responses['title']) ? $canned_responses['title'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Subject:</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control" name="subject" placeholder="Enter Subject" value="<?php echo isset($canned_responses['subject']) ? $canned_responses['subject'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Message:</label>
                                    <div class="col-lg-9">        
                                        <textarea name="message" id="editor-full" rows="4" cols="4"><?php echo isset($canned_responses['body']) ? $canned_responses['body'] : ''; ?></textarea>
                                    </div>
                                </div> 
                                <div class="text-right frm-actions">
                                    <button type="submit" class="btn bg-teal-400 save_canned_response_btn"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>                                
                                    <a class="btn btn-info" href="<?php echo base_url(); ?>canned_responses" >Cancel <i class="icon-reset position-right"></i></a>                                    
                                </div>
                            </div>
                        </form>
                    </div>
                </div>                    
            </div>
        </div>
    </div>    
    <?php $this->load->view('Templates/footer'); ?>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        CKEDITOR.replace('editor-full', {
            height: '400px',
            extraPlugins: 'forms'
        });
    });
</script>
<script type="text/javascript" src="assets/js/custom_pages/canned_responses.js"></script>
