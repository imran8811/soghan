<div id="main">
    <div class="container">
        <div class="events-cont">
            <div class="events-inner">
                <h2><?php echo date('d F'); ?></h2>
                <ul class="event-detail">
                    <li>
                        <span>Event Date</span>
                        <span><?php echo date('d - F Y', strtotime($event->event_date)); ?></span>
                    </li>
                    <li>
                        <span>Event Name</span>
                        <span><?php echo $event->name ?></span>
                    </li>
                    <li>
                        <span>Title</span>
                        <span><?php echo $event->title; ?></span>
                    </li>
                    <?php if($event->all_day == 0){ ?>
                    <li>
                        <span>Start Time</span>
                        <span><?php echo date('h:i A', strtotime($event->start_time)); ?></span>
                    </li>
                    <li>
                        <span>END Time</span>
                        <span><?php echo date('h:i A', strtotime($event->end_time)); ?></span>
                    </li>
                    <?php }else{ ?>
                    <li>
                        <span>Time</span>
                        <span>Full Day</span>
                    </li>
                    <?php } ?>
                    <li>
                        <span>Location</span>
                        <span><?php echo $event->event_location; ?></span>
                    </li>
                    <?php if(!empty($event->notes)){ ?>
                    <li>
                        <span>Note</span>
                        <span><?php echo $event->notes; ?></span>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>