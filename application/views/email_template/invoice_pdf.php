<div class="container">
    <div class="row pad-top-botm">
        <table class="table" style="width:100%;">
            <thead>
                <tr>
                    <th style="text-align:left;width:25%;">
                        <?php
                        if (isset($templates_info['image']) && !empty($templates_info['image'])) {
                            ?>
                            <img src="<?php // echo base_url();  ?>uploads/templates/<?php echo $templates_info['id']; ?>/<?php echo $templates_info['image']; ?>" style="max-width:200px;" />                              

                            <?php
                        } else {
                            echo "<h3>INVOICE</h3>";
                        }
                        ?>                             
                    </th>
                    <th style="text-align: right;width:70%;">
            <div class="pdf-address" stlye="float:none; width:50%; height:150px;padding-right: 100px;">
                <?php
                if (isset($template_header_info) && !empty($template_header_info)) {
                    echo $template_header_info;
                }
                ?>
            </div>                    
            </th>       
            <?php if (isset($transaction_info['payment_status']) && ($transaction_info['payment_status'] == "paid") || $transaction_info['payment_status'] == "pending" || $transaction_info['payment_status'] == "fail") { ?>
                <th style="vertical-align:top; width:5%">
                    <?php if ($transaction_info['payment_status'] == "paid") { ?>
                        <img src="<?php //echo base_url();                   ?>uploads/invoice_logos/paid.png" />                        
                    <?php } ?>
                    <?php if ($transaction_info['payment_status'] == "fail") { ?>
                        <img src="uploads/invoice_logos/failed.png" />                        
                    <?php } ?>
                    <?php if ($transaction_info['payment_status'] == "pending") { ?>
                        <img src="uploads/invoice_logos/overdue.png" />                 
                    <?php } ?>
                </th>
            <?php } ?>
            </tr>
            </thead>
        </table>
        <hr />
        <div  class="row pad-top-botm client-info">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <h4>  <strong><u>Client Information</u></strong></h4>
                <strong>  <?php echo ucfirst($customer[0]['name']); ?></strong>
                <br />
                <b>Address :</b> 
                <?php
                $address_str = "";
                if (isset($property_info) && !empty($property_info)) {
                    if (isset($property_info['address']) && !empty($property_info['address'])) {
                        $address_str .= $property_info['address'];
                    }
                    if (isset($property_info['city']) && !empty($property_info['city'])) {
                        $address_str .= "," . $property_info['city'];
                    }
                    if (isset($property_info['country']) && !empty($property_info['country'])) {
                        $address_str .= "," . $property_info['country'];
                    }
                    if (isset($property_info['zip_code']) && !empty($property_info['zip_code'])) {
                        $address_str .= " - " . $property_info['zip_code'];
                    }
                } else {
                    if (isset($customer[0]['address']) && !empty($customer[0]['address'])) {
                        $address_str .= $customer[0]['address'];
                    }
                    if (isset($customer[0]['city']) && !empty($customer[0]['city'])) {
                        $address_str .= "," . $customer[0]['city'];
                    }
                    if (isset($customer[0]['country']) && !empty($customer[0]['country'])) {
                        $address_str .= "," . $customer[0]['country'];
                    }
                    if (isset($customer[0]['zip_code']) && !empty($customer[0]['zip_code'])) {
                        $address_str .= " - " . $customer[0]['zip_code'];
                    }
                }

                echo (!empty($address_str)) ? $address_str : ' - ';
                ?>                   
                <br />
                <b>Call :</b> <?php echo (isset($customer[0]['phone']) && !empty($customer[0]['phone'])) ? '+' . $customer[0]['phone'] : ' - '; ?>
                <br />
                <b>E-mail :</b> <?php echo $customer[0]['email']; ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                &nbsp;
            </div>
        </div>
        <h4>  <strong><u>Package Information</u></strong></h4>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover border-css-01" width="100%" style="border:1px solid #000;">
                        <thead>
                            <tr>                           
                                <th style="border-bottom: 1px solid #000;">Description</th>
                                <th style="border-bottom: 1px solid #000;">Price</th>                            
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($preview)) { ?>
                                <tr> 
                                    <td colspan="2">Invoice Detail</td>
                                </tr>
                                <?php
                            } else {
                                $log_data = $transaction_info['log_data'];
                                $transaction = json_decode($log_data, true);
                                ?>
                                <tr>
                                    <?php
                                    if ($transaction['type'] == "renewal_customer") {
                                        ?>                                   
                                        <?php if (isset($transaction['packages'])) { ?>
                                        <tr>                            
                                            <td style="padding:10px 20px;"> <?php echo isset($transaction['packages_label']) ? $transaction['packages_label'] : ''; ?> <?php echo isset($transaction['packages']['name']) ? $transaction['packages']['name'] : ''; ?> </td>
                                            <td align="right" style="padding:10px 20px;"> $<?php echo isset($transaction['packages']['total_price']) ? $transaction['packages']['total_price'] : '0.00'; ?></td>                            
                                        </tr>   
                                    <?php } ?>                               
                                    <?php if (isset($transaction['addons']) && (!empty($transaction['addons']))) { ?>
                                        <?php foreach ($transaction['addons'] as $addon) { ?>
                                            <tr>                            
                                                <td style="padding:10px 20px;"> <?php echo isset($transaction['addon_label']) ? $transaction['addon_label'] : ''; ?><?php echo isset($addon['name']) ? $addon['name'] : ''; ?>
                                                <td align="right" style="padding:10px 20px;"> $<?php echo isset($addon['total_price']) ? $addon['total_price'] : '0.00'; ?></td>                            
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>                                  
                                    <?php
                                    if (isset($transaction['customer_credit_used'])) {
                                        $customer_credit_available_str = isset($transaction['customer_credit_available']) ? '$' . $transaction['customer_credit_available'] : '0.00';
                                        $credit_str = "Credit Used( Available : $" . $customer_credit_available_str . ')';
                                        ?>
                                        <tr>
                                            <td style="padding:10px 20px;"> 
                                                <?php
                                                if (isset($transaction['lb_customer_credit'])) {
                                                    echo $transaction['lb_customer_credit'];
                                                } else {
                                                    echo $credit_str;
                                                }
                                                ?>
                                            </td>
                                            <td align="right" style="padding:10px 20px;"> <?php echo isset($transaction['customer_credit_used']) ? '$' . $transaction['customer_credit_used'] : '0.00'; ?> </td>                            
                                        </tr>
                                    <?php } ?>
                                <?php }else{ ?>                                   
                                        <tr>                            
                                            <td style="padding:10px 20px;"> <?php echo isset($transaction_info['detail']) ? $transaction_info['detail'] : ''; ?> </td>
                                            <td align="right" style="padding:10px 20px;"> $<?php echo isset($transaction['price']) ? $transaction['price'] : ''; ?> </td>                            
                                        </tr>                                       
                                <?php } ?>                              
                                </tr>
                            <?php } ?>
                        <tbody>
                    </table>
                </div>

                <div style="text-align: right;"  class="ttl-amts">
                    <h5>  Total Amount : $<?php echo isset($transaction['total_price']) ? $transaction['total_price'] : 0; ?> </h5>
                </div>

                <div  style="text-align: right;" class="ttl-amts">
                    <?php if (isset($transaction['tax_included']) && $transaction['tax_included']) { ?>
                        <h5>  Tax : Included (<?php echo isset($transaction['tax_per']) ? $transaction['tax_per'] : 0; ?>  %) </h5>
                    <?php } else { ?>
                        <h5>  Tax : $<?php echo isset($transaction['tax']) ? $transaction['tax'] : 0; ?> (<?php echo isset($transaction['tax_per']) ? $transaction['tax_per'] : 0; ?>  %) </h5>
                    <?php } ?>
                </div>

                <div  style="text-align: right;" class="ttl-amts">
                    <h4> <strong>BILL AMOUNT  : $<?php echo isset($transaction['grand_total']) ? $transaction['grand_total'] : 0; ?></strong> </h4>
                </div>
            </div>
        </div>       
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <?php
                if (isset($footer_box) && !empty($footer_box)) {
                    echo $footer_box;
                }
                ?>
            </div>
        </div>
    </div>
