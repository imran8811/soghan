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
        <?php if (count($link) > 0) { ?>
            <h1 class="heading">Links List</h1>

                <table border="1" style="width: 100%;">
                    
                    <tr>
                        <th>Company Name</th>
                        <th>Contact Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Location</th>
                        <th>Small Image</th>                        
                        <th>Large Image</th>                        
                        <th>Action</th>
                    </tr>
                <?php foreach ($link as $row) { ?>
                    <tr>
                        <td><?php echo $row['company']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['location']; ?></td>
                        <td><img src="<?php echo $row['image']; ?>" width="80" height="50" /></td>
                        <td><img src="<?php echo $row['detail_image']; ?>" width="80" height="50" /></td>
                        <td>
                            <a href="<?php echo base_url().'edit_links/'.$row['link_id']; ?>"><span class="post_link">Edit</span></a>
                            <a href="<?php echo base_url().'del_links/'.$row['link_id']; ?>"><span class="post_link">Delete</span></a>
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