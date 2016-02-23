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

        <form action="<?php echo base_url(); ?>pro_notification" method="post">
                       
        <div id="isbn_data"></div>
        
            <div class="input-wrap">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control">
            </div>        
            <div class="input-wrap">
                <label for="title">Message</label>
                <textarea id="msg" name="msg" class="form-control"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary pull-right">Send</button>
        </form>
    </div>
</div>