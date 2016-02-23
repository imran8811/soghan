<select class="cities" id="city" name="city">
    <option value="">Select City</option>
    <?php foreach ($cities as $ct) { ?>
    <option value="<?php echo $ct['city_id']; ?>"><?php echo $ct['city_name']; ?></option>
    <?php } ?>
</select>