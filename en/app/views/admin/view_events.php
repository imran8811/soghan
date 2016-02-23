<style>
    .post_link{
        float: left;
        background: #e7e7e7;
        color: #a39e9e;
        padding: 5px 6px 5px 6px;
        margin-right: 10px;
    }
    .post_link:hover{        
        background: #e2e2e2;
    }
    a:hover{
        text-decoration: none;
    }
    table{
        width: 50%;
    }
    td{
        padding: 10px;
    }
</style>
<div id="main">
    <h4 style="color: red; width: 100%;">
        <?php
        echo $this->session->userdata('msg');
        $this->session->unset_userdata('msg');
        ?>
    </h4>
    <aside id="sidebar">
        <h2>&nbsp;</h2>
    </aside>
    <div class="content" style="width: 100%;">
        <?php if (count($events) > 0) { ?>
            <h1 class="heading">Events List</h1>

                <table border="1" style="width: 100%;">
                    
                    <tr>
                        <th>Name</th>
                        <th>Title</th>
                        <th>Event Date</th>
                        <th>All Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Location</th>
                        <th>Timezone</th>
                        <th>notes</th>
                        <th>Action</th>
                    </tr>
                <?php foreach ($events as $row) { ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo date('d-M-Y', strtotime($row['event_date'])); ?></td>
                        <td><?php if($row['all_day'] == 1){ echo 'Yes'; } ?></td>
                        <td><?php if($row['all_day'] == 0){ echo date('d-M-Y H:i:s', strtotime($row['start_time'])); } ?></td>
                        <td><?php if($row['all_day'] == 0){ echo date('d-M-Y H:i:s', strtotime($row['end_time'])); } ?></td>
                        <td><?php echo $row['event_location']; ?></td>
                        <td><?php echo $row['timezone']; ?></td>
                        <td><?php echo $row['notes']; ?></td>
                        <td>
                            <a href="<?php echo base_url().'edit_event/'.$row['event_id']; ?>"><span class="post_link">Edit</span></a>
                            <a href="<?php echo base_url().'del_event/'.$row['event_id']; ?>"><span class="post_link">Delete</span></a>
                        </td>
                    </tr>
                <?php }?>
                </table>

        <?php            
        } else {
            echo 'No Post Found !';
        }
        
        echo $links;
        ?>
    </div>
</div>