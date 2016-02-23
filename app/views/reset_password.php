<section id="main">
    <div class="pass-cont">
        <div class="pass-inner">
            <h1>اعادة تعيين كلمة السر</h1>
            <form action="<?php echo base_url().'reset'; ?>" method="post" class="profile-form">
                <span class="error-msg">
                    <?php echo validation_errors(); 
                          echo $this->session->userdata('msg');
                          $this->session->unset_userdata('msg');
                    ?>
                </span>
                <div class="input-wrap">
                    <label for="newpassword">كلمة السر الجديدة</label>
                    <div class="input-inner">
                        <input type="password" id="password" name="password" required minlength="8">
                        <span class="icon newpass">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <label for="cpass">تأكيد كلمة المرور</label>
                    <div class="input-inner">
                        <input type="password" id="cpass" name="cpass" required>
                        <span class="icon cnewpass">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <input type="submit" class="btn-password" name="reset" value="حفظ">
                </div>
            </form>
        </div>
    </div>
</section>