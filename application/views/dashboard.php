<?php
$provider_id = my_provider_id();
?>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ul>
    </div>
</div>
<div class="content dashboard_content">
    <?php if (is_provider()) { ?>
        <div class="row dashboard_layout">
            <div class="col-md-3">
                <div class="panel panel-white border-top-primary">
                    <div class="panel-heading">
                        <h5><?php echo isset($provider_info) ? $provider_info['name'] . "'s " : '' ?>Information</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap">                        
                            <tbody>
                                <tr>                                
                                    <td><span class="label  bg-blue">Customers</span></td>
                                    <td>
                                        <?php echo isset($total_counts['total_active']) ? $total_counts['total_active'] : 0 ?>
                                    </td>
                                </tr>
                                <tr>                                
                                    <td><span class="label  bg-danger">Available Units</span></td>
                                    <td>
                                        <?php echo isset($available_units) ? $available_units : 0 ?>
                                    </td>
                                </tr>
                                <tr>                                
                                    <td><span class="label  bg-success">Pending Invoices</span></td>
                                    <td>
                                        <?php echo isset($pending_invoice) ? $pending_invoice : 0 ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if (is_rental_service_available()) { ?>
        <?php
        if (isset($dashboard_property_unit) && !empty($dashboard_property_unit)) {
            if ((array_key_exists($provider_id, $dashboard_property_unit))) {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="bulding-structure">
                            <?php
                            foreach ($dashboard_property_unit[$provider_id] as $provider => $property) {

                                $property_name = '-';
                                if (isset($properties) && !empty($properties)) {
                                    $property_index = array_search($property['property_id'], array_column($properties, 'id'));

                                    if (!is_bool($property_index)) {
                                        $property_name = $properties[$property_index]['address'];
                                    }
                                }
                                ?>
                                <div class="structure-left">
                                    <div class="panel panel-white border-top-primary">
                                        <div class="panel-heading">
                                            <h5><i class="icon-office position-left"></i> <?php echo $property_name ?></h5>
                                        </div>
                                        <!--<div class="panel-body">-->
                                        <ul class="square-ul">
                                            <?php
                                            foreach ($property['unit_seq'] as $key => $val) {
                                                $multiple_units = explode('|', $val);
                                                $popup_content = 'Unit Vacant';
                                                $property_cls = 'red';
                                                $customer_id = '';
                                                foreach ($multiple_units as $k => $v) {
                                                    $last_element = end($multiple_units);
                                                    $v = trim($v);
                                                    if (!is_bool($property_index)) {
                                                        if (isset($properties[$property_index]['customer_info']) && !empty($properties[$property_index]['customer_info']) && array_key_exists($v, $properties[$property_index]['customer_info'])) {
                                                            $customer_id = $properties[$property_index]['customer_info'][$v]['cid'];
                                                            $rating = $properties[$property_index]['customer_info'][$v]['rating'];
                                                            $rating = !empty($rating) ? $rating : 0;
                                                            $property_cls = 'green';
                                                            $popup_content = '<div><strong>Rating : </strong><input type="hidden"  class="rating" data-readonly value="' . $rating . '" data-filled="icon-star-full2 symbol-filled" data-empty="icon-star-empty3 symbol-empty"/>' . '</div>';
                                                            $popup_content .= '<div><strong>Name : </strong>' . $properties[$property_index]['customer_info'][$v]['name'] . '</div>';
                                                            $popup_content .= '<div><strong>Email : </strong>' . $properties[$property_index]['customer_info'][$v]['email'] . '</div>';
                                                            if ($properties[$property_index]['customer_info'][$v]['phone']) {
                                                                $popup_content .= '<div><strong>Phone : </strong>' . $properties[$property_index]['customer_info'][$v]['phone'] . '</div>';
                                                            } else {
                                                                $popup_content .= '<div><strong>Phone : </strong>-</div>';
                                                            }
                                                            $popup_content .= '<div><strong>Package : </strong>$' . $properties[$property_index]['customer_info'][$v]['package_price'] . '</div>';
                                                            if ($properties[$property_index]['customer_info'][$v]['secondary_check'] == 'no') {
                                                                $popup_content .= '<hr/>';
                                                                $popup_content .= '<div><u><b>Secondary Contact</b></u></div><div><strong>Secondary Name : </strong>' . $properties[$property_index]['customer_info'][$v]['secondary_name'] . '</div>';
                                                                if ($properties[$property_index]['customer_info'][$v]['secondary_phone']) {
                                                                    $popup_content .= '<div><strong>Secondary Phone : </strong>' . $properties[$property_index]['customer_info'][$v]['secondary_phone'] . '</div>';
                                                                } else {
                                                                    $popup_content .= '<div><strong>Secondary Phone : </strong>-</div>';
                                                                }
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <li class="<?php echo (count($multiple_units) > 1) ? 'square-ul-2 ' . (($last_element == $v) ? 'square-ul-last-2' : '') : 'square-ul-1'; ?>">
                                                        <div class="square-li-div">
                                                            <?php
                                                            $redirect_url = '#';
                                                            if (!empty($customer_id)) {
                                                                $redirect_url = base_url() . 'customers/view/' . base64_encode($customer_id);
                                                            }
                                                            ?>
                                                            <h4 class="<?php echo $property_cls; ?>" data-placement="top" data-popup="popover-custom" title="Customer Info" data-trigger="hover" data-content='<?php echo $popup_content ?>'><a  href="<?php echo $redirect_url; ?>"><?php echo 'Unit ' . $v; ?></a></h4>
                                                        </div>
                                                    </li>
                                                    <?php
                                                }
                                            }
                                            ?>                                        
                                        </ul>

                                    </div>            
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
    ?>
    <script type="text/javascript" src="assets/js/custom_pages/dashboard.js"></script>  
<?php $this->load->view('Templates/footer'); ?>
</div>
