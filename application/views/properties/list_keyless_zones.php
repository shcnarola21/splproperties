<div id="modal_keyless_accesses_zone" class="modal fade">
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
                <table class="table responsive nowrap" width="100%" cellspacing="0" id="dt_keyless_accesses_zone" style="margin-bottom: 10px;">
                    <thead>
                        <tr>
                            <th>Keyless Access Id</th>
                            <th>FOB ID</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($keyless_accesses as $k) { ?>
                            <tr>
                                <td><?php echo $k['id']; ?></td>
                                <td><?php echo $k['fob']; ?></td>                
                            </tr>
                        <?php } ?>       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
