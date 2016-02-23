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
        <?php if (count($maidans) > 0) { ?>
            <h1 class="heading">Maidans List</h1>

                <table border="1" style="width: 100%;">
                    
                    <tr>
                        <th>Title</th>
                        <th>Country</th>
                        <th>City</th>
                        <th>Action</th>
                    </tr>
                <?php foreach ($maidans as $row) { ?>
                    <tr>
                        <td><?php echo $row['maidan_title']; ?></td>
                        <td><?php echo $row['country_name']; ?></td>
                        <td><?php echo $row['city_name']; ?></td>
                        <td>
                            <a href="<?php echo base_url().'edit_maidans/'.$row['maidan_id']; ?>"><span class="post_link">Edit</span></a>
                            <a href="<?php echo base_url().'del_maidans/'.$row['maidan_id']; ?>"><span class="post_link">Delete</span></a>
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