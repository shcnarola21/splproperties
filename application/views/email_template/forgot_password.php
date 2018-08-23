<tr>
    <td>
        <p><h2>Hello, <?php echo $first_name.' '.$last_name; ?></h2></p>
    </td>
</tr>
<tr>
    <td style="padding: 0px 0 15px 0;">
        <p>We heard you need a password reset. Click the link below and you'll redirected to a secure site from which you can set a new password.</p>
    </td>
</tr>
<tr>
    <td style="padding:30px;text-align: center">
        <p><a href="<?php echo site_url('admin/reset_password?q='.base64_encode($user_id).'&code='.$verification_code); ?>" class="btn_href">Reset your password</a></p>
    </td>
</tr>
<tr>
    <td>
        <p>If you didn't request a password reset, please ignore this email or <a href="javascript:void(0)">contact support</a> if you have questions.</p>
    </td>
</tr>
<tr><td style="padding:15px 0"><hr></td></tr>
<tr>
    <td style="color:#8e8e8e">
        If you're the trouble with the button above, copy and paste the URL below into your web browser.
        <p><a href="javascript:void(0)" style="font-size:12px;color:#0079c1"><?php echo site_url('admin/reset_password?q='.base64_encode($user_id).'&code='.$verification_code); ?></a></p>    
    </td>
</tr>
