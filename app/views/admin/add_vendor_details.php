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

        <form action="<?php echo base_url(); ?>save_vendor_details" method="post" enctype="multipart/form-data">
                       
            <div class="input-wrap">
                <label for="title">Vendor</label>
                <select name="vendor" id="vendor" required="required">
                    <option>Select Vendor</option>
                    <?php foreach($vendors as $v){ ?>
                        <option value="<?php echo $v['vendor_id']; ?>" <?php if($v['vendor_id'] == $detail->vendor_id){ echo 'selected="selected"'; } ?> ><?php echo $v['vendor_type']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="input-wrap">
                <label for="title">Company Name (arabic)</label>
                <input type="text" id="trans" name="company" value="<?php echo $detail->company_name; ?>" class="form-control" required="required">
            </div>

            <div class="input-wrap">
                <label for="title">Company Name (english)</label>
                <input type="text" id="english_company" name="english_company" value="<?php echo $detail->english_company_name; ?>" class="form-control" required="required">
            </div>

            <div class="input-wrap">
                <label for="title">Contact Name (arabic)</label>
                <input type="text" id="contact" name="contact" value="<?php echo $detail->contact_name; ?>" class="form-control" required="required">
            </div>

            <div class="input-wrap">
                <label for="title">Contact Name (english)</label>
                <input type="text" id="english_contact" name="english_contact" value="<?php echo $detail->english_contact_name; ?>" class="form-control" required="required">
            </div>

            <div class="input-wrap">
                <label for="title">Email</label>
                <input type="text" id="email" name="email" value="<?php echo $detail->email; ?>" class="form-control" required="required">
            </div>

            <div class="input-wrap">
                <label for="title">Phone</label>
                <input type="text" id="phone" name="phone" value="<?php echo $detail->phone; ?>" class="form-control">
            </div>

            <div class="input-wrap">
                <label for="title">Mobile</label>
                <input type="text" id="mobile" name="mobile" value="<?php echo $detail->mobile; ?>" class="form-control" required="required">
            </div>

            <div class="input-wrap">
                <label for="title">Latitude</label>
                <input type="text" id="latitude" name="latitude" value="<?php echo $detail->latitude; ?>" class="form-control">
            </div>

            <div class="input-wrap">
                <label for="title">Longitude</label>
                <input type="text" id="longitude" name="longitude" value="<?php echo $detail->longitude; ?>" class="form-control">
            </div>

            <div class="input-wrap">
                <label for="title">Description</label>
                <textarea cols="5" rows="5" name="description" id="description" class="form-control"><?php echo $detail->description; ?></textarea>
            </div>

            <div class="input-wrap">
                <label for="title">Description</label>
                <textarea cols="5" rows="5" name="english_description" id="english_description" class="form-control"><?php echo $detail->english_description; ?></textarea>
            </div>

            <div class="input-wrap">
                <label for="title">Image</label>
                <input type="file" id="picture" name="picture" class="form-control" <?php if($this->uri->segment(1)=='add_vendor_details'){ echo 'required="required"'; } ?>>
                <?php if(!empty($detail->image)){ ?>
                    <img src="<?php echo $detail->image; ?>" width="100" height="100" />
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