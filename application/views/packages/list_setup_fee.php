<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><i class="icon-coin-dollar position-left"></i>Setup Fee</li>
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
                                <h5 class="panel-title">Listing Setup Fee</h5>  
                                <div class="heading-elements">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" id="add_setupfee"><i class="icon-plus-circle2 position-left"></i> Add Setup Fee</button>                                    
                                </div>  
                            </div>
                            <div class="panel-body">
                                <table class="table datatable-basic" id="package_setupfee_table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>State/Province</th>                                                
                                            <th>Setup Fees</th>                                            
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
    <div class="modal_div modal_add_setupfee"></div>
   <?php $this->load->view('Templates/footer'); ?>
</div>
