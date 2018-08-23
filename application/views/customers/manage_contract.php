<style>
    .caption-overflow{overflow: hidden;}
    .loading {
        background: transparent url('<?php echo base_url() ?>assets/images/img_loader.gif') center no-repeat;
    }
    .vhide{visibility: hidden}
</style>
<div id="modal_manage_contract" class="modal fade" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close custom_close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="<?php echo isset($contract_info['id']) ? "icon-pencil5" : "icon-plus-circle2"; ?> position-left"></i><?php echo isset($contract_info['id']) ? "Edit" : "Add"; ?> Contract</h5>
            </div>
            <form class="form-horizontal" method="post" id="manage_contract_frm" enctype="multipart/form-data">
                <input type="hidden" name="contract" id="contract" value="<?php echo isset($contract_info['id']) ? base64_encode($contract_info['id']) : '' ?>">
                <input type="hidden" name="contract_for" value="<?php echo isset($contract_for) ? $contract_for : '' ?>">
                <div class="modal-body edit_customer_wrapper">
                    <div class="userMessageDiv"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" value="<?php echo isset($contract_info['name']) ? $contract_info['name'] : '' ?>">
                                </div>
                            </div>
                        </div>  
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Email</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="notification_email" id="notification_email" value="<?php echo isset($contract_info['notification_email']) ? $contract_info['notification_email'] : '' ?>">
                                </div>
                            </div>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Start Date</label>
                                <div class="col-sm-10">
                                    <input  data-format="yyyy-MM-dd" type="text" class="form-control daterange-single" name="start_date" id="start_date" value="<?php echo isset($contract_info['start_date']) ? date('m/d/Y', strtotime($contract_info['start_date'])) : '' ?>">
                                </div>
                            </div>
                        </div>  
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-2">End Date</label>
                                <div class="col-sm-10">
                                    <input  data-format="yyyy-MM-dd" type="text" class="form-control daterange-single" name="end_date" id="end_date" value="<?php echo isset($contract_info['end_date']) ? date('m/d/Y', strtotime($contract_info['end_date'])) : '' ?>">
                                </div>
                            </div>
                        </div>  
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Reminder Days</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="reminder_days" id="reminder_days" value="<?php echo isset($contract_info['reminder_days']) ? $contract_info['reminder_days'] : '' ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Contract:</label>
                                <div class="col-sm-10">
                                    <input type="file" name="image" class="form-control file_input" />                                            
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="thumb-ul ">
                            <div class="preview_img">
                                <?php
                                if (isset($contract_images) && !empty($contract_images)) {
                                    foreach ($contract_images as $key => $val) {
                                        $path = $this->config->item('uploads_path') . '/contracts/' . $val['contract_id'];
                                        $file_extesntion = pathinfo($val['file_name'], PATHINFO_EXTENSION);
                                        $caption_url = $img_url = base_url() . 'uploads' . '/contracts/' . $val['contract_id'] . '/' . $val['file_name'];
                                        if ($file_extesntion == 'pdf') {
                                            $img_url = base_url() . 'uploads/pdf-default-img.png';
                                        }
                                        if (!file_exists($path)) {
                                            $img_url = base_url() . 'uploads/no_image.jpg';
                                        }
                                        ?>
                                        <div class="col-lg-2  <?php echo 'ci_' . $val['id'] ?>">
                                            <div class="thumbnail">
                                                <div class="thumb">
                                                    <img class="loading img_view_wrapper" src="" lsrc="<?php echo $img_url; ?>"  id="ci_img_<?php echo $val['id']; ?>" style="" alt="">
                                                    <div class="caption-overflow">
                                                        <span>
                                                            <a href="<?php echo $caption_url; ?>" <?php echo ($file_extesntion == 'pdf') ? 'target="_blank"' : '' ?> data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-zoomin3"></i></a>                                                                
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="caption">
                                                    <h6 class="no-margin vhide" >                                                            
                                                        <a href="#" class="text-default">&nbsp;</a>                                                     
                                                        <a href="#" class="text-muted delete_ci pull-right" id="<?php echo $val['id']; ?>">
                                                            <i class="icon-bin pull-right" title="Remove File"></i>
                                                        </a>
                                                    </h6>
                                                </div>
                                            </div> 
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions">
                        <button value="submit" class="btn btn-primary btn_contract"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>
                        <button type="button" class="btn btn-default custom_close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
