<div class="row">
    <div class="row custom-tab-form-content">
        <div class="col-md-12">
            <div class="panel panel-white border-top-primary">

                <div class="panel-heading custom_info_head">
                    <h6 class="panel-title text-semibold">List Invoices</h6>
                    <div class="heading-elements">
                        <div class="heading-btn">
                            <a href="javascript:void(0)" class="btn btn-primary btn-labeled" id="add_invoice"><b><i class="icon-plus-circle2"></i></b> Add Manual Incoive</a>
                        </div>
                    </div>
                </div>
                <div class="panel-body">     
                    <div id="userMessageDiv"></div>
                    <table class="table responsive nowrap" width="100%" cellspacing="0" id="customer_invoice_dttable">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="10%">Date</th>
                                <th width="18%">Description</th>
                                <th width="8%">Price</th>
                                <th style="display: none;"></th>
                                <th width="12%">Payment Type</th>
                                <th width="10%">CC Last Digit</th>
                                <th width="10%">Card Type</th>
                                <th width="12%">Payment Status</th>
                                <th width="5%">Action(s)</th>
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
<div class="manage_invoice"></div>
<?php $this->load->view('customers/invoice_preview_modal');?>

