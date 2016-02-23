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
                <input type="file" id="picture" name="picture" class="form-control" style="padding-bottom: 40px;">
            </div>
            <div class="input-wrap">
                <label for="title">URL</label>
                <input type="text" id="url" name="url" class="form-control" placeholder="http://soghan.ae">
            </div>
            <div class="input-wrap">
                <label for="title">Sort</label>
                <input type="text" id="sort" name="sort" class="form-control">
            </div>
            
            <button type="submit" class="btn btn-primary pull-right">Save</button>
        </form>
    </div>
</div>