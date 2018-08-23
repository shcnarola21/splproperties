<?php
$customer_credit = number_format($customer_credit, 2);
?>
<div class="row">
    <div class="manage_credits"></div>
    <div class="row custom-tab-form-content">
        <div class="col-md-12">
            <div class="panel panel-white border-top-primary">

                <div class="panel-heading custom_info_head">
                    <h6 class="panel-title text-semibold">List Credits</h6>
                    <div class="heading-elements">
                        <div class="heading-btn">
                            <span class="text-semibold user_credit">
                                Credit: <span class="label label-primary">$ <label class="customer_total_credit"><?php echo $customer_credit ?></label></span>
                            </span>
                            <a href="javascript:void(0)" class="btn btn-primary btn-labeled" id="credit_add"><b><i class="icon-plus-circle2"></i></b> Add Credit</a>
                        </div>
                    </div>
                </div>
                <div class="panel-body">   
                    <table class="table responsive nowrap" width="100%" cellspacing="0" id="customer_credits_dttable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>UserName</th>
                                <th>Type</th>
                                <th style="display: none;"></th>
                                <th>Credit Added On</th>
                                <th>Description</th>
                                <th>Action(s)</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

