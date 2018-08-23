<?php
if (isset($services_arr) && !empty($services_arr)) {
    $services_arr = $services_arr[0];
}
?>
<div id="modal_manage_package_history" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="icon-list position-left"></i> Yearly Auto Increase Package Prices</h5>
            </div>
            <div class="modal-body">
                <div class="userMessageDiv"></div>
                <table class="table nowrap" width="100%" cellspacing="0" id="dt_list_package_history" style="margin-bottom: 10px;">
                    <thead>
                        <tr>
                            <th>ID</th>                            
                            <th>Package</th>
                            <th style="display:none;">history  date</th> 
                            <th>Percentage</th>
                            <th>Org Price</th>                            
                            <th>Updated Price</th>                            
                            <th>Date</th>                            
                        </tr>
                    </thead>
                    <tbody>                             
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
