<style>
    .post_link{
        float: left;
        background: #c7ac73;
        color: #fefefe;
        padding: 10px 15px 10px 15px;
        margin-right: 10px;
    }
    .post_link:hover{        
        background: #e2e2e2;
    }
    a:hover{
        text-decoration: none;
    }
    .views{
        background: #bdcdd3;
        color: #332f2f;
    }
    .push{
        background: #775b5b;
        clear: both;
        margin-top: 30px;
    }
</style>

<div id="main">
    <div class="login-form" style="width: 100%;">
        <h4 style="color: red; width: 100%;">
            <?php
            echo $this->session->userdata('msg');
            $this->session->unset_userdata('msg');
            ?>
        </h4>

        <div class="links">

            <a href="<?php echo base_url(); ?>add_event"><span class="post_link">Add Event</span></a>
            <a href="<?php echo base_url(); ?>view_events"><span class="post_link views">View Events</span></a>
            <a href="<?php echo base_url(); ?>add_vendor"><span class="post_link">Add Vendor</span></a>
            <a href="<?php echo base_url(); ?>view_vendors"><span class="post_link views">View Vendors</span></a>
            <a href="<?php echo base_url(); ?>add_vendor_details"><span class="post_link">Add Vendor Details</span></a>
            <a href="<?php echo base_url(); ?>view_vendor_details"><span class="post_link views">View Vendor Details</span></a>
            <a href="<?php echo base_url(); ?>add_links"><span class="post_link">Add Links</span></a>
            <a href="<?php echo base_url(); ?>view_links"><span class="post_link views">View Links</span></a><br><br><br>
            <a href="<?php echo base_url(); ?>add_maidans"><span class="post_link">Add Maidans</span></a>
            <a href="<?php echo base_url(); ?>view_maidans"><span class="post_link views">View Maidans</span></a>
            <a href="<?php echo base_url(); ?>add_advert"><span class="post_link">Add Advert</span></a>
            <a href="<?php echo base_url(); ?>view_adverts"><span class="post_link views">View Adverts</span></a>
            <a href="<?php echo base_url(); ?>view_users"><span class="post_link views">View Users</span></a>
            <a href="<?php echo base_url(); ?>view_market_places"><span class="post_link views">View MarketPlaces</span></a>
            <a href="<?php echo base_url(); ?>view_expire_market_places"><span class="post_link views">View Expired MarketPlaces</span></a>
            <a href="<?php echo base_url(); ?>push_form"><span class="post_link push">Push Notification</span></a>
        </div>    
    </div>
</div>