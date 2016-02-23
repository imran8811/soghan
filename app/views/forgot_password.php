<section id="main">
    <div class="pass-cont">
        <div class="pass-inner">
            <h1>هل نسيت كلمة المرور</h1>
            <span class="notice-text">يرجى إدخال البريد الالكتروني لتلقي رابط إعادة تعيين كلمة المرور.</span>
            <form action="<?php echo base_url().'forgot'; ?>" method="post" class="profile-form">
                <span class="error-msg"><?php echo $this->session->userdata('msg'); $this->session->unset_userdata('msg'); ?></span>
                <div class="input-wrap">
                    <label for="email">بريد الالكتروني</label>
                    <div class="input-inner">
                        <input type="email" id="email" name="email" required>
                        <span class="icon email">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <input type="submit" class="btn-password" name="forgot" value="إرسال">
                </div>
            </form>
        </div>
    </div>
</section>