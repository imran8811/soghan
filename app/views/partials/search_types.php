<select class="types" id="type" name="type">
    <option value="">اكتب</option>
    <?php foreach ($types as $t) { ?>
    <option value="<?php echo $t['type_id'].'-'.$t['type_name']; ?>"><?php echo $t['arabic_type_name']; ?></option>
    <?php } ?>
</select>

<script>

$(document).ready(function(){  
    
    $('#type').change(function () {
        $.ajax({
            type: 'POST',
            data: { c : $(this).val() },
            url: "<?php echo base_url(); ?>type_genders",
            cache: false,
            success: function (data) {
                $('.gender').replaceWith(data);
            }
        });
    });
});

</script>