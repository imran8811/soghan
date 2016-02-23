<section id="main">
    <div class="pass-cont">
        <div class="pass-inner">
            <h1>Change Password</h1>
            <form action="<?php echo base_url().'save_password'; ?>" method="post" class="profile-form">
                <span class="error-msg">
                    <?php echo validation_errors(); 
                          echo $this->session->userdata('msg');
                          $this->session->unset_userdata('msg');
                    ?>
                </span>
                <div class="input-wrap">
                    <label for="password">Old Password</label>
                    <div class="input-inner">
                        <input type="password" id="current_password" name="current_password" required>
                        <span class="icon password">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <label for="newpassword">New Password</label>
                    <div class="input-inner">
                        <input type="password" id="new_password" name="new_password" required minlength="8">
                        <span class="icon newpass">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <label for="cpass">Confirm Password</label>
                    <div class="input-inner">
                        <input type="password" id="cpass" name="cpass" required>
                        <span class="icon cnewpass">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <input type="submit" class="btn-password" value="Save">
                </div>
            </form>
        </div>
    </div>
</section>