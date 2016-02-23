<section id="main">
    <div class="register-cont">
        <h1>تسجيل مستخدم جديد</h1>
        <div class="register-area">
            <form action="#" method="post" class="register-form">
                <span class="error-msg"></span>
                <div class="input-wrap">
                    <label for="firstname">الاسم الأول*</label>
                    <div class="input-inner">
                        <input type="text" id="firstname" name="firstname" required minlength="3" class="reg">
                        <span class="icon">icon</span>
                    </div>
                </div>
<!--                <div class="input-wrap">-->
<!--                    <label for="lastname">Middle Name</label>-->
<!--                    <div class="input-inner">-->
<!--                        <input type="text" id="lastname" name="middlename" minlength="3" class="reg">-->
<!--                        <span class="icon lastname">icon</span>-->
<!--                    </div>-->
<!--                </div>-->
                <div class="input-wrap">
                    <label for="familyname">اسم العائلة*</label>
                    <div class="input-inner">
                        <input type="text" id="familyname" name="familyname" required minlength="3" class="reg">
                        <span class="icon">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <label for="email">البريد الإلكتروني*</label>
                    <div class="input-inner">
                        <input type="email" id="email" name="email" required class="reg">
                        <span class="icon email">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <label for="password">كلمة السر*</label>
                    <div class="input-inner">
                        <input type="password" id="password" name="password" required minlength="8" class="reg">
                        <span class="icon password">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <label for="cpassword">تأكيد كلمة السر*</label>
                    <div class="input-inner">
                        <input type="password" id="cpassword" name="cpassword" required minlength="8" class="reg">
                        <span class="icon password">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <label for="mobilenumber">الجوال*</label>
                    <div class="input-inner">
                        <input type="text" id="mobilenumber" name="mobilenumber" required class="reg" maxlength="15">
                        <span class="icon mobile">icon</span>
                    </div>
                </div>
<!--                <div class="input-wrap">-->
<!--                    <label for="country-list">Country*</label>-->
<!--                    <div class="input-inner">-->
<!--                        <select id="country" name="country">-->
<!--                            <option value="">Select Country</option>-->
<!--                            --><?php //foreach($countries as $cnt){ ?>
<!--                                <option value="--><?php //echo $cnt['country_id']; ?><!--">--><?php //echo $cnt['country_name'] ?><!--</option>-->
<!--                            --><?php //} ?>
<!--                        </select>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="input-wrap">-->
<!--                    <label for="city-list">City*</label>-->
<!--                    <div class="input-inner">-->
<!--                        <select class="cities" disabled="disabled">-->
<!--                            <option value="">Select City</option>-->
<!--                        </select>-->
<!--                    </div>-->
<!--                </div>-->
                <span class="required-field">* = حقل مطلوب</span>
                <input type="submit" class="btn-submit" name="register" value="أرسال">
                <img src="<?php echo base_url().'assets/images/loader.gif'; ?>" class="mkt-loader" width="60" height="50" />
            </form>
            <div class="contact-area">
                <h2>احصل على دعم</h2>
                <span class="skype">Soghan</span>
                <span class="phone">096 788 7671</span>
            </div>
        </div>
    </div>
</section>