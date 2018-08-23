<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo base_url(); ?>">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $title; ?></title>
        <?php
        $class = $this->router->fetch_class();
        $action = $this->router->fetch_method();
        ?>
        <!-- Global stylesheets -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">

        <link href="assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
        <link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="assets/css/core.css" rel="stylesheet" type="text/css">
        <link href="assets/css/components.css" rel="stylesheet" type="text/css">
        <link href="assets/css/colors.css" rel="stylesheet" type="text/css">                
        <link href="assets/css/style.css" rel="stylesheet" type="text/css">
        <link href="assets/css/dashboard.css" rel="stylesheet" type="text/css">
        <link href="assets/js/plugins/uploaders/fileinput/fileinput.min.css" rel="stylesheet" type="text/css">

        <!-- /global stylesheets -->
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/logo.ico" type="image/gif" />

        <script type="text/javascript">
            var site_url = "<?php echo site_url() ?>";
            var base_url = "<?php echo base_url() ?>";
            var remoteURL = '';
        </script>

        <script type="text/javascript" src="assets/js/plugins/loaders/pace.min.js"></script>
        <script type="text/javascript" src="assets/js/core/libraries/jquery.min.js"></script>
        <script type="text/javascript" src="assets/js/core/libraries/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/loaders/blockui.min.js"></script>
        <!-- /core JS files -->

        <!-- Theme JS files -->       
        <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/styling/switch.min.js"></script>

        <script type="text/javascript" src="assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
        <script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/pickers/daterangepicker.js"></script>
        <script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>

        <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/responsive.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
        <!--<script type="text/javascript" src="assets/js/pages/datatables_api.js"></script>-->

        <script type="text/javascript" src="assets/js/plugins/forms/editable/editable.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/extensions/mockjax.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/editable/address.js"></script>

        <script type="text/javascript" src="assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/validation/validate.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/inputs/touchspin.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/media/fancybox.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/uploaders/fileinput/fileinput.min.js"></script>

        <script type="text/javascript" src="assets/js/pages/uploader_bootstrap.js"></script>
        <script type="text/javascript" src="assets/js/pages/jQueryRotateCompressed.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>

        <script type="text/javascript" src="assets/js/core/app.js"></script>
        <script type="text/javascript" src="assets/js/pages/form_checkboxes_radios.js"></script>

        <script type="text/javascript" src="assets/js/pages/form_select2.js"></script>
        <script type="text/javascript" src="assets/js/pages/invoice_grid.js"></script>
        <script type="text/javascript" src="assets/js/pages/form_layouts.js"></script>
        <script type="text/javascript" src="assets/js/pages/form_multiselect.js"></script>
        <script type="text/javascript" src="assets/js/plugins/buttons/spin.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/buttons/ladda.min.js"></script>
        <script type="text/javascript" src="assets/js/pages/components_buttons.js"></script>

        <script type="text/javascript" src="assets/js/plugins/forms/wizards/steps.min.js"></script>    
        <script type="text/javascript" src="assets/js/bootstrap-rating.min.js"></script>    
        <script type="text/javascript" src="assets/ckeditor/ckeditor.js"></script>        
        <script type="text/javascript" src="assets/js/application.js"></script>

        <script>
            $(function () {
                Pace.on('start', function () {
                    $('#custom_loading').show();
                });
                Pace.on('done', function () {
                    $('#custom_loading').hide();
                });
                // Lightbox
                $('[data-popup="lightbox"]').fancybox({
                    padding: 3
                });
                $('[data-popup="pdf_lightbox"]').fancybox({
                    padding: 3,
                    type:'iframe'
                });
            })
        </script>
        <!-- /theme JS files -->

    </head>
    <?php
    $body_class = "";
    if (isset($_COOKIE['sidebar_event']) && $_COOKIE['sidebar_event'] == 'close') {
        $body_class = 'sidebar-xs';
    }
    ?>
    <body class="<?php echo $body_class; ?>">

        <?php
        $user_data = $this->session->userdata('session_info');
        $user_type = $user_data['type'];
        $user_id = $user_data['user_id'];
        $username = $user_data['username'];
        $is_master_admin = is_master_admin();
        $is_provider = is_provider();
        $is_rental_service_available = is_rental_service_available();
        $is_provider_login = is_provider_login();
        ?>
        <!-- Main navbar -->
        <div class="navbar navbar-inverse custom_navbar ">
            <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo site_url('/'); ?>" style="letter-spacing: 3px;font-weight: 500;font-size: 16px;line-height:2">
                    <!-- <img src="<?php // echo base_url();                                       ?>assets/frontend/images/logo.jpg" alt="site logo"> -->
                    <?php
                    if ($is_master_admin) {
                        echo "Master Panel";
                    } else {
                        echo strtoupper($username);
                    }
                    ?>
                </a>

                <ul class="nav navbar-nav visible-xs-block">
                    <li><a class="sidebar-mobile-main-toggle"><img src="assets/images/hamburger.png" alt=""></a></li>
                </ul>
            </div>


            <div class="navbar-collapse collapse" id="navbar-mobile">
                <ul class="nav navbar-nav">
                    <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><img src="assets/images/hamburger.png" alt=""></a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <?php
                    $user_data = $this->session->userdata('session_info');
                    $master_id = $this->session->userdata('master_id');
                    if ($is_provider) {
                        if (!$is_provider_login) {
                            ?>
                            <li class="dropdown dropdown-user">
                                <a href="<?php echo base_url(); ?>users/autologin/<?php echo base64_encode($master_id); ?>" class="dropdown-toggle">
                                    <i class="icon-arrow-left7"></i>
                                    <span>Back To Admin Panel</span>
                                </a>
                            </li>
                            <?php
                        }
                    }
                    ?>
                    <li class="dropdown dropdown-user">
                        <a class="dropdown-toggle" data-toggle="dropdown">
                            <span><?php echo strtoupper($username); ?></span>
                            <i class="caret"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="javascript:void(0)"><i class="icon-user-plus"></i> My profile</a></li>
                            <li><a href="<?php echo site_url('/logout'); ?>"><i class="icon-switch2"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /main navbar -->
        <!-- Page container -->
        <div class="page-container custom_content">
            <div class="page-content">
                <div class="sidebar sidebar-main">
                    <div class="sidebar-content">

                        <div class="sidebar-category sidebar-category-visible">
                            <div class="category-content no-padding">
                                <ul class="navigation navigation-main navigation-accordion">
                                     <li class="navigation-header"><span></span></li>
                                    <li class = "<?php echo ($class == 'dashboard') ? 'active' : '' ?>"><a href = "<?php echo site_url('dashboard'); ?>"><i class = "icon-home4"></i> <span>Dashboard</span></a></li>

                                    <?php if ($is_master_admin) { ?>
                                        <li class="<?php echo ($class == 'providers') ? 'active' : '' ?>"><a href="<?php echo site_url('providers'); ?>"><i class="icon-users"></i> <span>Providers</span></a></li>
                                    <?php } ?>
                                    <?php if ($is_provider) {
                                        ?>
                                        <li class="<?php echo ($class == 'customers') ? 'active' : '' ?>"><a href="<?php echo base_url('customers'); ?>"><i class="icon-users"></i> <span>Customers</span></a></li>
                                        <?php if (!$is_rental_service_available) { ?>
                                        <li class="<?php echo ($class == 'packages') ? 'active' : '' ?>"><a href="<?php echo base_url('packages'); ?>"><i class="icon-list"></i> <span>Packages</span></a></li>
                                        <?php } ?>
                                        <li class="<?php echo ($class == 'messaging') ? 'active' : '' ?>"><a href="<?php echo base_url('messaging'); ?>"><i class="icon-mail5"></i> <span>Messaging</span></a></li>
                                        <?php if ($is_rental_service_available) { ?>
                                            <li class="<?php echo ($class == 'todolist') ? 'active' : '' ?>"><a href="<?php echo base_url('todolist'); ?>"><i class="icon-clipboard5"></i> <span>Maintenace / Repair(s)</span></a></li>
                                            <li class="<?php echo ($class == 'camera') ? 'active' : '' ?>"><a href="<?php echo base_url('camera'); ?>"><i class="icon-camera"></i> <span>Camera(s)</span></a></li>
                                            <li class="<?php echo ($class == 'keylessaccess') ? 'active' : '' ?>"><a href="<?php echo base_url('keylessaccess'); ?>"><i class="icon-keyboard"></i> <span>Keyless Access</span></a></li>
                                            <?php
                                        }
                                    }
                                    $controller_arr = array('users', 'services', 'properties', 'canned_responses', 'tax_setting', 'templates', 'send_email', 'payment_system');
                                    ?>
                                    <li class="<?php echo in_array($class, $controller_arr) ? 'active' : ''; ?>">
                                        <a href="#" class="has-ul"><i class="icon-gear"></i> <span>Settings</span></a>
                                        <ul class="hidden-ul" style="<?php echo in_array($class, $controller_arr) ? 'display:block' : ''; ?>">
                                            <li class="<?php echo ($class == 'users') ? 'active' : '' ?>"><a href="<?php echo base_url('users'); ?>"><i class="icon-users"></i> <span>Users</span></a></li>
                                            <?php if ($is_master_admin) { ?>
                                                <li class="<?php echo ($class == 'services') ? 'active' : '' ?>"><a href="<?php echo site_url('services'); ?>"><i class="icon-task"></i> <span>Services</span></a></li>
                                            <?php } ?>
                                            <?php
                                            if ($is_provider) {
                                                if ($is_rental_service_available) {
                                                    ?>
                                                    <li class="<?php echo ($class == 'properties') ? 'active' : '' ?>"><a href="<?php echo base_url('properties'); ?>"><i class="icon-office"></i> <span>Property(s)</span></a></li>
                                                    <li class="<?php echo ($class == 'canned_responses') ? 'active' : '' ?>"><a href="<?php echo base_url('canned_responses'); ?>"><i class="icon-bubbles2"></i> <span>Canned Responses</span></a></li>
                                                    <li class="<?php echo ($class == 'tax_setting') ? 'active' : '' ?>"><a href="<?php echo base_url('tax_setting'); ?>"><i class="icon-stats-growth"></i> <span>Tax Setting</span></a></li>
                                                <?php }
                                                ?>  
                                                <li class="<?php echo ($class == 'payment_system') ? 'active' : '' ?>"">
                                                    <a href="#" class="has-ul"><i class="icon-coin-dollar"></i> <span>Payment System</span></a>
                                                    <ul class="hidden-ul" style="<?php echo in_array($class, array('payment_system')) ? 'display:block' : ''; ?>">                                                
                                                        <li class="<?php echo ($class == 'payment_system') ? 'active' : '' ?>"><a href="<?php echo base_url('payment_system'); ?>"><i class="icon-list2"></i> Payment Settings</a></li>                                             
                                                    </ul>
                                                </li>
                                                <li class="<?php echo ($class == 'send_email') ? 'active' : '' ?>"><a href="<?php echo base_url('send_email'); ?>"><i class="icon-mail5"></i> <span>Send Email Settings</span></a></li>
                                                <li class="<?php echo ($class == 'templates') ? 'active' : '' ?>"><a href="<?php echo base_url('templates'); ?>"><i class="icon-list2"></i> <span>Templates</span></a></li>
                                            <?php }
                                            ?>
                                        </ul>
                                    </li>                                                   
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class = "content-wrapper">
                    <div id = "custom_loading" class = "hide">
                        <div id = "loading-center">
                            <svg version = "1.1" id = "L7" xmlns = "http://www.w3.org/2000/svg" xmlns: xlink = "http://www.w3.org/1999/xlink" x = "0px" y = "0px" viewBox = "0 0 100 100" enable-background = "new 0 0 100 100" xml: space = "preserve">
                            <path fill = "#009688" d = "M31.6,3.5C5.9,13.6-6.6,42.7,3.5,68.4c10.1,25.7,39.2,38.3,64.9,28.1l-3.1-7.9c-21.3,8.4-45.4-2-53.8-23.3c-8.4-21.3,2-45.4,23.3-53.8L31.6,3.5z">
                            <animateTransform attributeName = "transform" attributeType = "XML" type = "rotate" dur = "2s" from = "0 50 50" to = "360 50 50" repeatCount = "indefinite" />
                            </path>
                            <path fill = "#26A69A" d = "M42.3,39.6c5.7-4.3,13.9-3.1,18.1,2.7c4.3,5.7,3.1,13.9-2.7,18.1l4.1,5.5c8.8-6.5,10.6-19,4.1-27.7c-6.5-8.8-19-10.6-27.7-4.1L42.3,39.6z">
                            <animateTransform attributeName = "transform" attributeType = "XML" type = "rotate" dur = "1s" from = "0 50 50" to = "-360 50 50" repeatCount = "indefinite" />
                            </path>
                            <path fill = "#74afa9" d = "M82,35.7C74.1,18,53.4,10.1,35.7,18S10.1,46.6,18,64.3l7.6-3.4c-6-13.5,0-29.3,13.5-35.3s29.3,0,35.3,13.5L82,35.7z">
                            <animateTransform attributeName = "transform" attributeType = "XML" type = "rotate" dur = "2s" from = "0 50 50" to = "360 50 50" repeatCount = "indefinite" />
                            </path>
                            </svg>
                        </div>
                    </div>
                    <?php echo $body; ?> 
                </div>
            </div>
        </div>

        <script  >
            $('document').ready(function () {
                $('.flashmsg').fadeOut(6000);

            });
        </script>
    </body>
</html>
<!--/Page container-->