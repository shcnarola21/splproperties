<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>providers"><i class="icon-folder-upload position-left"></i> Providers</a></li>  
            <li class="active"><?php echo (isset($provider['id'])) ? 'Edit' : 'Create' ?>  Provider</li>
        </ul>
    </div>
</div>
<div class="content">   
    <!-- Horizontal form options -->
    <div class="row">        
        <div class="col-md-12">
            <div class="panel panel-flat border-top-primary">
                <div class="panel-heading">
                    <h6 class="panel-title"><?php echo (isset($provider)) ? "Edit Provider" : "Create Provider" ?></h6>               
                </div>

                <div class="panel-body">
                    <form method="post" class="form-wrapper" id="<?php echo (isset($provider)) ? "frm_provider_edit" : "frm_provider_add" ?>">
                        <div class="userMessageDiv"></div>
                        <div class="tabbable">
                            <ul class="nav nav-tabs nav-tabs-highlight">
                                <li class="li_provider_info active"><a href="#tab1" data-toggle="tab"><i class="icon-menu7 position-left"></i> Provider Detail</a></li>
                                <li class="li_login_info"><a href="#tab2" data-toggle="tab"><i class=" icon-user-check position-left"></i> Login</a></li>                          
                            </ul>                        
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="hidden" id="provider_id" name="id" value="<?php echo (isset($provider['id'])) ? base64_encode($provider['id']) : ''; ?>">
                                            <div class="form-group">
                                                <label>Name:</label>
                                                <input type="text" name="name" class="form-control" placeholder="Enter Name" value="<?php echo (isset($provider['name'])) ? $provider['name'] : ''; ?>">
                                            </div>
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Phone: </label>
                                                <input type="text" name="phone" class="form-control" placeholder="Enter Phone" value="<?php echo (isset($provider['phone'])) ? $provider['phone'] : ''; ?>">
                                            </div>
                                        </div>                                    
                                    </div>

                                    <div class="row">                                   
                                        <div class="col-md-6">
                                            <label>Status:</label>
                                            <div class="form-group">
                                                <label class="radio-inline">
                                                    <?php
                                                    $checked = '';
                                                    if(isset($provider['status']) && $provider['status'] == 'active'){
                                                        $checked = 'checked="checked"';
                                                    }elseif(!isset($provider)){
                                                        $checked = 'checked="checked"';
                                                    }
                                                    ?>
                                                    <input type="radio" name="status" class="styled"  value="active" <?php echo $checked;?> >
                                                    Active
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="status" class="styled" value="inactive" <?php echo (isset($provider['status']) && $provider['status'] == 'inactive') ? 'checked="checked"' : ''; ?>>
                                                    In Active
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">                                   
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="display-block text-semibold">Services:</label>
                                                <?php
                                                $provider_services = array();
                                                if (isset($provider['services']) && !empty($provider['services'])) {
                                                    $provider_services = explode(',', $provider['services']);
                                                }
                                                if (isset($services) && !empty($services)) {
                                                    foreach ($services as $service) {
                                                        ?>
                                                        <label class="checkbox-inline">
                                                            <input type="checkbox" class="styled" name="services[]" value="<?php echo $service['id'] ?>" <?php echo (in_array($service['id'], $provider_services)) ? 'checked="checked"' : ''; ?>>
                                                            <?php echo $service['service_name'] ?>
                                                        </label>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Address :</label>
                                                <input type="text" name="address" class="form-control" placeholder="Enter Address" value="<?php echo (isset($provider['address'])) ? $provider['address'] : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Zip code :</label>
                                                <input type="text" name="zip_code" class="form-control" placeholder="Enter ZipCode" value="<?php echo (isset($provider['zip_code'])) ? $provider['zip_code'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>City :</label>
                                                <input type="text" name="city" class="form-control" placeholder="Enter City" value="<?php echo (isset($provider['city'])) ? $provider['city'] : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>State/Province :</label>
                                                <input type="text" name="state" class="form-control" placeholder="Enter State" value="<?php echo (isset($provider['state'])) ? $provider['state'] : ''; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Country :</label>
                                                <input type="text" name="country" class="form-control" placeholder="Enter Country" value="<?php echo (isset($provider['country'])) ? $provider['country'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email: </label>
                                                <input type="text" name="email_id" class="form-control required" placeholder="Enter Email" value="<?php echo (isset($provider['email'])) ? $provider['email'] : ''; ?>">
                                            </div>
                                        </div>                                     
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Password:</label>
                                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Confirm Password:</label>
                                                <input type="password" name="confirm_password"  class="form-control" placeholder="Enter Confirm Password">
                                            </div>
                                        </div>                                     
                                    </div>
                                </div>
                            </div>
                            <div class="text-right frm-action">
                                <button type="submit" class="btn bg-teal-400" id="spinner-dark-6"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>                                
                                <a href="<?php echo base_url(); ?>providers" class="btn btn-info">Cancel <i class="icon-reset position-right"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">

        </div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
    <!-- /vertical form options -->
</div>

<script type="text/javascript">
    remoteURL = site_url + "home/checkUnique_Email/<?php echo (isset($provider['id'])) ? $provider['id'] : ''; ?>";
</script>
<script type="text/javascript" src="assets/js/custom_pages/providers.js"></script>
