<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('properties'); ?>"><i class="icon-office position-left"></i> Property(s)</a></li>            
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
                                <h5 class="panel-title">List Property(s)</h5>
                                <a href="<?php echo base_url()?>properties/add" id="add_property" class="btn btn-primary btn-labeled text-right" style="margin-left: auto;"><b><i class="icon-plus-circle2"></i></b> Add Property</a>                                
                            </div>
                        </div>

                        <div class="panel-body">
                            <table class="table datatable-basic" id="properties_dt_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Address</th>
                                        <th>Unit(s)</th>                                        
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
<script type="text/javascript" src="assets/js/custom_pages/properties.js"></script>