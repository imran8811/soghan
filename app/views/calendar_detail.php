<div id="main">
    <div class="container">
        <div class="events-cont">
            <div class="events-inner">
                <h2><?php echo date('d F'); ?></h2>
                <ul class="event-detail">
                    <li>
                        <span>عنوان الركض</span>
                        <span><?php echo $event->title; ?></span>
                    </li>
                    <li>
                        <span>اسم الركض</span>
                        <span><?php echo $event->name ?></span>
                    </li>
                    <li>
                        <span>تاريخ الركض</span>
                        <span><?php echo date('d - F Y', strtotime($event->event_date)); ?></span>
                    </li>
                    <?php if($event->all_day == 0){ ?>
                    <li>
                        <span>وقت البدء</span>
                        <span><?php echo date('h:i A', strtotime($event->start_time)); ?></span>
                    </li>
                    <li>
                        <span>وقت النهاية</span>
                        <span><?php echo date('h:i A', strtotime($event->end_time)); ?></span>
                    </li>
                    <?php }else{ ?>
                    <li>
                        <span>مرة</span>
                        <span>يوم كامل</span>
                    </li>
                    <?php } ?>
                    <li>
                        <span>موقع الركض</span>
                        <span><?php echo $event->event_location; ?></span>
                    </li>
                    <?php if(!empty($event->notes)){ ?>
                    <li>
                        <span>ملاحظات</span>
                        <span><?php echo $event->notes; ?></span>
                    </li>
                    <?php } ?>
                    <li>
                        <span>طوال اليوم</span>
                        <span>
                            <?php
                                if($event->all_day == 0){
                                    echo 'لا';
                                }else{
                                    echo 'نعم';
                                }
                            ?>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>