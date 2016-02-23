<select id="sub_cat" name="sub_cat" class="sub-cat">
    <option value="">Select Sub Category</option>
    <?php foreach ($subcats as $sc) { ?>
    <option value="<?php echo $sc['sub_cat_name']; ?>"><?php echo $sc['sub_cat_name']; ?></option>
    <?php } ?>
</select>