<div id="modal_manage_camera" class="modal fade custom_email_modal" data-backdrop="static">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close custom_close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="icon-pencil5 position-left"></i><?php echo (isset($cameras) && !empty($cameras)) ? 'Edit ' : 'Add' ?> Camera</h5>
            </div>
            <div class="modal-body edit_customer_wrapper">
                <div class="tabbable">
                    <form method="post" id="manage_camera_frm" class="form-wrapper form-horizontal">
                        <input type="hidden" name="property" value="<?php echo isset($property) ? $property : '' ?>">
                        <input type="hidden" name="remove_cameras" id="remove_cameras" value="">
                        <div class="userMessageDiv"></div>
                        <div class="row">
                            <button class="btn btn-primary btn-labeled pull-right add_camera" style="margin-left: auto;" data-next="<?php echo (isset($cameras) && !empty($cameras)) ? (count($cameras) + 1) : '2' ?>"><b><i class="icon-plus-circle2"></i></b> Add Camera</button>
                        </div>
                        <ul class="nav nav-tabs nav-tabs-highlight camera_li">
                            <?php
                            if (isset($cameras) && !empty($cameras)) {
                                foreach ($cameras as $k => $val) {
                                    $c = $k + 1;
                                    ?>
                                    <li class="<?php echo 'li_camera_' . $c ?> <?php echo $k == 0 ? 'active' : '' ?>"><a href="#camera_<?php echo $c ?>" data-toggle="tab" data-camera="camera_<?php echo $c ?>">Camera <?php echo $c ?> <i class="text-danger icon-minus-circle2 remove_camera"></i></a></li>
                                    <?php
                                }
                            } else {
                                ?>
                                <li class="li_camera_1 active"><a href="#camera_1" data-toggle="tab" data-camera="camera_1">Camera 1 <i class="text-danger icon-minus-circle2 remove_camera"></i></a></li>
                            <?php } ?>
                        </ul>


                        <div class="tab-content camera_tab_content">
                            <?php
                            if (isset($cameras) && !empty($cameras)) {
                                foreach ($cameras as $k => $val) {
                                    $c = $k + 1;
                                    ?>
                                    <div class="tab-pane <?php echo $k == 0 ? 'active' : '' ?>" id="camera_<?php echo $c ?>">
                                        <input type="hidden" name="camera[<?php echo $k ?>]" value="<?php echo base64_encode($val['id']) ?>" class="camera_no">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Name</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="name[<?php echo $k ?>]" id="name" class="form-control" value="<?php echo $val['name']; ?>">
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Stream URL</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="url[<?php echo $k ?>]" id="url" class="form-control" value="<?php echo $val['stream_url']; ?>">
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-2">Video URL</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="video_url[<?php echo $k ?>]" id="video_url" class="form-control" value="<?php echo $val['video_url']; ?>">
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="tab-pane active" id="camera_<?php echo isset($index) ? $index : '1' ?>">
                                    <?php $this->load->view('camera/camera_tab_content'); ?>                                   
                                </div>
                            <?php } ?>
                            <div class="no_camera hide">
                                No Camera 
                            </div>

                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn bg-teal-400" id="camera_btn"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>                                
                            <button type="button" class="btn btn-info custom_close" data-dismiss="modal">Cancel <i class="icon-reset position-right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
