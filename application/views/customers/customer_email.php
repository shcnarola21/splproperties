<?php
$states = customer_states();
$is_rental = is_rental_service_available();
$show_input_address = 'display:block';
if ($is_rental) {
    $show_input_address = 'display:none';
}
?>
<div id="email_customer_modal" class="modal fade custom_email_modal" data-backdrop="static">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close custom_close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="icon-mail5 position-left"></i> Send Email</h5>
            </div>
            <form class="form-horizontal" method="post" id="customer_send_email_frm">
                <div class="modal-body edit_customer_wrapper">
                    <div class="userMessageDiv"></div>
                    <input type="hidden" name="customers_id" value="<?php echo $customer['cid']; ?>">
                    <?php if ($is_rental) { ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Canned Response(s)</label>
                                    <div class="col-sm-4">
                                        <select name="canned_response_id" id="canned_response_id" class="select">
                                            <option value="">Select Canned Response</option>
                                            <?php
                                            foreach ($canned_responses as $value) {
                                                ?>
                                                <option value="<?php echo base64_encode($value['id']); ?>"><?php echo $value['title'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-2">To</label>
                                <div class="col-md-10">
                                    <?php
                                    $email = $customer['email'];
                                    if (!empty($customer['additional_email'])) {
                                        $email .= ',' . $customer['additional_email'];
                                    }
                                    ?>
                                    <input type="text" class="form-control tokenfield" name="email_to" id="email_to" value="<?php echo $email ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Subject</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="subject" id="subject" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Message</label>
                                <div class="col-sm-10">
                                    <textarea name="message" id="message" class="message" rows="4" cols="4"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="form-actions">
                        <button value="submit" class="btn btn-primary btn_send_email"><i class="icon-spinner9 spinner position-left hide"></i> Send to customer <i class="icon-arrow-right14 position-right"></i></button>
                        <button type="button" class="btn btn-default custom_close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
