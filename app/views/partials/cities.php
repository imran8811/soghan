<select class="cities" id="city" name="city" required="required">
    <option value="">المدينة</option>
    <?php foreach ($cities as $ct) { ?>
    <option value="<?php echo htmlspecialchars($ct['city_id']); ?>"><?php echo $ct['arabic_city_name']; ?></option>
    <?php } ?>
</select>

<script>

$(document).ready(function(){   
    
    $('#city').change(function () {
        $.ajax({
            type: 'POST',
            data: { c : $(this).val() },
            url: "<?php echo base_url(); ?>maidan",
            cache: false,
            success: function (data) {                              
                $('#maidan').replaceWith(data);
            }
        });
    }); 
});

</script>