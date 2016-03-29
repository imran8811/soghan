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
    
    <div>
        <img src="<?php echo base_url().'assets/admin/images/green_btn.png'; ?>" width="15" height="15" />
        <img src="<?php echo base_url().'assets/admin/images/MarketPlaces.png'; ?>" width="75" height="55" />
        <img src="<?php echo base_url().'assets/admin/images/blue_btn.png'; ?>" width="15" height="15" />
        <img src="<?php echo base_url().'assets/admin/images/Portfolio.png'; ?>" width="75" height="55" />
    </div>
    <div class="content" style="width: 100%;">
        <?php if (count($posts) > 0) { ?>
            <h1 class="heading">User Posts List</h1>

            <form action="<?php echo base_url().'del_portfolio/'.$posts[0]['user_id']; ?>" method="post">
                <button type="submit" class="btn btn-primary pull-right" style="margin-bottom: 10px;">Delete Selected</button>
                <table border="1" style="width: 100%;">
                    <tr>
                        <th>Select</th>
                        <th>Camel Name</th>
                        <th>Category</th>
                        <th>Sub-Category</th>
                        <th>Gender</th>
                        <th>Price</th>
                        <th>Picture</th>
                        <th>Days</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Status</th>
                    </tr>
                <?php foreach ($posts as $row) { ?>
                    <tr>
                        <td><input type="checkbox" id="chk_del" name="chk_del[]" value="<?php echo $row['post_id']; ?>"></td>
                        <td><?php echo $row['camel_name']; ?></td>
                        <td><?php echo $row['cat_name']; ?></td>
                        <td><?php echo $row['sub_cat_name']; ?></td>
                        <td><?php echo $row['type_name']; ?></td>
                        <td><?php echo number_format($row['price']); ?></td>
                        <td><a href="<?php echo $row['picture']; ?>" target="_blank" ><img src="<?php echo $row['picture']; ?>" width="100px" height="100px" /></a></td>
                        <td><?php if($row['diff'] == 0){ echo 'Today'; } else{ echo $row['diff'].' days ago'; } ?></td>
                        <td><?php echo $row['email']; ?>
                            <input type="hidden" id="email_<?php echo $row['post_id']; ?>" name="email_<?php echo $row['post_id']; ?>" value="<?php echo $row['email']; ?>" >
                        </td>
                        <td><?php echo $row['mobile']; ?></td>
                        <td>
                            <?php if($row['status']==0){ ?>
                            <img src="<?php echo base_url().'assets/admin/images/blue_btn.png'; ?>" width="15" height="15" />
                            <?php }else{ ?>
                            <img src="<?php echo base_url().'assets/admin/images/green_btn.png'; ?>" width="15" height="15" />
                            <?php } ?>
                        </td>
                    </tr>
                <?php }?>
                </table>
            </form>
        <?php            
        } else {
            echo 'No Post Found !';
        }
        
        echo $links;
        ?>
    </div>
</div>