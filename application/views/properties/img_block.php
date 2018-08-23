<div class="col-lg-2 f_<?php echo $id ?>">
    <div class="thumbnail">
        <div class="thumb">
            <?php
            $type = 'lightbox';
            $file_extesntion = pathinfo($image_name, PATHINFO_EXTENSION);
            $caption_url = $img_url = base_url() . 'uploads' . '/properties/' . $property_id . '/' . $image_name . '?v=' . date('Ymdhis');
            if ($file_extesntion == 'pdf') {
                $type = 'pdf_lightbox';
                $caption_url = base_url() . 'uploads' . '/properties/' . $property_id . '/agreements/' . $image_name . '?v=' . date('Ymdhis');
                $img_url = base_url() . 'uploads/pdf-default-img.png';
            }
            ?>
            <img class="loading img_view_wrapper" src="" lsrc="<?php echo $img_url; ?>"  id="img_<?php echo $id; ?>" style="cursor: pointer;" alt="">
            <div class="caption-overflow">
                <span>
                    <a href="<?php echo $caption_url; ?>" data-popup="<?php echo $type; ?>" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-zoomin3"></i></a>                                                                
                </span>
            </div>
        </div>
        <div class="caption">
            <h6 class="no-margin">                                                            
                <a href="#" class="text-default">&nbsp;</a>   
                <?php if ($file_extesntion != 'pdf') { ?>
                    <a href="javascript:void(0)" data-nextangle="0" class="text-muted rotate_img" id="<?php echo $id; ?>">
                        <i class="icon-reload-alt pull-left" title="Rotate Image"></i>
                    </a>
                <?php } ?>
                <a href="#" class="text-muted delete_fi" id="<?php echo $id; ?>">
                    <i class="icon-bin pull-right" title="Remove File"></i>
                </a>
            </h6>
        </div>
    </div> 
</div>