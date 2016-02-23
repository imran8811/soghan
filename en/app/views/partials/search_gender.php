<select class="gender" id="gender" name="gender">
    <option value="">Select Type</option>
    <?php foreach ($genders as $g) { ?>
    <option value="<?php echo $g['gender']; ?>"><?php echo $g['gender']; ?></option>
    <?php } ?>
</select>