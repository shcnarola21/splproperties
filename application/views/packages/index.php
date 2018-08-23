<?php
$user_type = $this->session->userdata('type');
?>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('packages'); ?>"><i class="icon-list position-left"></i> Packages</a></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="packages_setting_add">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-white border-top-primary">
                        <div class="panel-heading">
                            <div class="text-right"></div>
                            <div class="text-left" style="display: flex;">
                                <h5 class="panel-title">List Packages</h5>
                                <button id="packages_add" class="btn btn-primary btn-labeled text-right" style="margin-left: auto;"><b><i class="icon-plus-circle2"></i></b> Add Package</button>
                                <button type="button" class="btn btn-primary btn-labeled text-right add_setup_fee_btn " data-toggle="modal" id="add_setup_fee" style="display:none;margin-left: auto;"><b><i class="icon-plus-circle2"></i></b> Add Setup Fee</button>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="col-md-12">
                                <div class="userMessageDiv"></div>
                                <div class="tabbable packages_listing">
                                    <ul class="nav nav-tabs nav-tabs-highlight">
                                        <?php
                                        if (isset($provider_services) && !empty($provider_services)) {
                                            $count = 0;
                                            foreach ($provider_services as $service) {
                                                ?>
                                                <li class="package_services <?php echo $count == 0 ? 'active' : '' ?>"><a href="#tab_<?php echo $service['service_id'] ?>" data-service="<?php echo $service['service_id'] ?>" data-toggle="tab"><i class=""></i> <?php echo$service['service_name'] ?></a></li>
                                                <?php
                                                $count++;
                                            }
                                            if (count($provider_services) > 1) {
                                                ?>
                                                <li class="package_services" ><a href="#tab_bundle" data-service="bundle" data-toggle="tab"><i class=""></i> Bundle(s)</a></li>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </ul>

                                    <div class = "tab-content">
                                        <?php
                                        if (isset($provider_services) && !empty($provider_services)) {
                                            $count = 0;
                                            foreach ($provider_services as $service) {
                                                ?>
                                                <div class= "tab-pane <?php echo $count == 0 ? 'active' : '' ?>" id = "tab_<?php echo $service['service_id'] ?>">
                                                    <div class = "row">
                                                        <div class = "col-md-12">
                                                            <div class = "tabbable nav-tabs-vertical nav-tabs-left packages_div">
                                                                <ul class = "nav nav-tabs nav-tabs-highlight">
                                                                    <li class = "active text-semibold"><a href = "#basic_<?php echo $service['service_id'] ?>" class = "tab_change" data-toggle = "tab">Main</a></li>
                                                                    <li class = "text-semibold"><a href = "#addon_<?php echo $service['service_id'] ?>" class = "tab_change" data-toggle = "tab">Addon(s)</a></li>
                                                                    <li class = "text-semibold"><a href = "#setup_fee_<?php echo $service['service_id'] ?>" class = "tab_setup_fee tab_change" data-service="<?php echo $service['service_id'] ?>"data-toggle = "tab">Setup Fee(s)</a></li>
                                                                </ul>
                                                                <div class = "tab-content">
                                                                    <div class = "tab-pane active form" id = "basic_<?php echo $service['service_id'] ?>">
                                                                        <div class="panel panel-white border-top-primary">
                                                                            <div class="panel-body">
                                                                                <table class="table  nowrap" width = "100%" cellspacing = "0" id = "basic_dttable_<?php echo $service['service_id'] ?>">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Id</th>
                                                                                            <th>Package Name</th>
                                                                                            <th>Packages Price</th>
                                                                                            <th>Type</th>
                                                                                            <th>Action</th>
                                                                                        </tr>
                                                                                    </thead>

                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class = "tab-pane" id = "addon_<?php echo $service['service_id'] ?>">
                                                                        <div class="panel panel-white border-top-primary">
                                                                            <div class="panel-body">
                                                                                <table class = "table nowrap" width = "100%" cellspacing = "0" id = "addon_dttable_<?php echo $service['service_id'] ?>">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Id</th>
                                                                                            <th>Package Name</th>
                                                                                            <th>Packages Price</th>
                                                                                            <th>Type</th>
                                                                                            <th>Action</th>
                                                                                        </tr>
                                                                                    </thead>

                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class= "tab-pane setup_fee_div" id = "setup_fee_<?php echo $service['service_id'] ?>">
                                                                        <div class="panel panel-white border-top-primary">
                                                                            <div class="panel-body">
                                                                                <table class="table nowrap" width = "100%" cellspacing = "0" id = "setupfee_dttable_<?php echo $service['service_id'] ?>">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Id</th>
                                                                                            <th>Province/State</th>
                                                                                            <th>Fees</th>                                                                                    
                                                                                            <th>Action</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                $count++;
                                            }
                                            if (count($provider_services) > 1) {
                                                ?>
                                                <div class= "tab-pane" id = "tab_bundle">
                                                    <div class = "row">
                                                        <div class = "col-md-12" id="basic_bundle">
                                                            <table class="table nowrap" width = "100%" cellspacing = "0" id = "basic_dttable_bundle">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Id</th>
                                                                        <th>Package Name</th>
                                                                        <th>Packages Price</th>
                                                                        <th>Type</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
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

                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="modal_add_setupfee"></div>
    <div class="modal_package_customer"></div>
    <script type="text/javascript" src="assets/js/custom_pages/package.js"></script>
   <?php $this->load->view('Templates/footer'); ?>
</div>
