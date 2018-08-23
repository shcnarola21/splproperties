<?php
$address = '';
if (isset($customer_property) && !empty($customer_property)) {
    if (!empty($customer_property['address'])) {
        $address .= $customer_property['address'] . ',&nbsp;&nbsp;';
    }
    if (!empty($customer_property['city'])) {
        $address .= $customer_property['city'] . ',&nbsp;&nbsp;';
    }
    if (!empty($customer_property['state'])) {
        $address .= $customer_property['state'] . ',&nbsp;&nbsp;';
    }
    if (!empty($customer_property['zip_code'])) {
        $address .= $customer_property['zip_code'] . ',&nbsp;&nbsp;';
    }
    if (!empty($customer_property['country'])) {
        $address .= $customer_property['country'];
    }
}
$provider_fname = '';
$provider_lname = '';
if (isset($provider_info['name'])) {
    $name = explode(' ', $provider_info['name']);
    $provider_fname = $name[0];
    if (isset($name[1])) {
        $provider_lname = $name[1];
    }
}
$total_rent_owing = 0;
if (isset($transactions) && !empty($transactions)) {
    foreach ($transactions as $current_transaction) {
        if (isset($current_transaction['log_data']) && !empty($current_transaction['log_data'])) {
            $current_transaction_log = json_decode($current_transaction['log_data'], true);
            $grand_total = floatval(preg_replace('/[,]/', '', $current_transaction_log['grand_total']));
            $total_rent_owing += $grand_total;
        }
    }
}
//
//$data = '1234';
//if (preg_match('/(\d{0,3})(\d{0,3})(\d{0,4})$/', $data, $matches)) {
//    $result = '(' . $matches[1] . ') ' . $matches[2] . ' - ' . $matches[3];
//    pr($result);
//} elseif (preg_match('/(\d{0,3})(\d{0,3})$/', $data, $matches)) {
//    $result = '(' . $matches[1] . ') ' . $matches[2];
//    pr($result);
//} elseif (preg_match('/(\d{0,3})$/', $data, $matches)) {
//    $result = '(' . $matches[1] . ') ';
//    pr($result);
//} else {
//    pr($data);
//}
?>
<div class="panel panel-white border-top-primary">
    <div class="panel-heading">
        <div class="text-right"></div>
        <div class="text-left" style="display: flex;">
            <h5 class="panel-title">Notice For</h5>   
        </div>
    </div>
    <div class="panel-body">   
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-sm-3">Tenant's name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="eviction_to" name="eviction_to" value="<?php echo isset($customer['name']) ? $customer['name'] : '' ?>" title="Tenant's Name"/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-sm-3">Landlord's name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="eviction_from" name="eviction_from" value="<?php echo isset($provider_info['name']) ? $provider_info['name'] : '' ?>" title="Landloard's Name"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-sm-2 address">Address</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" id="eviction_address" rows="2" name="eviction_address" title="Address of the rental unit"><?php echo $address; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-sm-3">Rent Amount</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control total_rent_owing" id="rent_amount" name="rent_amount" title="Amount of rent owing" value="<?php echo isset($transactions) ? $total_rent_owing : '' ?>"/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-sm-3">Eviction Date</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-calendar"></i></span>
                            <input type="text" class="form-control daterange-single eviction_date" id="eviction_date" name="eviction_date" value="<?php echo date("m/d/Y", strtotime(date("m/d/Y") . '+14 days')); ?>" title="Termination Date">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr />
<div class="panel panel-white border-top-primary">
    <div class="panel-heading">
        <div class="text-right"></div>
        <div class="text-left" style="display: flex;">
            <h5 class="panel-title">Total amount of rent claimed</h5>   
        </div>
    </div>
    <div class="panel-body">   
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered table-lg">
                    <thead>
                        <tr class="active">
                            <td colspan="2">
                                <div class="text-center">Rent Period </div> 
                                <div>
                                    <span class="text-left rent_l">From: (dd/mm/yyyy) </span>
                                    <span class="pull-right rent_r">To: (dd/mm/yyyy)</span>
                                </div>
                            </td>
                            <td>Rent Charged $</td>
                            <td>Rent Paid $</td>
                            <td>Rent Owing $</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $transaction_count = 3;
                        if (isset($transactions) && !empty($transactions)) {
                            $transaction_count = count($transactions);
                        }
                        for ($i = 0; $i < $transaction_count; $i++) {
                            if (isset($transactions) && isset($transactions[$i]) && !empty($transactions[$i]['log_data'])) {
                                $current_transaction_log = json_decode($transactions[$i]['log_data'], true);
                                $grand_total = floatval(preg_replace('/[,]/', '', $current_transaction_log['grand_total']));
                            }
                            ?>
                            <tr>
                                <td>
                                    <div class="input-group input-group-custom">
                                        <span class="input-group-addon"><i class="icon-calendar"></i></span><input data-format="yyyy-MM-dd" type="text" class="form-control rent_date" name="from_date[]" value="<?php echo isset($transactions) && isset($transactions[$i]) ? date('m/d/Y', strtotime($transactions[$i]['created'])) : '' ?>">
                                    </div>
                                </td>
                                <td><div class="input-group">
                                        <span class="input-group-addon"><i class="icon-calendar"></i></span><input data-format="yyyy-MM-dd" type="text" class="form-control rent_date" name="to_date[]" value="<?php echo isset($transactions) && isset($transactions[$i]) ? date('m/d/Y', strtotime($transactions[$i]['next_pay_date'])) : '' ?>">
                                    </div>
                                </td>
                                <td><input type="text" class="form-control curreny_format rent_charged" name="rent_charged[]" value="<?php echo isset($grand_total) ? $grand_total : '' ?>"></td>
                                <td><input type="text" class="form-control curreny_format rent_paid" name="rent_paid[]"  placeholder=""></td>
                                <td><input type="text" class="form-control curreny_format rent_owing" name="rent_owing[]" value="<?php echo isset($grand_total) ? $grand_total : '' ?>"></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="4" class="text-right">Total Rent Owing $</td>
                            <td><input type="text" class="form-control curreny_format total_rent_owing" name="total_rent_owing" value="<?php echo isset($transactions) ? $total_rent_owing : ''; ?>"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<hr />
<div class="panel panel-white border-top-primary">
    <div class="panel-heading">
        <div class="text-right"></div>
        <div class="text-left" style="display: flex;">
            <h5 class="panel-title">Information by Landlord</h5>   
        </div>
    </div>
    <div class="panel-body">   
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-sm-3">Signature</label>
                    <div class="col-sm-9">
                        <label class="radio-inline">
                            <input type="radio" name="signature_type" class="styled"  value="landlord" checked="checked" title="Who is signing the notice">
                            Landlord
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-sm-3">First Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="s_fname" name="s_fname" value="<?php echo $provider_fname; ?>" title="First Name of the person who is signing the notice"/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-sm-3">Last Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="s_lname" name="s_lname" value="<?php echo $provider_lname; ?>" title="Last Name of the person who is signing the notice"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-sm-3">Phone</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control phone_format" id="s_phone" name="s_phone" value="<?php echo isset($provider_info['phone']) ? phone_formatter($provider_info['phone']) : '' ?>" title="Phone number of the person who is signing the notice"/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-sm-3">Date</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-calendar"></i></span>
                           <input type="text" class="form-control s_date" id="s_date" name="s_date" value="" title="The Date you sign this form">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-sm-3">Signature</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="s_signature" name="s_signature" value="" title="Sign Your Name"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>