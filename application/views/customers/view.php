<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.date.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/tags/tagsinput.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/tags/tokenfield.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/inputs/formatter.min.js"></script>
<style>
    #profile_update_account .form-group {
        padding: 12px;
    }
    .daterangepicker.dropdown-menu {
        z-index: 3000;
    }
</style>
<?php
$credit = '';
if (isset($customer_credit) && !empty($customer_credit)) {
    $credit = '<span class="label label-primary"> ($ <label class="customer_total_credit">' . $customer_credit . '</label> )</span>';
}
?>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>customers"><i class="icon-users position-left"></i> Customers</a></li>
            <li class="active"><?php echo isset($customer['name']) ? $customer['name'] : 'Customer information' ?></li>
        </ul>
    </div>
</div>
<!-- Content area -->
<div class="content">

    <!-- Main charts -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat">
                <div class="panel-body">
                    <div class="tabbable">
                        <ul class="nav nav-tabs nav-tabs-highlight">
                            <li class="active text-semibold" onclick="get_customer_tab_detail('AccountInfo', 'account_tab')" ><a href="#account_tab" data-toggle="tab">Account Info</a></li>                         
                            <li class="text-semibold" onclick="get_customer_tab_detail('Packages', 'package_tab')"><a href="#package_tab" data-toggle="tab">Packages</a></li>                             
                            <li class="text-semibold" onclick="get_customer_tab_detail('Invoices', 'invoice_tab')"><a href="#invoice_tab" data-toggle="tab">Invoices</a></li>                             
                            <?php if ((isset($provider_info) && $provider_info['payment_system'] == '1') && (isset($customer) && $customer['payment_type'] == 'Credit')) { ?>
                                <li class="text-semibold" onclick="get_customer_tab_detail('CardInfo', 'cardinfo_tab')"><a href="#cardinfo_tab" data-toggle="tab">Card Info</a></li>                         
                            <?php } ?>
                            <li class="text-semibold" onclick="get_customer_tab_detail('Notes', 'notes_tab')"><a href="#notes_tab" data-toggle="tab">Notes</a></li>                         
                            <li class="text-semibold" onclick="get_customer_tab_detail('Credit', 'credit_tab')"><a href="#credit_tab" class="customer_last_month_credit" data-toggle="tab"><?php echo (!$customer_lastmonth_credit) ? '<i class="text-danger icon-info22"></i> ' : '' ?>Customer Credit(s) <?php echo $credit ?></a></li>                             
                            <?php if (is_rental_service_available()) { ?>
                                <li class="text-semibold" onclick="get_customer_tab_detail('Eviction', 'eviction_tab')"><a href="#eviction_tab" data-toggle="tab">Notification(s)</a></li>                         
                                <li class="text-semibold" onclick="get_customer_tab_detail('Contracts', 'contract_tab')"><a href="#contract_tab" data-toggle="tab">Contracts</a></li>                             
                            <?php } ?>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active has-padding" id="account_tab">
                                <?php $this->load->view('customers/profile_tab'); ?>
                            </div>
                            <div class="tab-pane has-padding" id="package_tab">
                                <?php $this->load->view('customers/packages_tab'); ?>
                            </div>
                            <div class="tab-pane has-padding" id="invoice_tab">
                                <?php $this->load->view('customers/invoice_tab'); ?>
                            </div>
                            <?php if (isset($customer) && $customer['payment_type'] == 'Credit') { ?>
                                <div class="tab-pane has-padding" id="cardinfo_tab">
                                    <?php $this->load->view('customers/cardinfo_tab'); ?>
                                </div>
                            <?php } ?>
                            <div class="tab-pane has-padding" id="notes_tab">
                                <?php $this->load->view('customers/notes_tab'); ?>
                            </div>
                            <div class="tab-pane has-padding" id="credit_tab">
                                <?php $this->load->view('customers/customer_credit_tab'); ?>
                            </div>
                            <?php if (is_rental_service_available()) { ?>
                                <div class="tab-pane has-padding" id="eviction_tab">
                                    <?php $this->load->view('customers/eviction_tab'); ?>
                                </div>
                                <div class="tab-pane has-padding" id="contract_tab">
                                    <?php $this->load->view('customers/contract_tab'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">

        var pay_customer = "<?php echo $customer['cid']; ?>";
    </script>
    <script type="text/javascript" src="assets/js/custom_pages/customer_view.js"></script>
    <?php $this->load->view('Templates/footer'); ?>
</div>
