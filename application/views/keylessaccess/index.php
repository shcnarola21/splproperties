<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url('keylessaccess'); ?>"><i class="icon-keyboard position-left"></i> Keyless Access</a></li>            
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>
            <div class="row">
                <div class="panel panel-white border-top-primary">
                    <div class="panel-heading">
                        <div class="text-right"></div>
                        <div class="text-left" style="display: flex;">
                            <h5 class="panel-title">List Property Keyless Access</h5>
                            <button type="button" class="btn btn-primary btn-labeled text-right add_fob_btn " data-toggle="modal" <?php echo (isset($properties) && !empty($properties)) ? 'data-property="' . $properties[0]['id'] . '"' : '' ?> id="add_fob" style="margin-left: auto;"><b><i class="icon-plus-circle2"></i></b> Add FOB</button>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="userMessageDiv"></div>
                            <div class="tabbable packages_listing">
                                <ul class="nav nav-tabs nav-tabs-highlight">
                                    <?php
                                    if (isset($properties) && !empty($properties)) {
                                        $count = 0;
                                        foreach ($properties as $property) {
                                            ?>
                                            <li class="property_access <?php echo $count == 0 ? 'active' : '' ?>"><a onclick="property_keylessaccess('<?php echo $property['id']; ?>')" href="#tab_<?php echo $property['id'] ?>" data-property="<?php echo $property['id'] ?>" data-toggle="tab"><i class=""></i> <?php echo $property['address'] ?></a></li>
                                            <?php
                                            $count++;
                                        }
                                    }
                                    ?>
                                </ul>
                                <div class = "tab-content">
                                    <?php
                                    if (isset($properties) && !empty($properties)) {
                                        $count = 0;
                                        foreach ($properties as $property) {
                                            ?>
                                            <div class= "tab-pane <?php echo $count == 0 ? 'active' : '' ?>" id = "tab_<?php echo $property['id'] ?>">
                                                <div class = "row">
                                                    <div class = "col-md-12">
                                                        <table class="table datatable-basic" id = "keyless_access_dttable_<?php echo $property['id'] ?>">
                                                            <thead>
                                                                <tr>
                                                                    <th>FOB ID</th>
                                                                    <th>Password</th>
                                                                    <th>Building Address</th>
                                                                    <th>Unit Number</th>
                                                                    <th>Zone(s)</th>
                                                                    <th>Action</th>                                            
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            $count++;
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
    <div class="manage_keyless_access_div"></div>
    <script type="text/javascript" src="assets/js/custom_pages/keylessaccess.js"></script>
    <?php $this->load->view('Templates/footer'); ?>
</div>