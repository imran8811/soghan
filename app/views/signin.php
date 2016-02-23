<section id="main">
    <div class="login-cont">
        <div class="login-inner">
            <h1>تسجيل دخول المستخدم</h1>
            <form action="#" method="post" class="login-form">
                <span class="error-msg">
                    <?php echo validation_errors(); 
                          echo $this->session->userdata('msg');
                          $this->session->unset_userdata('msg');
                    ?>
                </span>
                <div class="input-wrap">
                    <label for="email">البريد الإلكتروني</label>
                    <div class="input-inner">
                        <input type="email" id="email" name="email" required="required">
                        <span class="icon email">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <label for="password">كلمة السر</label>
                    <div class="input-inner">
                        <input type="password" id="password" name="password" required="required">
                        <span class="icon password">icon</span>
                    </div>
                </div>
                <input type="submit" class="btn-submit" name="btn-submit" value="تسجيل الدخول">
            </form>
            <a href="<?php echo base_url().'forgot'; ?>" class="forgot-pass">هل نسيت كلمة السر</a>
            <a href="<?php echo base_url().'register'; ?>" class="register-user">تسجيل مستخدم جديد</a>
        </div>
    </div>
</section>