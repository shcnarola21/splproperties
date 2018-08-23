<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><i class="icon-task position-left"></i>Services</li>
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
                                <h5 class="panel-title">List Services</h5>
                                <button type="button" class="btn btn-primary btn-labeled text-right " data-toggle="modal" id="add_service" style="margin-left: auto;"><b><i class="icon-plus-circle2"></i></b> Add Service</button>                                    
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table datatable-basic" id="services_dt_table">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Service Name</th>                                                
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
    <div class="modal_div modal_manage_service"></div>
    <script type="text/javascript" src="assets/js/custom_pages/service.js"></script>
    <?php $this->load->view('Templates/footer'); ?>
</div>

