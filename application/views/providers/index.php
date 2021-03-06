<?php
$user_type = $this->session->userdata('type');
?>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url('providers'); ?>"><i class="icon-users position-left"></i> Providers</a></li>            
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
                                <h5 class="panel-title">List Providers</h5>
                                <a href="<?php echo base_url('providers/add') ?>" class="btn btn-primary btn-labeled text-right" style="margin-left: auto;"><b><i class="icon-plus-circle2"></i></b> Add Provider</a>
                            </div>
                        
                            
                        </div>
                        <div class="panel-body">
                            <table class="table datatable-basic" id="providers_dt_table">
                                <thead>
                                    <tr>
                                        <th style="width:5%">#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>                                            
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
    <?php //$this->load->view('devices/add'); ?>
    <?php $this->load->view('Templates/footer'); ?>
</div>