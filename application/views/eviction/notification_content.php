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
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label col-md-2">To</label>
            <div class="col-md-8">
                <?php $email = $customer['email']; ?>
                <input type="text" class="form-control" name="email_to" id="email_to" value="<?php echo $email ?>">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label col-sm-2">Subject</label>
            <div class="col-sm-8">
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