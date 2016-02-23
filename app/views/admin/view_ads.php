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
        echo $this->session->userdata('error');
        $this->session->unset_userdata('error');
        echo $this->session->userdata('success');
        $this->session->unset_userdata('success');
        ?>
    </h4>
    <aside id="sidebar">
        <h2>&nbsp;</h2>
    </aside>
    <div class="content" style="width: 100%;">
        <?php if (count($ads) > 0) { ?>
            <h1 class="heading">Adverts List</h1>

                <table border="1" style="width: 100%;">
                    
                    <tr>
                        <th>Adverts</th>
                        <th>URL</th>
                        <th>Click</th>
                        <th>Loads</th>
                        <th>Sort</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                <?php foreach ($ads as $row) { ?>
                    <tr>
                        <td><img src="<?php echo $row['ad_image']; ?>" border="0" width="600" height="200" /></td>
                        <td><?php echo $row['url']; ?></td>
                        <td><?php echo $row['clicks']; ?></td>
                        <td><?php echo $row['loads']; ?></td>
                        <td><?php echo $row['sort']; ?></td>
                        <td><?php echo date('d-M-Y', strtotime($row['created_date'])); ?></td>
                        <td>
                            <a href="<?php echo base_url().'edit_advert/'.$row['ad_id']; ?>"><span class="post_link">Edit</span></a>
                            <a href="<?php echo base_url().'del_advert/'.$row['ad_id']; ?>"><span class="post_link">Delete</span></a>
                        </td>
                    </tr>

                <?php } ?>
                </table>
        <?php
        } else {
            echo 'No Post Found !';
        }
        ?>
    </div>
</div>