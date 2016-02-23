<div class="find-camel">
    <form action="#" method="post" id="search-form">
        <!-- <input type="text" placeholder="Find a Camel" name="title" id="title"> -->
        <select id="country_h" name="country_h">
            <option value="">الدولة</option>
            <?php foreach($countries as $cnt){ ?>
            <option value="<?php echo $cnt['country_name']; ?>"><?php echo $cnt['arabic_country_name']; ?></option>
            <?php } ?>
        </select>
        <select class="cities_h" disabled="disabled">
            <option value="">المدينة</option>
        </select>
        <select id="sub_cat" name="sub_cat">
            <option value="">السن</option>
            <?php foreach($sub_cats as $sc){ ?>
                <option value="<?php echo $sc['sub_cat_id'].'-'.$sc['sub_cat_name']; ?>"><?php echo $sc['arabic_sub_cat_name']; ?></option>
            <?php } ?>
        </select>
        <select class="types" id="type" name="type" disabled="disabled">
            <option value="">اكتب</option>
        </select>
        <select class="gender" id="gender" name="gender" disabled="disabled">
            <option value="">جنس</option>
        </select>
        <input type="submit" class="btn-submit" value="إيجاد">
    </form>
</div>