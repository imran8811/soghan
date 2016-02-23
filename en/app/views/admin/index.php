
<div id="main">
    <div class="login-form">
        <h4 style="color: red; width: 100%;">
            <?php
            echo $this->session->userdata('msg');
            $this->session->unset_userdata('msg');
            ?>
        </h4>
        <form action="<?php echo base_url(); ?>admin_login" method="post">
            <div class="input-wrap">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" value="admin">
            </div>
            <div class="input-wrap">
                <label for="password">Passowrd</label>
                <input type="password" id="password" name="password" class="form-control" value="admin">
            </div>
            <button type="submit" class="btn btn-primary pull-right">Login</button>
        </form>
    </div>
</div>