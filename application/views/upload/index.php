<?php
$user_type = $this->session->userdata('type');
?>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>upload"><i class="icon-folder-upload position-left"></i> Folder & Uploads</a></li>            
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
                                <h5 class="panel-title">List Folders</h5>  
                                <?php
                                if ($user_type != 'admin') {
                                    ?>
                                <div class="heading-elements">
                                    <a href="<?php echo base_url(); ?>upload/add" class="btn btn-primary"><i class="icon-folder-plus3 position-left"></i> Create Folder</a>
                                </div>  
                                <?php } ?>
                            </div>
                            <div class="panel-body">
                                <table class="table datatable-basic" id="folder_dt_table">
                                    <thead>
                                        <tr>
                                            <th style="width:5%">#</th>
                                            <th>Folder Name</th>                                        
                                            <th>User Name</th>                                        
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
    <?php $this->load->view('Templates/footer'); ?>
</div>