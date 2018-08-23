<div id="template_preview_modal" class="modal fade template_preview_modal">
   <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="position-left"></i> Preview</h5>
            </div>
            <div class="modal-body">
                <div class="userMessageDiv"></div>                    
                <div class="modal-body">
                    <div class="template_loader">Loading..</div>
                    <div class="template_content">
                        <?php
                        echo isset($message) ? $message : '';                        
                        ?>            
                    </div>

                </div>
            </div>               
        </div>
    </div>
</div>
</div>
