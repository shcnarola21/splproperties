<script type="text/javascript">
    function load_camera(event, second) {
        setTimeout(function () {
            var src = $(event).attr('src');
            if (src.lastIndexOf("t=") <= 0) {
                src += '&t=';
            }
            src = src.substring(0, (src.lastIndexOf("t=") + second)) + (new Date()).getTime();
            $(event).attr('src', src);
            $(event).siblings('div').find('.camera_url').attr('href', src);
        }, (second * 1000));
    }
    function load_camera_error(event, second) {
        setTimeout(function () {
            var src = $(event).attr('src');
            if (src.lastIndexOf("t=") <= 0) {
                src += '&t=';
            }
            src = src.substring(0, (src.lastIndexOf('t=') + second)) + (new Date()).getTime();
            $(event).attr('src', src);
            $(event).siblings('div').find('.camera_url').attr('href', src);
        }, (second * 1000));
    }
</script>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url('camera'); ?>"><i class="icon-camera position-left"></i> Camera(s)</a></li>            
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <?php if (isset($properties) && !empty($properties)) { ?>
            <div class="col-md-12">
                <div class="camera-structure">
                    <?php
                    foreach ($properties as $property) {
                        ?>
                        <div class="col-md-3">
                            <div class="panel panel-white border-top-primary">
                                <div class="panel-heading">
                                    <h5 class="panel-title"><i class="icon-office position-left"></i> <?php echo $property['address'] ?></h5>
                                    <div class="heading-elements r-drop-arrow">
                                        <ul class="icons-list">
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class=" icon-arrow-down12"></i></a>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li><a href="javascript:void(0);" data-property="<?php echo base64_encode($property['id']) ?>" class="property_camera"><i class="icon-pencil5"></i> Edit</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <ul class="square-ul">
                                    <?php
                                    if (isset($property['cameras'])) {
                                        foreach ($property['cameras'] as $k => $v) {
                                            ?>
                                            <li class="square-ul-2">
                                                <div class="square-li-div">
                                                    <div class="thumb">
                                                        <img src="<?php echo $v['stream_url'] ?>" onload="load_camera(this, 5)" onerror="load_camera_error(this, 5)"/>
                                                        <div class="caption-overflow">
                                                            <span>
                                                                <a href="javascript:void(0)" data-camera="<?php echo base64_encode($v['id']); ?>" data-url="<?php echo $v['stream_url']; ?>" class="camera_url btn border-white text-white btn-flat btn-icon btn-rounded" title="<?php echo $v['name'] ?>"><i class="icon-zoomin3"></i></a>                                                                
                                                                <a href="javascript:void(0)" data-camera="<?php echo base64_encode($v['id']); ?>" class="delete_camera btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-trash"></i></a>                                                                
                                                                <br/>
                                                                <a href="javascript:void(0)" class="btn text-white"><?php echo $v['name'] ?></a>                                                                
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <li class="square-ul-1">
                                            <div class="square-li-div">
                                                <h4 class="">No Camera</h4>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>            
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="manage_camera_div"></div>
    <div class="manage_camera_stream"></div>
    <script type="text/javascript" src="assets/js/custom_pages/camera.js"></script>
    <script type="text/javascript" src="assets/js/jwplayer/jwplayer.js"></script>
    <script>jwplayer.key = "88nVVnBJtVZ9Dm+LfyqKb0oRDU2zXpauKqRYGg==";</script>
    <?php $this->load->view('Templates/footer'); ?>
</div>