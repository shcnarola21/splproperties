<!DOCTYPE html>
<html class="no-js" lang="en">
    <head>
        <base href="<?php echo base_url(); ?>">
        <meta content="charset=utf-8">
        <title><?php echo $title; ?></title>
        <base href="<?php echo base_url(); ?>">
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/frontend/images/ls3.png" type="image/x-icon"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <link href="assets/css/bootstrap.css" rel="stylesheet"/>
        <link href="assets/css/fontawesome-all.css" rel="stylesheet"/>
        <link href="assets/css/custom.css" rel="stylesheet"/>
        <link href="assets/css/developer.css" rel="stylesheet"/>

        <script type="text/javascript" src="assets/js/core/libraries/jquery.min.js"></script>
        <script src="assets/js/bootstrap.js" ></script>
        <script type="text/javascript" src="assets/js/plugins/forms/validation/validate.min.js"></script>
    </head>
    <body>
        <?php echo $body; ?>
        <script>
            $(document).ready(function () {
                $('.carousel').carousel({
                    interval: false
                })
            })
        </script>
    </body>
</html>