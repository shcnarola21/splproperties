<?php
$device_arr = array();
if (!empty($folder_devices)) {
    foreach ($folder_devices as $d) {
        $device_arr[] = $d['device_id'];
    }
}
$user_data = $this->session->userdata('session_info');
$user_id = $user_data['user_id'];
?>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>upload"><i class="icon-folder-upload position-left"></i> Uploads</a></li>  
            <li class="active"><?php echo (isset($folder['id'])) ? 'Edit' : 'Create' ?>  Folder</li>
        </ul>
    </div>
</div>
<div class="content">   
    <!-- Horizontal form options -->
    <div class="row">
        <div class="col-md-12">
            <div class="userMessageDiv"></div>
            <!-- Basic layout-->
            <form  id="create_folder_frm" method="post" class="form-horizontal" enctype="multipart/form-data">
                <input type ="hidden" name="folder_id" class="folder_id" value="<?php echo (isset($folder['id'])) ? $folder['id'] : '' ?>" />                
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title"><?php echo (isset($folder['id'])) ? 'Edit' : 'Create' ?> Folder</h5>  
                        <?php if (isset($folder['id'])) { ?>
                            <div class="heading-elements">
                                <a href="<?php echo base_url() ?>upload/view/<?php echo base64_encode($folder['id']); ?>" class="btn btn-primary"><i class="icon-zoomin3 position-left"></i>View Folder</a>
                            </div>  
                        <?php }
                        ?>

                    </div>

                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Name:</label>
                            <div class="col-lg-4">
                                <input type="text" class="form-control" name="folder_name" placeholder="Folder Name" value="<?php echo (isset($folder['name'])) ? $folder['name'] : '' ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Device:</label>
                            <div class="col-lg-4">
                                <select multiple="multiple" name="device_id[]" data-placeholder="Select Hardware" class="select-icons">
                                    <optgroup label="Select Device">
                                        <?php
                                        if (!empty($devices)) {
                                            foreach ($devices as $d) {
                                                ?>
                                                <option <?php echo (in_array($d['id'], $device_arr)) ? "selected='selected'" : '' ?> value="<?php echo $d['id'] ?>" data-icon="display4"><?php echo $d['code'] ?></option>                                                
                                                <?php
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <?php if (!empty($folder_images)) { ?>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">&nbsp;</label>
                                <div class="col-lg-10">
                                    <div class="row">
                                        <?php
                                        foreach ($folder_images as $fi) {
                                            $path = $this->config->item('uploads_path') . '/' . $user_id . '/' . $fi['folder_id'] . '/' . $fi['image_name'];
                                            $img_url = base_url() . 'uploads' . '/' . $user_id . '/' . $fi['folder_id'] . '/' . $fi['image_name'];
                                            if (!file_exists($path)) {
                                                $img_url = base_url() . 'uploads/no_image.jpg';
                                            }
                                            ?>
                                            <div class="col-lg-2 <?php echo 'f_' . $fi['id'] ?>">

                                                <div class="thumbnail">
                                                    <div class="thumb">
                                                        <img src="<?php echo $img_url; ?>" style="max-height:140px !important;min-height:140px !important;" alt="<?php echo $fi['image_name']; ?>">
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
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Upload Images:</label>
                            <div class="col-lg-10">
                                <input type="file" name="upload_imgs[]" class="txt_other_images" multiple="multiple">                                
                                <!--<input id="input-b3" name="upload_imgs[]" type="file" multiple="multiple" class="file" />-->
                            </div>
                        </div>                          
                        <div class="text-right frm-action">
                            <button type="submit" class="btn bg-teal-400" id="spinner-dark-6"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>
                            <a href="<?php echo base_url() ?>upload" class="btn btn-info">Cancel <i class="icon-reset position-right"></i></a>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /basic layout -->

        </div>

        <div class="col-md-6">

        </div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
    <!-- /vertical form options -->
</div>
<style>
    .file-footer-buttons .kv-file-upload,.file-upload-indicator{
        display:none;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $(".txt_other_images").fileinput({
            uploadAsync: false,
            showRemove: true,
            showUpload: false,
            initialPreview: [],            
            fileActionSettings: {
                removeIcon: '<i class="icon-bin"></i>',
                removeClass: 'btn btn-link btn-xs btn-icon',
                uploadIcon: '<i class="icon-upload"></i>',
                uploadClass: 'btn btn-link btn-xs btn-icon',
                indicatorNew: '<i class="icon-file-plus text-slate"></i>',
                indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
                indicatorError: '<i class="icon-cross2 text-danger"></i>',
                indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>',
            }
        }).on("filebatchselected", function (event, data) {            
//            var formData = new FormData();
//formData.append('file', data);
//            console.log(data);
//            
        });
    });

</script>