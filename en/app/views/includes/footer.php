</div>
<footer id="footer">
    <div id="wrapper">
        <div class="copyright-area">
            <ul class="social-links">
                <li><a href="https://www.facebook.com/Soghanuae-1708727829355905/" target="_blank">facebook</a></li>
                <li class="twitter"><a href="https://twitter.com/Soghanuae" target="_blank">twitter</a></li>
                <li class="instagram"><a href="https://instagram.com/soghanuae/" target="_blank">instagram</a></li>
            </ul>
            <span class="text">&copy; Copyright <?php echo date('Y'); ?> - Soghan. All rights reserved.</span>
        </div>
    </div>
</footer>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.matchHeight.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jcarousel.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jcarousel-responsive.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/modal.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/fullcalendar.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/auth.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom.js"></script>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<script>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            dayClick: function(date, jsEvent, view) {
                var date = date.format();
                $.ajax({
                type: 'POST',
                data: { date : date},
                url: "<?php echo base_url() . 'calendar_events'; ?>",
                cache: false,
                success: function (data) {
                    $('#new').html(data);
                    $('html, body').animate({
                        scrollTop: $(".events").offset().top
                    }, 500);
                }
            });
            },
            eventLimit: true,
            events: <?php echo json_encode($events); ?>
        });
        
        $('.fc-next-button, .fc-prev-button, .fc-today-button').click(function() {
            var moment = $('#calendar').fullCalendar('getDate');
            var date = moment.format().split('T');
            $.ajax({
                type: 'POST',
                data: { date : date[0], s : '1'},
                url: "<?php echo base_url() . 'calendar_events'; ?>",
                cache: false,
                success: function (data) {
                    $('#new').html(data);
                }
            });       
        });
    });
</script>
</body>
</html>