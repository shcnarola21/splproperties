<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/'); ?>"><i class="icon-bubbles2 position-left"></i> Canned Response(s)</a></li>            
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
                                <h5 class="panel-title">List Canned Response(s)</h5>
                                <a href="<?php echo base_url();?>canned_responses/add" id="add_canned_responses" class="btn btn-primary btn-labeled text-right" style="margin-left: auto;"><b><i class="icon-plus-circle2"></i></b> Add Canned Response</a>                                
                            </div>
                        </div>

                        <div class="panel-body">
                            <table class="table datatable-basic" id="canned_responses_dt_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Subject</th>                                        
                                        <th>Created</th>                                            
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
    <?php $this->load->view('Templates/footer'); ?>
</div>
<script type="text/javascript" src="assets/js/custom_pages/canned_responses.js"></script>