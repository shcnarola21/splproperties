<style>
    .list-group-item > .label{float: none;}
</style>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>upload"><i class="icon-display4 position-left"></i> Upload</a></li>        
            <li class="active">View</li>
            <li class="active"><?php echo (isset($folder['name'])) ? 'Folder - ' . $folder['name'] : ''; ?></li>
        </ul>
    </div>
</div>
<div class="content">  
    <div class="row">
        <div class="col-md-12">            
            <form action="#">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Device Info</h5>                        
                    </div>
                    <div class="panel-body">     
                        <div class="list-group no-border no-padding-top">
                            <?php
                            if (!empty($folder_devices)) {
                                foreach ($folder_devices as $f) {
                                    ?>
                                    <span class="list-group-item">
                                        <i class="icon-display4"></i> 
                                        <?php if (!empty($f['hardware_id'])) { ?>
                                            <strong><?php echo $f['hardware_id'] . ' ( ' . $f['device_name'] . ' ) '; ?> </strong></span>
                                    <?php } else { ?>
                                        <strong><?php echo $f['device_name']; ?> </strong></span>
                                    <?php }
                                    ?>
                                    <?php
                                }
                            }
                            ?>
                        </div>  
                    </div>
                </div>
            </form>            
        </div>

        <div class="col-md-12">
            <form action="#">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Folder Images</h5>       
                        <div class="heading-elements">
                            <a href="<?php echo base_url() ?>upload/edit/<?php echo base64_encode($folder['id']); ?>" id="" class="btn btn-primary"><i class="icon-images3 position-left"></i>Add Photo(s)</a>
                        </div>  
                    </div>
                    <div class="panel-body">
                        <?php if (!empty($folder_images)) { ?>

                            <div class="col-lg-10">
                                <div class="row">
                                    <ul class="thumb-ul">


                                        <?php
                                        foreach ($folder_images as $fi) {
                                            $path = $this->config->item('uploads_path') . '/' . $folder['user_id'] . '/' . $fi['folder_id'] . '/' . $fi['image_name'];
                                            $img_url = base_url() . 'uploads' . '/' . $folder['user_id'] . '/' . $fi['folder_id'] . '/' . $fi['image_name'];
                                            if (!file_exists($path)) {
                                                $img_url = base_url() . 'uploads/no_image.jpg';
                                            }
                                            ?>
                                            <li class="col-lg-2 <?php echo 'f_' . $fi['id'] ?>">

                                                <div class="thumbnail">
                                                    <div class="thumb">
                                                        <img src="<?php echo $img_url; ?>" style="height:100px !important;" alt="<?php echo $fi['image_name']; ?>">
                                                        <div class="caption-overflow">
                                                            <span>
                                                                <a href="<?php echo $img_url; ?>" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-zoomin3"></i></a>                                                       
                                                                <?php echo $fi['image_name']; ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="caption">
                                                        <h6 class="no-margin">                                                            
                                                            <a href="#" class="text-default">&nbsp;</a>                                                     
                                                            <a href="#" class="text-muted delete_fi" id="<?php echo $fi['id']; ?>">
                                                                <i class="icon-bin pull-right" title="Remove File"></i>
                                                            </a>
                                                        </h6>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal_div dv_view"></div>
    <?php $this->load->view('devices/add'); ?>
    <?php $this->load->view('Templates/footer'); ?>
</div>