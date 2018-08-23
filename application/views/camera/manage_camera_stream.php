<div id="modal_manage_camera_stream" class="modal fade custom_email_modal" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close custom_close" data-dismiss="modal">&times;</button>
                <?php if (isset($camera_info) && !empty($camera_info) && !empty($camera_info['video_url'])) { ?>
                    <button type="button" class="btn btn-primary gotovideo" data-camera="" id="gotovideo" ><i class="icon-video-camera2"></i> Go to video</button>
                    <button type="button" class="btn btn-default hide gotoimage" data-camera="" id="gotoimage" >Back</button>
                <?php } ?>
            </div>
            <div class="modal-body ">
                <div class="stream_image_url_div">
                    <img src="<?php echo isset($stream_url) ? $stream_url : '' ?>" onload="load_camera(this, 1)" onerror="load_camera_error(this, 1)"/>
                </div>
                <div class="stream_video_url_div hide">
                    <?php if (isset($camera_info) && !empty($camera_info) && !empty($camera_info['video_url'])) { ?>
                        <input type="hidden" id="live_video_url" value="<?php echo $camera_info['video_url'] ?>" />
                        <div id='preview_live_video_url_div' style="text-align: center;">Loading...</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>