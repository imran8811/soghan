</div>
<footer id="footer">
    <div id="wrapper">
        <div class="copyright-area">
            <ul class="social-links">
                <li><a href="https://www.facebook.com/Soghanuae-1708727829355905/" target="_blank">facebook</a></li>
                <li class="twitter"><a href="https://twitter.com/Soghanuae" target="_blank">twitter</a></li>
                <li class="instagram"><a href="https://instagram.com/soghanuae/" target="_blank">instagram</a></li>
            </ul>
            <span class="text">&copy; حقوق الطبع والنشر <?php echo date('Y'); ?> -  صوغان
. جميع الحقوق محفوظة.</span>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/slider.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/auth.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom.js"></script>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-69580030-1', 'auto');
    ga('send', 'pageview');
</script>
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
        $('#slideshow').bjqs({
            animtype      : 'fade',
            height        : 300,
            width         : 1000,
            responsive    : true,
            randomstart   : true
        });
    });
</script>
</body>
</html>