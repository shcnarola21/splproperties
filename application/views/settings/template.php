<?php
$user_type = $this->session->userdata('type');
?>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('settings/templates'); ?>"><i class="icon-list position-left"></i> Templates</a></li>            
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
                                <h5 class="panel-title">List Templates</h5>
                            </div>
                            <div class="panel-body">
                                <table class="table datatable-basic" id="template_dt_table">
                                    <thead>
                                        <tr>
                                            <th style="width:5%">#</th>
                                            <th>Email</th>
                                            <th>Subject</th>                                            
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