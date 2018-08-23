<?php
$show_header_footer = false;
$show_html_content = true;
if (isset($template['id']) && $template['type'] == 'PDF') {
    $show_header_footer = true;
    $show_html_content = false;
}
?>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/'); ?>"><i class="icon-list2 position-left"></i> Edit Template</a></li>            
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>
            <div class="row">               
                <div class="col-md-9">
                    <div class="panel panel-white border-top-primary">
                        <div class="panel-heading">
                            <div class="text-right"></div>
                            <div class="text-left" style="display: flex;">
                                <h5 class="panel-title">Template : <?php echo isset($template['id']) ? str_replace('_', " ", $template['name']) : ""; ?></h5> 
                            </div>
                        </div>
                        <form class="form-horizontal" method="post" id="template_frm">
                            <div class="panel-body">
                                <div class="userMessageDiv"></div>
                                <input type="hidden"  name="template_id" value="<?php echo isset($template['id']) ? base64_encode($template['id']) : ""; ?>">
                                <input type="hidden"  name="type" value="<?php echo isset($template['id']) ? $template['type'] : ""; ?>">
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">From Email Address:</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control" name="from_email" placeholder="Enter Email Address" value="<?php echo isset($template['from_email']) ? $template['from_email'] : ""; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Subject:</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control" name="subject" placeholder="Enter Subject" value="<?php echo isset($template['subject']) ? $template['subject'] : ""; ?>">
                                    </div>
                                </div>
                                <?php
                                if ($show_header_footer) {
                                    if (!empty($template['image'])) {
                                        ?>
                                        <div class="form-group <?php echo 'f_' . $template['id'] ?>">
                                            <label class="col-lg-2 control-label">&nbsp;</label>
                                            <div class="col-lg-3 ">
                                                <div class="thumbnail">
                                                    <div class="preview_img">
                                                        <div class="img-wrapper">
                                                            <div class="thumb">
                                                                <?php
                                                                $path = $this->config->item('uploads_path') . '/templates/' . $template['id'];
                                                                $img_url = base_url() . 'uploads' . '/templates/' . $template['id'] . '/' . $template['image'];
                                                                if (!file_exists($path)) {
                                                                    $img_url = base_url() . 'uploads/no_image.jpg';
                                                                }
                                                                ?>
                                                                <img class="loading img_view_wrapper" src="<?php echo $img_url; ?>"  id="img_<?php echo $template['id']; ?>" style="" alt="">
                                                                <div class="caption-overflow">                                                   
                                                                    <span>
                                                                        <a href="<?php echo $img_url; ?>" data-popup="lightbox" rel="gallery" class="btn border-white text-white btn-flat btn-icon btn-rounded"><i class="icon-zoomin3"></i></a>                                                                
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="caption">
                                                                <h6 class="no-margin vhide" >                                                            
                                                                    <a href="#" class="text-default">&nbsp;</a>     
                                                                    <a href="javascript:void(0)"></a>
                                                                    <a href="#" class="text-muted delete_ti pull-right" id="<?php echo $template['id']; ?>">
                                                                        <i class="icon-bin" title="Remove File"></i>
                                                                    </a>
                                                                </h6>
                                                            </div>
                                                        </div> 
                                                    </div> 
                                                </div> 
                                            </div> 

                                        </div>
                                    <?php } ?>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Image:</label>
                                        <div class="col-lg-4">
                                            <input type="file" name="image" class="form-control file_input" name="image" />                                            
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Header Box:</label>
                                        <div class="col-lg-9">                                        
                                            <textarea name="header_box" id="ckeditor_header_box" rows="4" cols="4"><?php echo isset($template['header_box']) ? $template['header_box'] : ""; ?></textarea>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($show_html_content) { ?>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Html e-mail:</label>
                                        <div class="col-lg-9">                                        
                                            <textarea name="html_content" id="ckeditor_html_content"  rows="4" cols="4"><?php echo isset($template['html_content']) ? $template['html_content'] : ""; ?></textarea>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($show_header_footer) { ?>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label">Footer:</label>
                                        <div class="col-lg-9">                                        
                                            <textarea name="footer_box" id="ckeditor_footer_box"  rows="4" cols="4"><?php echo isset($template['footer_box']) ? $template['footer_box'] : ""; ?></textarea>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="text-right frm-action">                                    
                                    <button type="submit" class="btn bg-teal-400" id="btn_edit_template"><i class="icon-spinner9 spinner position-left hide"></i> Submit <i class="icon-arrow-right14 position-right"></i></button>                                
                                    <button type="reset" class="btn btn-info"> Reset <i class="icon-reset position-right"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>                      
                <div class="col-md-3">
                    <div class="panel panel-white border-top-primary">
                        <div class="panel-heading">
                            <div class="text-right"></div>
                            <div class="text-left" style="display: flex;">
                                <h5 class="panel-title">Template Variable</h5> 
                            </div>
                        </div>
                        <form class="form-horizontal" method="post" id="template_frm">
                            <div class="panel-body">        
                                <table class="table">
                                    <tr>
                                        <td>
                                            <label>Customer ID - ||customer_id||</label>                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Customer Name - ||customer_name||</label>
                                        </td>
                                    </tr>
                                    <?php if (isset($template['id']) && $template['type'] == 'PDF') { ?>
                                        <tr>
                                            <td>
                                                <label>Invoice Date - ||invoice_created_date||</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>Invoice Number - ||invoice_number||</label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php
                                    if (isset($template['id']) && $template['type'] == 'Package') { ?>
                                        <tr>
                                            <td>
                                                <label>Package Detail - ||package_detail||</label>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>                      
            </div>
        </div>
    </div>
    <div class="modal_div"></div>
    <?php $this->load->view('Templates/footer'); ?>
</div>
<script type="text/javascript" src="assets/js/custom_pages/templates.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        if ($(document).find('#ckeditor_header_box').length > 0) {
            CKEDITOR.replace('ckeditor_header_box', {
                height: '400px',
                extraPlugins: 'forms'
            });
        }

        if ($(document).find('#ckeditor_footer_box').length > 0) {
            CKEDITOR.replace('ckeditor_footer_box', {
                height: '400px',
                extraPlugins: 'forms'
            });
        }
        if ($(document).find('#ckeditor_html_content').length > 0) {
            CKEDITOR.replace('ckeditor_html_content', {
                height: '400px',
                extraPlugins: 'forms'
            });
        }
        $(".file_input").uniform({
            fileButtonClass: 'action btn bg-primary'
        });

    });
</script>
