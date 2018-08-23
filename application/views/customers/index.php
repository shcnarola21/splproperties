<script type="text/javascript" src="assets/js/pages/components_popups.js"></script>
<?php
$user_type = $this->session->userdata('type');
?>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/'); ?>"><i class="icon-users position-left"></i> Customers</a></li>            
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
                                <h5 class="panel-title">List Customers</h5>
                                <a href="<?php echo base_url('customers/add') ?>" class="btn btn-primary btn-labeled text-right" style="margin-left: auto;"><b><i class="icon-plus-circle2"></i></b> Add Customer</a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="pull-right">
                                <input type="checkbox" name="show_terminated" class="styled" id="show_terminated">					
                                <label class="show_checkbox_left text-semibold">Show Terminated&nbsp;&nbsp;</label>
                            </div>
                            <table class="table datatable-basic" id="customers_dt_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Joined Date</th>
                                        <th>Status</th>                                            
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
     <script type="text/javascript" src="assets/js/custom_pages/customer_view.js"></script>
    <?php $this->load->view('Templates/footer'); ?>
</div>