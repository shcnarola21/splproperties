<style>
    .caption-overflow{overflow: hidden;}
    .loading {
        background: transparent url('<?php echo base_url() ?>assets/images/img_loader.gif') center no-repeat;
    }
    .vhide{visibility: hidden}
</style>
<?php
$states = customer_states();
$is_rental_service_available = is_rental_service_available();
?>
<div class = "page-header page-header-default">
    <div class = "breadcrumb-line">
        <ul class = "breadcrumb">
            <li><a href = "<?php echo base_url('properties'); ?>"><i class = "icon-office position-left"></i> Properties</a></li>
            <li class = "active"><?php echo (isset($property_arr['id'])) ? 'Edit' : 'Add' ?>  Properties</li>
        </ul>
    </div>
</div>
<div class="content">   
    <!-- Horizontal form options -->
    <div class="row">

        <div class="col-md-12">
            <div class="panel panel-white border-top-primary">
                <div class="panel-heading">
                    <h6 class="panel-title"><?php echo $title; ?></h6>                    
                </div>                
                <div class="panel-body">
                    <div class="userMessageDiv"></div>
                    <div class="tabbable">
                        <form id="img_form"></form>
                        <form method="post" id="property_frm" class="form-horizontal customer-cls form-wrapper" enctype="multipart/form-data">
                            <ul class="nav nav-tabs nav-tabs-highlight">
                                <li class="li_property_tab active"><a href="#property_tab" data-toggle="tab"><i class="icon-office position-left"></i> Property Detail</a></li>                         
                                <li class="li_images_tab"><a href="#images_tab" data-toggle="tab"><i class="icon-images3 position-left"></i> Images</a></li>                         
                                <li class="li_description_tab"><a href="#description_tab" data-toggle="tab"><i class="icon-list position-left"></i> Description</a></li>
                                <?php if (isset($property_arr['id'])) { ?>
                                    <li class="li_zone_tab"><a href="#zones_tab" data-toggle="tab"><i class="icon-mobile2 position-left"></i> Keyless Zones </a></li>
                                    <?php if (isset($property_arr['id']) && $is_rental_service_available) { ?>
                                        <li class="li_general_agreement"><a href="#general_agreement_tab" data-toggle="tab"><i class="icon-magazine position-left"></i> General Agreement(s) </a></li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane active has-padding" id="property_tab">
                                    <input type="hidden" name="property_id" value="<?php echo (isset($property_arr['id']) ? base64_encode($property_arr['id']) : ''); ?>" class="form-control">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-sm-3">Address :</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="address" value="<?php echo (isset($property_arr['address']) ? $property_arr['address'] : ''); ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-sm-3">Country :</label>
                                                <div class="col-sm-8">
                                                    <select id="customer_country" class="country_list form-control select-search" name="country">                                            
                                                        <option value="">Select country</option>
                                                        <?php foreach ($countries as $country) { ?>
                                                            <option value="<?php echo $country->country_name; ?>" <?php
                                                            if ((!isset($property_arr['country']) && $country->country_name == 'Canada') || (isset($property_arr['country'])) && ($property_arr['country'] == $country->country_name)) {
                                                                echo 'selected="selected"';
                                                            }
                                                            ?>><?php echo $country->country_name; ?></option>
                                                                <?php } ?>
                                                    </select>  
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-sm-3">City :</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="city" value="<?php echo (isset($property_arr['city']) ? $property_arr['city'] : ''); ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-sm-3">State/Province:</label>
                                                <div class="col-sm-8">
                                                    <?php
                                                    $state_op_style = 'display:block';
                                                    $state_txt_style = 'display:none';
                                                    $state_class = 'select-search';
                                                    if (isset($property_arr['id'])) {
                                                        if (!in_array($property_arr['state'], $states)) {
                                                            $state_op_style = 'display:none';
                                                            $state_txt_style = 'display:block';
                                                            $state_class = '';
                                                        }
                                                    }
                                                    $is_state_from_option = false;
                                                    ?>
                                                    <select id="state_opt" class="state_opt form-control <?php echo $state_class; ?>" name="state_op" style="<?php echo $state_op_style; ?>">
                                                        <option value="">Select State</option>
                                                        <?php foreach ($states as $state) { ?>
                                                            <option value="<?php echo $state; ?>" <?php
                                                            if ((!isset($property_arr['id']) && $state == 'Ontario') || (isset($property_arr['id']) && $property_arr['state'] == $state)) {
                                                                echo 'selected="selected"';
                                                                $is_state_from_option = true;
                                                            }
                                                            ?>><?php echo $state; ?></option>
                                                                <?php } ?>
                                                    </select>                                        
                                                    <input type="text" style="<?php echo $state_txt_style; ?>" class="form-control state_txt" id="state_txt" name="state" value="<?php echo ((isset($property_arr['state']) && !$is_state_from_option) ? $property_arr['state'] : ''); ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-sm-3">Zip / Postal code :</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="zip_code" value="<?php echo (isset($property_arr['zip_code']) ? $property_arr['zip_code'] : ''); ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-sm-3">Unit(s) :</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="units" value="<?php echo (isset($property_arr['units']) ? $property_arr['units'] : ''); ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                                                 
                                </div>                                
                                <div class="tab-pane has-padding" id="images_tab">
                                    <div class="row">
                                        <div class="thumb-ul ">
                                            <div class="preview_img">
                                                <?php
                                                if (!empty($property_images_arr)) {
                                                    foreach ($property_images_arr as $fi) {
                                                        $path = $this->config->item('uploads_path') . '/properties/' . $property_arr['id'];
                                                        $img_url = base_url() . 'uploads' . '/properties/' . $property_arr['id'] . '/' . $fi['image_name'];
                                                        if (!file_exists($path)) {
                                                            $img_url = base_url() . 'uploads/no_image.jpg';
                                                        }
                                                        ?>
                                                        <div class="col-lg-2  <?php echo 'f_' . $fi['id'] ?>">
                                                            <div class="thumbnail">
                                                                <div class="thumb">
                                                                    <img class="loading img_view_wrapper" src="" lsrc="<?php echo $img_url; ?>"  id="img_<?php echo $fi['id']; ?>" style="" alt="">
                                                                    <div class="caption-overflow">
                                                                        <span>
                                                                            <a href="<?php echo $img_url; ?>" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-zoomin3"></i></a>                                                                
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="caption">
                                                                    <h6 class="no-margin vhide" >                                                            
                                                                        <a href="#" class="text-default">&nbsp;</a>                                                     
                                                                        <a href="javascript:void(0)" data-nextangle="0" class="text-muted rotate_img" id="<?php echo $fi['id']; ?>">
                                                                            <i class="icon-reload-alt pull-left" title="Rotate Image"></i>
                                                                        </a>
                                                                        <a href="#" class="text-muted delete_fi" id="<?php echo $fi['id']; ?>">
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
                                                <form action="" id="view_file_upload_frm" method="post" enctype="multipart/form-data">
                                                    <div class="col-lg-2 cls_upload_div custom_upload_div">
                                                        <div class="thumbnail">
                                                            <div class="thumb uploader icon_plus">
                                                                <span><i class="icon icon-images3"></i> Drag and drop files or click to select</span>
                                                            </div>

                                                            <div class="caption" style="padding:0">
                                                                <h6 class="no-margin">                                                                    
                                                                    <button type="button" class="btn_file_input_view btn btn-primary btn-xs" style="width: 100%;"><i class="i icon-images3"></i> Add Photo(s)</button>
                                                                    <input id="upload_file_view" data-action="<?php echo (isset($property_arr['id']) ? 'edit' : ''); ?>"  name="upload_file_view[]" class="hide" type="file">                                                        
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane has-padding" id="description_tab">
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-lg-2 control-label">Description:</label>
                                            <div class="col-lg-9">                                        
                                                <textarea name="description" id="editor-full"><?php echo (isset($property_arr['description']) ? $property_arr['description'] : ''); ?></textarea>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                                <?php if (isset($property_arr['id'])) { ?>
                                    <div class="tab-pane has-padding" id="zones_tab">
                                        <div class="row">
                                            <div class="row custom-tab-form-content">
                                                <div class="col-md-12">
                                                    <div class="panel panel-white border-top-primary">
                                                        <div class="panel-heading custom_info_head">
                                                            <h6 class="panel-title text-semibold">List Zones</h6>
                                                            <div class="heading-elements">
                                                                <div class="heading-btn">
                                                                    <a href="javascript:void(0)" class="btn btn-primary btn-labeled add_property_zone"><b><i class="icon-plus-circle2"></i></b> Add Zone</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">   
                                                            <table class="table responsive nowrap" cellspacing="0" id="property_zones_dttable">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Name</th>
                                                                        <th>Password</th>
                                                                        <th>Action(s)</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (isset($property_arr['id']) && $is_rental_service_available) { ?>
                                    <div class="tab-pane has-padding" id="general_agreement_tab">
                                        <div class="row">
                                            <div class="thumb-ul ">
                                                <div class="preview_img">
                                                    <?php
                                                    if (!empty($property_agreements_arr)) {
                                                        foreach ($property_agreements_arr as $fi) {
                                                            $path = $this->config->item('uploads_path') . '/properties/' . $property_arr['id'];
                                                            $file_extesntion = pathinfo($fi['image_name'], PATHINFO_EXTENSION);
                                                            $type = 'lightbox';
                                                            $caption_url = $img_url = base_url() . 'uploads' . '/properties/' . $property_arr['id'] . '/agreements/' . $fi['image_name'];
                                                            if ($file_extesntion == 'pdf') {
                                                                $type = 'pdf_lightbox';
                                                                $img_url = base_url() . 'uploads/pdf-default-img.png';
                                                            }
                                                            if (!file_exists($path)) {
                                                                $img_url = base_url() . 'uploads/no_image.jpg';
                                                            }
                                                            ?>
                                                            <div class="col-lg-2  <?php echo 'f_' . $fi['id'] ?>">
                                                                <div class="thumbnail">
                                                                    <div class="thumb">
                                                                        <img class="loading img_view_wrapper" src="" lsrc="<?php echo $img_url; ?>"  id="img_<?php echo $fi['id']; ?>" style="" alt="">
                                                                        <div class="caption-overflow">
                                                                            <span>
                                                                                <a href="<?php echo $caption_url; ?>" data-popup="<?php echo $type; ?>" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-zoomin3"></i></a>                                                                
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="caption">
                                                                        <h6 class="no-margin vhide" >                                                            
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
                                                    }
                                                    ?>
                                                    <div class="col-lg-2 cls_general_agreement_div custom_upload_div">
                                                        <div class="thumbnail">
                                                            <div class="thumb agreement_uploader icon_general_agreement icon_agreement_plus">
                                                                <span><i class="icon icon-images3"></i> Drag and drop files or click to select</span>
                                                            </div>

                                                            <div class="caption" style="padding:0">
                                                                <h6 class="no-margin">                                                                    
                                                                    <button type="button" class="btn_general_agreement btn btn-primary btn-xs" style="width: 100%;"><i class="i icon-images3"></i> Add Photo(s)</button>
                                                                    <input id="general_agreement" data-action="<?php echo (isset($property_arr['id']) ? 'edit' : ''); ?>"  name="general_agreement[]" class="hide" type="file">                                                        
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="text-right form-actions">
                                <button type="submit" class="btn bg-teal-400" id="add_property_btn"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>                                
                                <a href="<?php echo base_url(); ?>properties" class="btn btn-info">Cancel <i class="icon-reset position-right"></i></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="manage_zones"></div>
    <div class="modal_keyless_accesses_zone"></div>
    <?php $this->load->view('Templates/footer'); ?>
    <!-- /vertical form options -->
</div>
<script type="text/javascript">
    property_id = "<?php echo (isset($property_arr['id']) ? base64_encode($property_arr['id']) : ''); ?>";
</script>
<script type="text/javascript" src="assets/js/custom_pages/properties.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        CKEDITOR.replace('editor-full', {
            height: '400px',
            extraPlugins: 'forms'
        });
    });
</script>