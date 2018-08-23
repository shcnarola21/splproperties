<script type="text/javascript" src="assets/js/plugins/forms/tags/tagsinput.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/tags/tokenfield.min.js"></script>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/'); ?>messaging"><i class="icon-mail5 position-left"></i> Messaging</a></li>            
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
                                <h5 class="panel-title">Messaging</h5>                                
                            </div>
                        </div>
                        <form class="form-horizontal" method="post" id="messaging_email_frm">
                            <div class="panel-body" id="email_customer_modal">
                                <div class="userMessageDiv"></div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Canned Response(s):</label>
                                    <div class="col-lg-4">
                                        <select name="canned_response_id" id="canned_response_id" class="select">
                                            <option value="">Select Canned Response</option>
                                            <?php
                                            foreach ($canned_responses as $value) {
                                                ?>
                                                <option value="<?php echo base64_encode($value['id']); ?>"><?php echo $value['title'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2 control-label">To:</label>
                                    <div class="col-lg-4">
                                        <select class="select op_to" name="to">
                                            <option value="specific">Specific</option>
                                            <option value="all">All</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group div_email_to">
                                    <label class="col-lg-2 control-label">Email:</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control tokenfield" name="email_to" id="email_to">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Subject:</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter Subject" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Message:</label>
                                    <div class="col-lg-9">                                        
                                        <textarea name="message" id="message" rows="4" cols="4"></textarea>
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
<script type="text/javascript" src="assets/js/custom_pages/messaging.js"></script>
