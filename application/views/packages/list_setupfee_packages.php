<div id="modal_setupfee_package" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class=""></i> Setup Fee Packages </h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <?php echo $alert_msg; ?>
                </div>
                <table class="table responsive nowrap" width="100%" cellspacing="0" id="dt_list_setup_package" style="margin-bottom: 10px;">
                    <thead>
                        <tr>
                            <th>Package Id</th>
                            <th>Name</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($setup_fee_packages as $sf) { ?>
                            <tr>
                                <td><?php echo $sf['id']; ?></td>
                                <td><?php echo $sf['name']; ?></td>                
                            </tr>
                        <?php } ?>       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
