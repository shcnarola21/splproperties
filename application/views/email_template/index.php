<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/'); ?>"><i class="icon-list2 position-left"></i> Templates</a></li>            
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
                                <h5 class="panel-title">List Templates</h5>                                
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table datatable-basic" id="templates_dt_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Subject</th>
                                        <th style="display:none;">Type</th>                                        
                                        <th>Action</th>                                            
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>                    
            </div>
        </div>
    </div>
    <div class="modal_div"></div>
    <div id="pdf_template_preview_modal" class="modal fade pdf_template_preview_modal" style="display:none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title"><i class="position-left"></i> Preview Invoice PDF</h5>
                </div>
                <div class="modal-body">
                    <div class="userMessageDiv"></div>                    
                    <div class="modal-body">                        
                        <div class="pdf_template_content">
                            <iframe  id="pdf_iframe" src="" name="pdf_iframe" style="height: 390px;width: 100%;" src="" frameborder="0"></iframe>                        
                        </div>
                    </div>
                </div>               
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('Templates/footer'); ?>
</div>
<script type="text/javascript" src="assets/js/custom_pages/templates.js"></script>