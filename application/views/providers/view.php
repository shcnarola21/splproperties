<style>
    .list-group-item > .label{float: none;}
</style>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url('providers'); ?>"><i class="icon-users position-left"></i> Providers</a></li>      
        </ul>
    </div>
</div>

<div class="content">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel border-top-xlg border-top-primary panel-white">
            <div class="panel-heading " role="tab" id="heading1">
                <h4 class="panel-title">
                    <?php echo isset($user) ? $user['name'] . "'s" : '' ?> Provider Details
                </h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered page_details" data-alert="" data-all="189">
                        <tbody>
                            <tr>
                                <th class="text-nowrap">Provider Name :</th>
                                <td><?php echo isset($user['name']) ? $user['name'] : '-' ?></td>
                            </tr>
                            <tr>
                                <th class="text-nowrap">Email :</th>
                                <td><?php echo isset($user['email']) ? $user['email'] : '-' ?></td>
                            </tr>
                            <tr>
                                <th class="text-nowrap">Address :</th>
                                <td><?php echo isset($user['address']) ? $user['address'] : '-' ?></td>
                            </tr>
                            <tr>
                                <th class="text-nowrap">City :</th>
                                <td><?php echo isset($user['city']) ? $user['city'] : '-' ?></td>
                            </tr>
                            <tr>
                                <th class="text-nowrap">State :</th>
                                <td><?php echo isset($user['state']) ? $user['state'] : '-' ?></td>
                            </tr>
                            <tr>
                                <th class="text-nowrap">Country :</th>
                                <td><?php echo isset($user['country']) ? $user['country'] : '-' ?></td>
                            </tr>
                            <tr>
                                <th class="text-nowrap">Zipcode :</th>
                                <td><?php echo isset($user['zip_code']) ? $user['zip_code'] : '-' ?></td>
                            </tr>
                            <tr>
                                <th class="text-nowrap">Phone Number :</th>
                                <td><?php echo isset($user['phone']) ? $user['phone'] : '-' ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
   
    <div class="modal_div dv_view"></div>
    <?php $this->load->view('Templates/footer'); ?>
</div>