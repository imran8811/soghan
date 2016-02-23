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

        <form action="<?php echo base_url(); ?>save_maidans" method="post" enctype="multipart/form-data" id="md-form">
                     
            <div class="input-wrap">
                <label for="title">Maidan Title</label>
                <input type="text" id="trans" name="title" value="<?php echo $maidan->maidan_title; ?>" class="form-control">
            </div>  
            
            <div class="input-wrap">
                <label for="title">Country</label>
                <select id="country" name="country">
                    <option>Select Country</option>
                    <?php foreach($countries as $c){ ?>
                        <option value="<?php echo $c['country_id']; ?>" <?php if($c['country_id'] == $maidan->country_id){ echo 'selected="selected"'; } ?> ><?php echo $c['country_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            
            <div class="input-wrap">
                <label for="title">City</label>
                <select class="cities" id="city" name="city">
                    <option value="">Select City</option>
                    <?php foreach ($cities as $ct) { ?>
                        <option value="<?php echo $ct['city_id']; ?>" <?php if($c['city_id'] == $maidan->city_id){ echo 'selected="selected"'; } ?>><?php echo $ct['city_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
                    
        <?php if($this->uri->segment(3)){ ?>
            <button type="submit" class="btn btn-primary pull-right" id="md-btn" name="update">Update</button>
        <?php }else{ ?>
            <button type="submit" class="btn btn-primary pull-right" id="md-btn" name="add">Save</button>
        <?php } ?>
        </form>
    </div>
</div>