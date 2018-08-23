<?php
$notification_type = array('notification' => 'Notification',
    'eviction' => 'Eviction notice');
?>
<div id = "eviction_modal" class = "modal fade custom_eviction_modal" data-backdrop = "static">
    <div class = "modal-dialog modal-full">
        <div class = "modal-content">
            <div class = "modal-header bg-primary">
                <button type = "button" class = "close custom_close" data-dismiss="modal">&times;
                </button>
                <h5 class = "modal-title"><i class = "icon-mail5 position-left"></i> Send Notice</h5>
            </div>
            <form class = "form-horizontal" method = "post" id = "customer_send_notice_frm">
                <div class = "modal-body edit_customer_wrapper">
                    <div class = "userMessageDiv"></div>
                    <input type = "hidden" name = "customer_id" value = "<?php echo $customer['cid']; ?>">
                    <div class = "row">
                        <div class = "col-md-12">
                            <div class = "form-group">
                                <label class="control-label col-sm-2">Type</label>
                                <div class = "col-sm-4">
                                    <select name="notify_type" id="notify_type" class = "select">
                                        <option value ="">Select Type</option>
                                        <?php
                                        foreach ($notification_type as $key => $value) {
                                            ?>
                                            <option value="<?php echo $key; ?>" <?php echo ($key == 'notification') ? 'selected="selected"' : '' ?>><?php echo $value ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="notice_div">
                        <?php $this->load->view('eviction/notification_content') ?>
                    </diV>
                </div>
                <div class="modal-footer">
                    <div class="form-actions">
                        <button value="submit" class="btn btn-primary btn_send_notice"><i class="icon-spinner9 spinner position-left hide"></i> Send to customer <i class="icon-arrow-right14 position-right"></i></button>
                        <button type="button" class="btn btn-default custom_close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
