<select class="gender" id="gender" name="gender">
    <option value="">جنس</option>
    <?php foreach ($genders as $g) { ?>
    <option value="<?php echo $g['gender']; ?>"><?php echo $g['arabic_gender']; ?></option>
    <?php } ?>
</select>