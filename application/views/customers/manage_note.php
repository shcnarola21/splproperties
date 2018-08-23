<div id="modal_manage_notes" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close custom_close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="<?php echo isset($note_info['id']) ? "icon-pencil5" : "icon-plus-circle2"; ?> position-left"></i><?php echo isset($note_info['id']) ? "Edit" : "Add"; ?> Note</h5>
            </div>
            <form class="form-horizontal" method="post" id="manage_note_frm" >
                <input type="hidden" name="note_id" value="<?php echo isset($note_info['id']) ? base64_encode($note_info['id']) : '' ?>">
                <div class="modal-body edit_customer_wrapper">
                    <div class="userMessageDiv"></div>
                    <div class="row">
                        <input type="hidden" value="<?php echo isset($note_for) ? $note_for : '' ?>" name="note_for"/>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Notes</label>
                                <div class="col-sm-10">
                                    <textarea id="notes" name="notes" rows="6" class="form-control"><?php echo isset($note_info['notes']) ? $note_info['notes'] : '' ?></textarea>                                     
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions">
                        <button value="submit" class="btn btn-primary btn_notes"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>
                        <button type="button" class="btn btn-default custom_close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
