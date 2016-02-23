<style>
    .post_link{
        float: right;
        background: #e7e7e7;
        color: #a39e9e;
        padding: 5px 6px 6px 5px;
        margin-top: 5px;
    }
    .post_link:hover{        
        background: #e2e2e2;
        cursor: pointer;
    }
</style>
<div id="main">
    <div class="login-form">
        <h4 style="color: red; width: 100%;">
            <?php
            echo validation_errors();
            echo $this->session->userdata('msg');
            $this->session->unset_userdata('msg');
            ?>
        </h4>

        <form action="<?php echo base_url(); ?>save_event" method="post">
                       
        <div id="isbn_data"></div>

            <div class="input-wrap">
                <label for="title">Name</label>
                <input type="text" id="event_name" name="event_name" value="<?php echo $event->name; ?>" class="form-control">
            </div>
        
            <div class="input-wrap">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?php echo $event->title; ?>" class="form-control" required="required">
            </div>
            
            <div class="input-wrap">
                <label for="title">Event Date</label>
                <input type="text" id="date" name="date" value="<?php echo $event->event_date; ?>" placeholder="YYYY-MM-DD" class="form-control" required="required">
            </div>
            
            <div class="input-wrap">
                <label for="title">All Day</label>
                <input type="checkbox" id="all_day" name="all_day" value="1" <?php if($event->all_day == 1){ echo 'checked="checked"'; } ?>>
            </div>        

            <div id="times">
            <div class="input-wrap">
                <label for="title">Start Time</label>
                <input type="text" id="start" name="start" value="<?php if(!empty($event->start_time)){ echo date('H:i:s', strtotime($event->start_time)); } ?>" placeholder="HH:MM:SS (24-hrs" class="form-control" required="required">
            </div>
            
            <div class="input-wrap">
                <label for="title">End Time</label>
                <input type="text" id="end" name="end" value="<?php if(!empty($event->end_time)){ echo date('H:i:s', strtotime($event->end_time)); } ?>" placeholder="HH:MM:SS (24-hrs" class="form-control" required="required">
            </div>
            </div>
            
            <div class="input-wrap">
                <label for="title">Location</label>
                <input type="text" id="location" name="location" value="<?php echo $event->event_location; ?>" class="form-control" required="required">
            </div>
            
            <div class="input-wrap">
                <label for="title">Timezone</label>
                <input type="text" id="timezone" name="timezone" value="<?php echo $event->timezone; ?>" class="form-control">
            </div>
            
            <div class="input-wrap">
                <label for="title">Added to Local Calendar</label>
                <input type="checkbox" id="calendar" name="calendar" value="<?php if($event->addedto_local_calendar == 1){ echo 'checked="checked"'; } ?>">
            </div>
        
            <div class="input-wrap">
                <label for="title">Note</label>
                <textarea cols="5" rows="5" id="note" name="note" class="form-control"><?php echo $event->notes; ?></textarea>
            </div>
            
        <?php if($event){ ?>
            <button type="submit" class="btn btn-primary pull-right" name="update">Update</button>
        <?php }else{ ?>
            <button type="submit" class="btn btn-primary pull-right" name="add">Save</button>
        <?php } ?>
        </form>
    </div>
</div>
<script>
    $(document).ready(function(){
        if(!$('#all_day').is(':checked')){
            $('#times').show();
        }
        else{
            $('#times').hide();
            $('#start, #end').attr('required', false);
        }
        
        $('#all_day').click(function(){
            if(!$(this).is(':checked')){
                $('#times').show();
            }
            else{
                $('#times').hide();
                $('#start, #end').attr('required', false);
            }
        });        
    });
    
</script>