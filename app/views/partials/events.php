
<div class="events">
<?php if(count($events) > 0){ ?>
    <?php foreach ($events as $row) { ?>
    <a href="<?php echo base_url() . 'event_details/' . $row['event_id']; ?>" class="single-event" target="_blank">
        <div class="date-area">
            <span class="date"><?php echo date('d', strtotime($row['event_date'])); ?></span>
            <span class="month"><?php echo date('M', strtotime($row['event_date'])); ?></span>
        </div>
        <div class="event-details">
            <strong class="title"><?php echo $row['title']; ?></strong>
            <?php if($row['all_day'] == 0){ ?>
                <p><?php echo date('d-M-Y h:i A', strtotime($row['start_time'])); ?>  to <?php echo date('d-M-Y h:i A', strtotime($row['end_time'])); ?></p>
            <?php }else{ ?> 
                <p>Full Day</p>
            <?php } ?>
            <span class="location"><?php echo $row['event_location']; ?></span>
        </div>
    </a>
    <?php } ?>
<?php } else { ?>
<div class="single-event">
    <span class="no-event">لا حدث وجدت في <?php echo date('d-M-Y', strtotime($date)); ?>، يرجى التحقق من تاريخ آخر</span>
</div>
<?php } ?>
</div>