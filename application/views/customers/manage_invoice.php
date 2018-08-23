<div id="modal_manage_invoice" class="modal fade" data-backdrop="static">
    <?php
    $log_data = array();
    if (isset($invoice_info['log_data']) && !empty($invoice_info['log_data'])) {
        $log_data = json_decode($invoice_info['log_data'], true);
    }
    ?>
    <?php
    $modal_cls = '';
    if ($invoice_info['type'] == 'cron') {
        $modal_cls = 'modal-lg';
    }
    ?>
    <div class="modal-dialog <?php echo $modal_cls; ?>">       
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close custom_close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"><i class="<?php echo isset($invoice_info['id']) ? "icon-pencil5" : "icon-plus-circle2"; ?> position-left"></i><?php echo isset($invoice_info['id']) ? "Edit" : "Add"; ?> Invoice <?php echo isset($invoice_info['id']) ? '#' . $invoice_info['id'] : '' ?></h5>
            </div>
            <form class="form-horizontal" method="post" id="manage_invoice_frm">
                <input type="hidden" name="invoice" id="invoice" value="<?php echo isset($invoice_info['id']) ? base64_encode($invoice_info['id']) : '' ?>">
                <?php
                if ($invoice_info['type'] == 'cron') {
                    ?>
                    <div class="modal-body edit_customer_wrapper">
                        <div class="userMessageDiv"></div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Payment Status</label>
                                    <div class="col-sm-8">
                                        <?php
                                        $payment_status_arr = array('paid', 'fail', 'pending', 'refunded', 'canceled');
                                        ?>
                                        <select name="payment_status" id="payment_status" class="select">
                                            <option value="">Select Type</option>
                                            <?php
                                            foreach ($payment_status_arr as $status) {
                                                $selected = '';
                                                if (isset($invoice_info['payment_status']) && $invoice_info['payment_status'] == $status) {
                                                    $selected = 'selected="selected"';
                                                }
                                                ?>
                                                <option value="<?php echo $status; ?>" <?php echo $selected; ?>><?php echo ucfirst($status); ?></option>
                                                <?php
                                            }
                                            ?>                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-3">Invoice Date</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="icon-calendar"></i></span>
                                            <input data-format="yyyy-MM-dd HH:MM:SS" autocomplete="off" type="text" class="form-control daterange-single invoice_date" name="invoice_date" value="<?php echo date('m/d/Y h:i a', strtotime($invoice_info['created'])) ?>" title="Date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Description</label>
                                    <div class="col-sm-8">
                                        <textarea rows="3" cols="100" class="form-control" name="detail"><?php echo (isset($invoice_info['detail'])) ? $invoice_info['detail'] : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>                           
                        </div>                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h5 class="display-block text-semibold">Package Information</h5>     
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered table-lg">
                                    <thead>
                                        <tr class="active">
                                            <td class="text-semibold" style="width:50%">Description</td>
                                            <td class="text-semibold">Term</td>
                                            <td class="text-semibold">Price</td>
                                            <td class="text-semibold">Total Price</td>                                                
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?php
                                                $package_desc = "";
                                                if (!empty($log_data) && isset($log_data['packages']['name'])) {
                                                    $package_desc = $log_data['packages_label'] . " " . $log_data['packages']['name'];
                                                }
                                                ?>
                                                <input type="text" name="package_name" class="form-control" value="<?php echo $package_desc; ?>">
                                            </td>
                                            <?php
                                            $package_term = "";
                                            if (!empty($log_data) && isset($log_data['packages']['term'])) {
                                                $package_term = $log_data['packages']['term'];
                                            }
                                            ?>
                                            <td class="text-center">
                                                <?php echo $package_term; ?>
                                            </td>
                                            <?php
                                            $package_price = 0;
                                            if (!empty($log_data) && isset($log_data['packages']['price'])) {
                                                $package_price = $log_data['packages']['price'];
                                            }
                                            ?>
                                            <td>
                                                <input type="text" name="package_price" id='package_price' class="form-control package_price" data-term='<?php echo $package_term; ?>'  value="<?php echo $package_price; ?>">
                                            </td>                                                
                                            <?php
                                            $package_total_price = 0;
                                            if (!empty($log_data) && isset($log_data['packages']['total_price'])) {
                                                $package_total_price = $log_data['packages']['total_price'];
                                            }
                                            ?>
                                            <td>
                                                <input type="text" name="package_total_price" id='package_total_price' class="form-control cal_price" value="<?php echo $package_total_price; ?>">
                                            </td>                                                
                                        </tr> 
                                        <?php
                                        if (isset($log_data['addons']) && !empty($log_data['addons'])) {
                                            foreach ($log_data['addons'] as $k => $a) {
                                                ?>
                                                <tr>
                                                    <?php
                                                    $addon_name = $log_data['addon_label'] . " " . $a['name'];
                                                    ?>
                                                    <td>
                                                        <input type="text" name="addon[<?php echo $k; ?>][addon_name]" class="form-control" value="<?php echo $addon_name; ?>">
                                                    </td>
                                                    <?php
                                                    $addon_term = $a['term'];
                                                    ?>
                                                    <td class="text-center">
                                                        <?php echo $addon_term; ?>
                                                    </td>    
                                                    <?php
                                                    $addon_price = $a['price'];
                                                    ?>
                                                    <td>
                                                        <input type="text" class="form-control addon_ch" data-term='<?php echo $a['term']; ?>' id='<?php echo $a['id']; ?>' name="addon[<?php echo $k; ?>][addon_price]" value="<?php echo $addon_price; ?>">                                                       
                                                    </td>
                                                    <?php
                                                    $addon_total_price = $a['total_price'];
                                                    ?>
                                                    <td>
                                                        <input type="text" name="addon[<?php echo $k; ?>][total_price]" id='addon_total_<?php echo $a['id']; ?>'  class="form-control cal_price" value="<?php echo $addon_total_price; ?>">
                                                    </td>                                                
                                                </tr>   
                                                <?php
                                            }
                                        }
                                        ?>
                                        <?php
                                        if (!empty($log_data) && isset($log_data['lb_customer_credit'])) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php
                                                    $credit = "";
                                                    if (!empty($log_data) && isset($log_data['lb_customer_credit'])) {
                                                        $credit = $log_data['lb_customer_credit'];
                                                    }
                                                    ?>
                                                    <input type="text" name='lb_customer_credit' class="form-control" value="<?php echo $credit; ?>">
                                                </td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>                                            
                                                <?php
                                                $customer_credit_used = 0;
                                                if (!empty($log_data) && isset($log_data['customer_credit_used'])) {
                                                    $customer_credit_used = $log_data['customer_credit_used'];
                                                }
                                                ?>
                                                <td>
                                                    <input type="text" name='customer_credit_used' class="form-control cal_price" readonly value="<?php echo $customer_credit_used; ?>">
                                                </td>                                                
                                            </tr> 
                                        <?php } ?>

                                        <tr>
                                            <td colspan="3" class="text-right">Total Amount</td>
                                            <?php
                                            $total_price = 0;
                                            if (!empty($log_data) && isset($log_data['total_price'])) {
                                                $total_price = trim(str_replace(',', '', $log_data['total_price']));
                                            }
                                            ?>
                                            <td><input type="text" name="total_price" id='total_price' readonly class="form-control" value="<?php echo $total_price; ?>"></td>
                                        </tr>
                                        <tr>
                                            <?php
                                            $tax = 0;
                                            $tax_per = 0;
                                            if (!empty($log_data) && isset($log_data['tax'])) {
                                                $tax = $log_data['tax'];
                                            }
                                            if (!empty($log_data) && isset($log_data['tax_per'])) {
                                                $tax_per = $log_data['tax_per'];
                                            }
                                            ?>
                                            <td colspan="3" class="text-right">Tax (<?php echo $tax_per; ?>%)</td>
                                            <td>
                                                <input type="hidden" id='tax_val' value="<?php echo $tax_per; ?>">
                                                <input type="text" name="tax" id='tax_amt' class="col-sm-3 form-control" readonly value="<?php echo $tax; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-right">BILL AMOUNT</td>
                                            <?php
                                            $grand_total = 0;
                                            if (!empty($log_data) && isset($log_data['grand_total'])) {
                                                $grand_total = trim(str_replace(',', '', $log_data['grand_total']));
                                            }
                                            ?>
                                            <td><input type="text" id='grand_total' name="grand_total" readonly class="form-control" value="<?php echo $grand_total; ?>"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>                          
                    </div>            
                    <?php
                } else {
                    $state = isset($customer_property['state']) ? $customer_property['state'] : $customer_info['state'];
                    $country = isset($customer_property['country']) ? $customer_property['country'] : $customer_info['country'];
                    ?>
                    <input type="hidden" name="invoice_for" value="<?php echo isset($invoice_for) ? $invoice_for : '' ?>">
                    <input type="hidden" id="calculated_final_price" name="calculated_final_price" value="<?php echo!empty($log_data) && isset($log_data['grand_total']) ? $log_data['grand_total'] : 0 ?>">
                    <input type="hidden" id="calculated_tax" name="calculated_tax" value="<?php echo!empty($log_data) && isset($log_data['tax']) ? $log_data['tax'] : 0 ?>">
                    <input type="hidden" id="c_country" name="c_country" value="<?php echo $country; ?>">
                    <input type="hidden" id="c_state" name="c_state" value="<?php echo $state ?>">
                    <input type="hidden" id="m_tax_per" name="m_tax_per" value="<?php echo!empty($log_data) && isset($log_data['tax_per']) ? $log_data['tax_per'] : 0 ?>" />
                    <div class="modal-body edit_customer_wrapper">
                        <div class="userMessageDiv"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Price</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="icon-coin-dollar"></i></span>
                                            <input type="text" class="form-control" id="c_price" name="c_price" value="<?php echo!empty($log_data) && isset($log_data['price']) ? $log_data['price'] : 0 ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="description" rows="2" id="description" ><?php echo isset($invoice_info['detail']) ? $invoice_info['detail'] : '' ?></textarea>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Invoice Date</label>
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="icon-calendar"></i></span>
                                            <input autocomplete="off" type="text" class="form-control daterange-single invoice_date" name="invoice_date" value="<?php echo (isset($invoice_info['created'])) ? date('m/d/Y h:i a', strtotime($invoice_info['created'])) : date('m/d/Y h:i a'); ?>" title="Date">
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <?php 
                        if(isset($invoice_info['payment_status'])){
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Payment Status</label>
                                    <div class="col-sm-10">
                                        <?php
                                        $payment_status_arr = array('paid', 'fail', 'pending', 'refunded', 'canceled');
                                        ?>
                                        <select name="payment_status" id="payment_status" class="select">
                                            <option value="">Select Type</option>
                                            <?php
                                            foreach ($payment_status_arr as $status) {
                                                $selected = '';
                                                if (isset($invoice_info['payment_status']) && $invoice_info['payment_status'] == $status) {
                                                    $selected = 'selected="selected"';
                                                }
                                                ?>
                                                <option value="<?php echo $status; ?>" <?php echo $selected; ?>><?php echo ucfirst($status); ?></option>
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
                                    <label class="control-label col-sm-2">Include Tax</label>
                                    <div class="col-sm-9">
                                        <?php
                                        $checked = '';
                                        if (!empty($log_data) && isset($log_data['tax_included']) && $log_data['tax_included'] == "1") {
                                            $checked = 'checked=checked';
                                        }
                                        ?>
                                        <input type="checkbox" id="c_include_tax" <?php echo $checked; ?> name="include_tax" value="1" class="styled"/>      
                                    </div>
                                </div>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-2">Total Price</label>
                                    <div class="col-sm-10">
                                        <span id="c_invoice_total_price"><?php echo!empty($log_data) && isset($log_data['total_price']) ? $log_data['total_price'] : 0 ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-2">Tax</label>
                                    <div class="col-sm-10">
                                        <span id="c_invoice_tax"><?php echo!empty($log_data) && isset($log_data['tax']) ? $log_data['tax'] : 0 ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-2">Final Price</label>
                                    <div class="col-sm-10">
                                        <span id="c_invoice_fprice"><?php echo!empty($log_data) && isset($log_data['grand_total']) ? $log_data['grand_total'] : 0 ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="modal-footer">
                    <div class="form-actions">
                        <button value="submit" class="btn btn-primary btn_invoice"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>
                        <button type="button" class="btn btn-default custom_close" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
