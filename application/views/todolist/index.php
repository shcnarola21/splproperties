<script type="text/javascript" src="assets/js/pages/components_popups.js"></script>
<?php
$user_type = $this->session->userdata('type');
?>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/'); ?>"><i class="icon-clipboard5 position-left"></i> Maintenace / Repair(s)</a></li>            
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
                                <h5 class="panel-title">List Maintenace / Repair(s)</h5>
                                <a href="javascript:void(0)" class="btn btn-primary btn-labeled text-right add_todolist" style="margin-left: auto;"><b><i class="icon-plus-circle2"></i></b> Add Maintenace / Repair(s)</a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table datatable-basic" id="todolist_dt_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Description</th>
                                        <th>status</th>
                                        <th>Added On</th>
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
    <div class="todolist_div"></div>
    <script type="text/javascript" src="assets/js/custom_pages/todolist.js"></script>
    <?php $this->load->view('Templates/footer'); ?>
</div>