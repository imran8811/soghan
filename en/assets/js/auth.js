$(document).ready(function () {    
    var base_url = 'http://localhost/Dropbox/soghandev/en/';
    //var base_url = 'http://soghan.ae/';
    $('.mkt-loader').hide();
    
    $('.login-form').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            data: $(this).serialize(),
            url: base_url+"login",
            cache: false,
            success: function (data) {
                var a = $.parseJSON(data);
                if (a[0] == '1') {
                        window.location = a[1];
                } else {
                    $('.error-msg').html(a[0]);
                }
            }
        });
    });

    $('.register-form').submit(function (e) {
        e.preventDefault();
        $('.mkt-loader').show();
        $.ajax({
            type: 'POST',
            data: $(this).serialize(),
            url: base_url+"register",
            cache: false,
            success: function (data) {
                var a = $.parseJSON(data);
                if (a[0] == '1') {
                    window.location = "";
                } else {
                    $('.mkt-loader').hide();
                    $('.error-msg').html(a[1]);
                    $('html, body').animate({
                        scrollTop: $(".register-cont").offset().top
                    }, 500);
                    if(a[0] == '0'){
                        $('.reg').removeAttr('required');
                        $('.reg').val('');
                        $("#country").val($("#country option:first").val());
                        $("#city").val($("#city option:first").val());
                        $('#city').attr('disabled', 'disabled');
                    }
                }
            }
        });
    });

    $('#country').change(function () {
        var s = $('#token').val();
        $.ajax({
            type: 'POST',
            data: { c : $(this).val(), s : s },
            url: base_url+"cities",
            cache: false,
            success: function (data) {                              
                if(data.search("Select Maidan") == '56'){
                    $('#maidan').replaceWith(data);
                    $(".cities").attr('disabled','disabled');
                    $(".cities").val($(".cities option:first").val());
                }else{
                    $('.cities').replaceWith(data);
                    $("#maidan").attr('disabled','disabled');
                    $("#maidan").val($("#maidan option:first").val());
                }
            }
        });
    });
    
    $('#country_h').change(function () {
        $.ajax({
            type: 'POST',
            data: { c : $(this).val()},
            url: base_url+"cities_search",
            cache: false,
            success: function (data) { 
                $('.cities_h').replaceWith(data);
            }
        });
    });

    $('#cat').change(function () {
        $.ajax({
            type: 'POST',
            data: { c : $(this).val() },
            url: base_url+"subcats",
            cache: false,
            success: function (data) {
                $('.sub-cat').replaceWith(data);
            }
        });
    });

    $('#sub_cat').change(function () {
    	var id = $(this).val();
        $.ajax({
            type: 'POST',
            data: { c : id },
            url: base_url+"types",
            cache: false,
            success: function (data) {
            	if(data!=0){
	                $('.types').replaceWith(data);
            	}else{
            		// alert(id);
            		$.ajax({
			            type: 'POST',
			            data: { c : id },
			            url: base_url+"genders",
			            cache: false,
			            success: function (data) {
			                $('.gender').replaceWith(data);
			            }
			        });
            	}
            }
        });
    });

    $('#search-form').submit(function (e) {
        e.preventDefault();
        var a = $('#country_h').val();
        var b = $('#city').val();
        var c = $('#sub_cat').val();
        var d = $('#gender').val();
        if(a=='' && b=='' && c=='' && d==''){
            alert('You have not selected any field !');
        }else{
            $('.mkt-loader').show();
            $.ajax({
                type: 'POST',
                data: $(this).serialize(),
                url: base_url+"search",
                cache: false,
                success: function (data) {
                    $('.market-data').html(data);
                }
            });
        }
    });
    
    $('#new_password').focus(function(){
            var cur = $('#current_password').val(); 
            if(cur === ''){
                $(".error-msg").show();
                $(".error-msg").text('Current Password Required!');
                $(".error-msg").fadeOut(5000);
            }else{
                $.ajax({
                    type: 'POST',
                    data: { cur : cur },
                    url : base_url+"check",
                    cache: false,
                    success : function(data){
                        $(".error-msg").show();
                        $(".error-msg").text(data);
                        $(".error-msg").fadeOut(5000);
                    }
                });
                
            }
        });        
        
    $('.load-more').click(function (e) {
        e.preventDefault();
        $('.mkt-loader').show();
        $.ajax({
            type: 'POST',
            url: base_url+"load",
            cache: false,
            success: function (data) { 
                $('.mkt-loader').hide();
                if($('.videos-list:last').html().length=='20'){
                    $('.load-more').hide();
                    $('.videos-list:last').replaceWith(data);
                }else{
                    $('.videos-list').append(data);
                }
            }
        });
    });
        
    $('#find_form').submit(function (e) {
        e.preventDefault();
        $('.find-loader').show();
        var date   = $('#datepicker').val();
        var maidan = $('#maidan').val();
        if(date=='' && maidan==''){
            alert('You have not selected any Maidan');
        }else{
            $.ajax({
                type: 'POST',
                data: { date:date, maidan:maidan },
                url: base_url+"search_video",
                cache: false,
                success: function (data) {
                    $('.mkt-loader').hide();
                    if(data.length == '56'){
                        $('.load-more').hide();
                    }else{
                        $('.load-more').show();
                    }
                    $('.videos-list').replaceWith(data);
                }
            });
        }
    });


});
