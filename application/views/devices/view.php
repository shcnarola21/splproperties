<style>
    .list-group-item > .label{float: none;}
</style>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>devices"><i class="icon-display4 position-left"></i> Devices</a></li>        
            <li class="active"><?php echo (isset($device['code'])) ? $device['code'] : ''; ?></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-5">            
            <form action="#">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Device Info</h5>
                    </div>
                    <div class="panel-body">
                        <div class="list-group no-border no-padding-top">
                            <span class="list-group-item"><i class=" icon-grid5"></i> <strong>Code </strong> : <?php echo (isset($device['code'])) ? $device['code'] : ''; ?></span>
                            <span class="list-group-item"><i class="icon-display4"></i> <strong> Hardware ID </strong> : <?php echo (isset($device['hardware_id'])) ? $device['hardware_id'] : ''; ?></span>
                            <span class="list-group-item"><i class=" icon-circles"></i> <strong> Status </strong> : <?php
                                if (isset($device['status'])) {
                                    if ($device['status'] == 'active') {
                                        echo '<label class="label bg-success">Active</label>';
                                    }
                                    if ($device['status'] == 'in_active') {
                                        echo '<label class="label bg-danger">InActive</label>';
                                    }
                                }
                                ?></span>
                            <span class="list-group-item"><i class=" icon-calendar"></i> <strong> Created </strong> : <?php echo (isset($device['created'])) ? $device['created'] : ''; ?></span>
                        </div>                        
                    </div>
                </div>
            </form>            
        </div>

        <div class="col-md-7">
            <form action="#">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Assign Folders</h5>       
                        <div class="heading-elements">
                            <button type="button" id="<?php echo base64_encode($device['id']); ?>" c class="assign_folder btn btn-primary btn-sm"><i class="icon-plus-circle2 position-left"></i>Assign Folders</button>                                    
                        </div>  
                    </div>
                    <div class="panel-body">
                        <div class="list-group no-border no-padding-top">
                            <?php                          
                            if (!empty($user_folders)) {
                                foreach ($user_folders as $f) {
                                    ?>
                                    <span class="list-group-item">
                                        <a href="<?php echo base_url(); ?>upload/view/<?php echo base64_encode($f['folder_id']) ?>"><i class="icon-folder-open"></i> <strong><?php echo $f['folder_name']; ?> </strong></a>                               
                                    </span> 
                                    <?php
                                }
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal_div dv_view"></div>
    <?php $this->load->view('Templates/footer'); ?>
</div>