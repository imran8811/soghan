<style>
    .post_link{
        float: right;
        background: #e7e7e7;
        color: #a39e9e;
        padding: 5px 6px 6px 5px;
        margin-top: 5px;
    }
    .post_link:hover{        
        background: #e2e2e2;
        cursor: pointer;
    }
</style>
<div id="main">
    <div class="login-form">
        <h4 style="color: red; width: 100%;">
            <?php
            echo validation_errors();
            echo $this->session->userdata('msg');
            $this->session->unset_userdata('msg');
            ?>
        </h4>

        <form action="<?php echo base_url(); ?>save_vendor" method="post" enctype="multipart/form-data">
                       
        <div id="isbn_data"></div>

            <div class="input-wrap">
                <label for="title">Vendor Title</label>
                <input type="text" id="type" name="type" value="<?php echo $vendor->vendor_type; ?>" class="form-control">
            </div>

            <div class="input-wrap">
                <label for="title">Logo (Mobile)</label>
                <input type="file" id="mobile_logo" name="mobile_logo" class="form-control">
                <?php if(!empty($vendor->vendor_mobile_image)){ ?>
                    <img src="<?php echo $this->session->userdata('image'); ?>" width="50" height="50" />
                <?php } ?>
            </div>

            <div class="input-wrap">
                <label for="title">Logo (Web)</label>
                <input type="file" id="web_logo" name="web_logo" class="form-control">
                <?php if(!empty($vendor->vendor_image)){ ?>
                    <img src="<?php echo $this->session->userdata('image1'); ?>" width="50" height="50" />
                <?php } ?>
            </div>
            
        <?php if($this->uri->segment(3)){ ?>
            <button type="submit" class="btn btn-primary pull-right" name="update">Update</button>
        <?php }else{ ?>
            <button type="submit" class="btn btn-primary pull-right" name="add">Save</button>
        <?php } ?>
        </form>
    </div>
</div>