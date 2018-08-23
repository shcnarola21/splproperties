<div class="modal fade invoice_preview" style="display:none" data-backdrop="static"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Invoice PDF</h4>
            </div>
            <div class="modal-body" id="modal_container">
                <div class="pdf_message_div" style="display:none;"></div>
                <div style="width:100px;margin:auto;display:none" class="pdf_loading_timer" >
                    <img alt="loading img"  src="<?php echo base_url(); ?>assets/images/loading_timer.gif" >
                </div>
                <input type="hidden" id="pdf_name" name="pdf_name" value="" />
                <input type="hidden" id="invoice_id" name="invoice_id" value="" />

                <div class="view_pdf_section" style="display:none">
                    <iframe id="pdf_iframe" name="pdf_iframe" style="height: 500px;width: 100%;" src="" frameborder="0"></iframe>
                </div>
            </div>
            <div class="send_pdf_section modal-footer">
                <div class="pdf_actions" style="display:none;">                                
                    <a href="" target="_blank" class="btn btn-danger print_pdf"> <i class="icon-printer2"></i> Print</a>
                    <a href="" class="btn btn-warning download_pdf"> <i class="icon-file-download2"></i> Download</a>
                    <button value="button" class="btn btn-primary send_pdf_to_customer"><i class="icon-spinner9 spinner position-left hide"></i> Send To Customer <i class="icon-arrow-right14 position-right"></i></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                    
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->