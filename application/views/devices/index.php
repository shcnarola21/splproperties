<?php
$user_type = $this->session->userdata('type');
?>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/'); ?>"><i class="icon-display4 position-left"></i> Devices</a></li>            
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h5 class="panel-title">List Devices</h5>  
                                <?php
                                if ($user_type != 'admin') {
                                    ?>
                                    <div class="heading-elements">
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" id="add_device"><i class="icon-plus-circle2 position-left"></i> Add Device</button>                                    
                                    </div>  
                                <?php } ?>
                            </div>
                            <div class="panel-body">
                                <table class="table datatable-basic" id="device_dt_table">
                                    <thead>
                                        <tr>
                                            <th style="width:5%">#</th>
                                            <th>Code</th>
                                            <th>Device ID</th>                                        
                                            <th>Mac</th>                                        
                                            <th>Device Name</th>                                        
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
    </div>
    <div class="modal_div"></div>
    <?php $this->load->view('devices/add'); ?>
    <?php $this->load->view('Templates/footer'); ?>
</div>