<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/notes'); ?>"><i class="icon-clipboard5 position-left"></i> Notes</a></li>            
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="manage_notes"></div>
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-white border-top-primary">
                        <div class="panel-heading">
                            <div class="text-right"></div>
                            <div class="text-left" style="display: flex;">
                                <h5 class="panel-title">List Notes</h5>
                                <a href="javascript:void(0)" class="btn btn-primary btn-labeled text-right" style="margin-left: auto;" id="p_note_add"><b><i class="icon-plus-circle2"></i></b> Add Note</a>
                            </div>
                        </div>
                        <div class="panel-body">     
                            <div id="userMessageDiv"></div>
                            <table class="table responsive nowrap" width="100%" cellspacing="0" id="notes_dttable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer Name</th>
                                        <th>Created</th>
                                        <th>Note</th>
                                        <th>Added by</th>
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
    </div>
    <script type="text/javascript" src="assets/js/custom_pages/notes.js"></script>  
    <?php $this->load->view('Templates/footer'); ?>
</div>