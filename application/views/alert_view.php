<?php if ($this->session->flashdata('success')) { ?>
    <div class="alert alert-success alert-styled-left alert-bordered hide-msg flashmsg">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span>
            <span class="sr-only">Close</span>
        </button>
        <?php echo $this->session->flashdata('success') ?>
    </div>    
<?php } ?>
<?php if ($this->session->flashdata('error')) { ?>   
    <div class="alert alert-danger alert-styled-left alert-bordered hide-msg flashmsg">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span>
            <span class="sr-only">Close</span>
        </button>
        <?php echo $this->session->flashdata('error') ?>
    </div>
<?php } ?>