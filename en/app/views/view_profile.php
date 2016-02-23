<section id="main">
    <div class="profile-cont">
        <div class="profile-inner">
            <h1>View Profile</h1>
            <form action="#" method="post" class="profile-form">
                <div class="input-wrap">
                    <label for="firstname">First Name</label>
                    <div class="input-inner">
                        <input type="text" id="firstname" name="firstname" required minlength="3" value="<?php echo $user->first_name; ?>" disabled ='false'>
                        <span class="icon">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <label for="lastname">Family Name</label>
                    <div class="input-inner">
                        <input type="text" id="familyname" name="familyname" required minlength="3" value="<?php echo $user->family_name; ?>" disabled ='false'>
                        <span class="icon lastname">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <label for="email">Email Address</label>
                    <div class="input-inner">
                        <input type="email" id="email" name="email" required value="<?php echo $user->email; ?>" disabled ='disabled'>
                        <span class="icon email">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <label for="mobile-number">Mobile Number</label>
                    <div class="input-inner">
                        <input type="text" id="mobilenumber" name="mobilenumber" required value="<?php echo $user->mobile; ?>" disabled ='false'>
                        <span class="icon mobile">icon</span>
                    </div>
                </div>
                <div class="input-wrap">
                    <input type="submit" class="btn-submit" value="Edit">
                </div>
            </form>
        </div>
    </div>
</section>