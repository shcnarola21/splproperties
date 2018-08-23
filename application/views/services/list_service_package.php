<div id="modal_service_package" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class=""></i> Service Packages </h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <?php echo $alert_msg; ?>
                </div>               
                <table class="table nowrap" width="100%" cellspacing="0" id="dt_list_service_package" style="margin-bottom: 10px;">
                    <thead>
                        <tr>
                            <th>Provider</th>                            
                            <th>Package</th>
                            <th>Name</th>                            
                            <th>Type</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($service_packages as $p) { ?>
                            <tr>
                                <td><?php echo $p['provider_name'] . ' (ID : ' . $p['provider_id'] . ')'; ?></td>
                                <td><?php echo $p['package_name'] . ' (ID : ' . $p['package_id'] . ')'; ?></td>                                
                                <td><?php echo $p['package_name']; ?></td>                
                                <td><?php echo $p['type']; ?></td>                
                            </tr>
                        <?php } ?>       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
