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
            echo $this->session->userdata('error');
            $this->session->unset_userdata('error');
            ?>
        </h4>

        <form action="<?php echo base_url(); ?>save_advert" method="post" enctype="multipart/form-data">
        
            <div class="input-wrap">
                <label for="title">Ad Image (size: 750/240)</label>
                <img src="<?php echo $ad->ad_image; ?>" width="300" height="100" />
                <input type="hidden" id="old_picture" name="old_picture" value="<?php echo $ad->ad_image; ?>">
                <input type="file" id="picture" name="picture" class="form-control" style="padding-bottom: 40px;"><br>
                <label for="url">URL</label>
                <input type="text" id="url" name="url" value="<?php echo $ad->url; ?>"><br><br>
                <label for="sort">Sort</label>
                <input type="text" id="sort" name="sort" value="<?php echo $ad->sort; ?>">
                <input type="hidden" id="ad_id" name="ad_id"value="<?php echo $ad->ad_id; ?>">
            </div>
            
            <button type="submit" class="btn btn-primary pull-right" name="btn_update">Update</button>
        </form>
    </div>
</div>