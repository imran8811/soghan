<section id="main">
    <div class="register-cont">
        <h1>User Registration</h1>
        <div class="register-area">
            <form action="#" method="post" class="register-form">
                <span class="error-msg"></span>
                <div class="input-wrap">
                    <label for="firstname">First Name*</label>
                    <div class="input-inner">
                        <input type="text" id="firstname" name="firstname" required minlength="3" class="reg">
                        <span class="icon">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <label for="lastname">Last Name</label>
                    <div class="input-inner">
                        <input type="text" id="lastname" name="lastname" minlength="3" class="reg">
                        <span class="icon lastname">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <label for="email">Email Address*</label>
                    <div class="input-inner">
                        <input type="email" id="email" name="email" required class="reg">
                        <span class="icon email">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <label for="password">Password*</label>
                    <div class="input-inner">
                        <input type="password" id="password" name="password" required minlength="8" class="reg">
                        <span class="icon password">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <label for="cpassword">Confirm Password*</label>
                    <div class="input-inner">
                        <input type="password" id="cpassword" name="cpassword" required minlength="8" class="reg">
                        <span class="icon password">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <label for="mobilenumber">Mobile Number*</label>
                    <div class="input-inner">
                        <input type="text" id="mobilenumber" name="mobilenumber" required class="reg" maxlength="15">
                        <span class="icon mobile">icon</span>
                    </div>
                </div>
                <span class="required-field">* = Required field</span>
                <input type="submit" class="btn-submit" name="register" value="submit">
                <img src="<?php echo base_url().'assets/images/loader.gif'; ?>" class="mkt-loader" width="60" height="50" />
            </form>
            <div class="contact-area">
                <h2>get in touch</h2>
                <span class="skype">Soghan</span>
                <span class="phone">096 788 7671</span>
            </div>
        </div>
    </div>
</section>