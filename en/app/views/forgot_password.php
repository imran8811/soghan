<section id="main">
    <div class="pass-cont">
        <div class="pass-inner">
            <h1>Forgot Password</h1>
            <span class="notice-text">Please enter email to receive password reset link.</span>            
            <form action="<?php echo base_url().'forgot'; ?>" method="post" class="profile-form">
                <span class="error-msg"><?php echo $this->session->userdata('msg'); $this->session->unset_userdata('msg'); ?></span>
                <div class="input-wrap">
                    <label for="email">Email Address</label>
                    <div class="input-inner">
                        <input type="email" id="email" name="email" required>
                        <span class="icon email">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <input type="submit" class="btn-password" name="forgot" value="Send">
                </div>
            </form>
        </div>
    </div>
</section>