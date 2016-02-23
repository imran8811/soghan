<select id="maidan" name="maidan">
    <option value="">ميدان</option>
    <?php foreach ($maidans as $md) { ?>
    <option value="<?php echo $md['maidan_title']; ?>"><?php echo $md['maidan_title']; ?></option>
    <?php } ?>
</select>