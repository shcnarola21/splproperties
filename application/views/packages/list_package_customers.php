<div id="modal_package_customers" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class=""></i>Package Customers </h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <?php echo $alert_msg; ?>
                </div>
                <table class="table responsive nowrap" width="100%" cellspacing="0" id="dt_package_customers" style="margin-bottom: 10px;">
                    <thead>
                        <tr>
                            <th>Customer Id</th>
                            <th>Name</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $c) { ?>
                            <tr>
                                <td><?php echo $c['cid']; ?></td>
                                <td><?php echo $c['name']; ?></td>                
                            </tr>
                        <?php } ?>       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
