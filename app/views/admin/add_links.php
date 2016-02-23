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

        <form action="<?php echo base_url(); ?>save_links" method="post" enctype="multipart/form-data">
                     
            <div class="input-wrap">
                <label for="title">Company Name (arabic)</label>
                <input type="text" id="trans" name="company" value="<?php echo $link->company; ?>" class="form-control">
            </div>
            
            <div class="input-wrap">
                <label for="title">Company Name (english)</label>
                <input type="text" id="english_company" name="english_company" value="<?php echo $link->english_company; ?>" class="form-control">
            </div>

            <div class="input-wrap">
                <label for="title">Contact Name (arabic)</label>
                <input type="text" id="contact" name="contact" value="<?php echo $link->username; ?>" class="form-control">
            </div>

            <div class="input-wrap">
                <label for="title">Contact Name (english)</label>
                <input type="text" id="english_contact" name="english_contact" value="<?php echo $link->english_username; ?>" class="form-control">
            </div>

            <div class="input-wrap">
                <label for="title">Email</label>
                <input type="text" id="email" name="email" value="<?php echo $link->email; ?>" class="form-control">
            </div>

            <div class="input-wrap">
                <label for="title">Location</label>
                <input type="text" id="location" name="location" value="<?php echo $link->location; ?>" class="form-control">
            </div>

            <div class="input-wrap">
                <label for="title">Phone</label>
                <input type="text" id="phone" name="phone" value="<?php echo $link->phone; ?>" class="form-control">
            </div>

            <div class="input-wrap">
                <label for="title">URL</label>
                <input type="text" id="url" name="url" value="<?php echo $link->url; ?>" class="form-control">
            </div>

            <div class="input-wrap">
                <label for="title">Small Image (230*184)</label>
                <input type="file" id="picture" name="picture" class="form-control">
                <?php if(!empty($link->image)){ ?>
                    <img src="<?php echo $this->session->userdata('image'); ?>" width="100" height="100" />
                <?php } ?>
            </div>

            <div class="input-wrap">
                <label for="title">Large Image (600*96)</label>
                <input type="file" id="detail_picture" name="detail_picture" class="form-control">
                <?php if(!empty($link->detail_image)){ ?>
                    <img src="<?php echo $this->session->userdata('image1'); ?>" width="200" height="70" />
                <?php } ?>
            </div>            
                    
        <?php if(!empty($this->uri->segment(2))){ ?>
            <button type="submit" class="btn btn-primary pull-right" name="update">Update</button>
        <?php }else{ ?>
            <button type="submit" class="btn btn-primary pull-right" name="add">Save</button>
        <?php } ?>
        </form>
    </div>
</div>