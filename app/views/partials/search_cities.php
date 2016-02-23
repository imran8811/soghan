<select class="cities_h" id="city" name="city">
    <option value="">المدينة</option>
    <?php foreach ($cities as $ct) { ?>
    <option value="<?php echo $ct['city_name']; ?>"><?php echo $ct['arabic_city_name']; ?></option>
    <?php } ?>
</select>