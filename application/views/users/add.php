<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>users"><i class="icon-folder-upload position-left"></i> Users</a></li>  
            <li class="active"><?php echo (isset($user['id'])) ? 'Edit' : 'Create' ?>  User</li>
        </ul>
    </div>
</div>
<div class="content">   
    <!-- Horizontal form options -->
    <div class="row">

        <div class="col-md-12">
            <div class="panel panel-flat border-top-primary">
                <div class="panel-heading">
                    <h6 class="panel-title"><?php echo $title; ?></h6>                    
                </div>

                <div class="panel-body">
                    <div class="userMessageDiv"></div>
                    <div class="tabbable">
                        <ul class="nav nav-tabs nav-tabs-highlight">
                            <li class="active"><a href="#tab1" data-toggle="tab"><i class="icon-menu7 position-left"></i> User Detail</a></li>
                            <li><a href="#tab2" data-toggle="tab"><i class=" icon-user-check position-left"></i> Login</a></li>                          
                        </ul>

                        <form method="post" id="frm_user_add">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="hidden" name="id" value="" >
                                            <div class="form-group">
                                                <label>Name: <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control required" placeholder="Enter Name">
                                            </div>
                                        </div> 
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Phone: </label>
                                                <input type="text" name="phone" class="form-control" placeholder="Enter Phone">
                                            </div>
                                        </div>                                    
                                    </div>

                                    <div class="row"> 
                                        <div class="col-md-6">
                                            <label>Type:</label>
                                            <div class="form-group">
                                                <label class="radio-inline">
                                                    <input type="radio" name="type" class="ra_styled" value="Admin">
                                                    Admin
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="type" class="ra_styled" value="Basic">
                                                    Basic
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Status:</label>
                                            <div class="form-group">
                                                <label class="radio-inline">
                                                    <input type="radio" name="status" class="ra_styled" value="active">
                                                    Active
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="status" class="ra_styled" value="inactive">
                                                    In Active
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Address :</label>
                                                <input type="text" name="address" class="form-control" placeholder="Enter Address">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Zip code :</label>
                                                <input type="text" name="zip_code" class="form-control" placeholder="Enter ZipCode">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>City :</label>
                                                <input type="text" name="city" class="form-control" placeholder="Enter City">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>State/Province :</label>
                                                <input type="text" name="state" class="form-control" placeholder="Enter State">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Country :</label>
                                                <input type="text" name="country" class="form-control" placeholder="Enter Country">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email: <span class="text-danger">*</span></label>
                                                <input type="text" name="email_id" class="form-control required" placeholder="Enter Email">
                                            </div>
                                        </div>                                     
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Password:</label>
                                                <input type="text" name="password" id="password" class="form-control" placeholder="Enter Password">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Confirm Password:</label>
                                                <input type="text" name="confirm_password"  class="form-control" placeholder="Enter Confirm Password">
                                            </div>
                                        </div>                                     
                                    </div>
                                </div>
                              


                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn bg-teal-400" id="spinner-dark-6"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>                                
                                <a href="<?php echo base_url(); ?>users" class="btn btn-info">Cancel <i class="icon-reset position-right"></i></a>
                            </div>
                        </form>
                    </div>
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
    remoteURL = site_url + "home/checkUnique_Email/<?php echo (isset($user['id'])) ? $user['id'] : ''; ?>";
</script>
<script type="text/javascript" src="assets/js/custom_pages/users.js"></script>
