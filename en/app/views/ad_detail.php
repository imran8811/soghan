<section id="main">
    <?php if($detail){ ?>
    <div class="container">
        <div class="details-cont">
            <div class="head">
                <div class="title-area">
                    <strong class="title"><?php echo $detail[0]['camel_name']; ?></strong>
                    <ul class="links">
                        <li class="views"><?php echo $detail[0]['views']; ?></li>
                        <li class="time"><?php echo date('d-M-Y', strtotime($detail[0]['created_date'])); ?></li>
                        <li class="location"><?php echo $detail[0]['location']; ?></li>
                    </ul>
                </div>
                <span class="price">AED <?php echo number_format($detail[0]['price']); ?></span>
            </div>
            <div class="connected-carousels">
                <div class="stage">
                    <div class="carousel carousel-stage">
                        <ul>
                            <?php foreach($detail as $pic){ ?>
                                <li><img src="<?php echo $pic['picture']; ?>" alt="slider image"></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <a href="#" class="prev prev-stage"><span>&lsaquo;</span></a>
                    <a href="#" class="next next-stage"><span>&rsaquo;</span></a>
                </div>
                <div class="navigation">
                    <a href="#" class="prev prev-navigation">&lsaquo;</a>
                    <a href="#" class="next next-navigation">&rsaquo;</a>
                    <div class="carousel carousel-navigation">
                        <ul>
                            <?php foreach($detail as $pic){ ?>
                                <li><img src="<?php echo $pic['picture']; ?>" width="100" height="115" alt="slider image"></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="inner-cont">
                <div class="detail-list">
                    <ul>
                        <li>Category</li>
                        <li><?php echo $detail[0]['cat_name']; ?></li>
                        <?php if(!empty($detail[0]['sub_cat_name'])){ ?>
                        <li><?php echo $detail[0]['sub_cat_name']; ?></li>
                        <?php } ?>
                        <li><?php echo $detail[0]['type_name']; ?></li>
                    </ul>
                    <ul>
                        <li>Name</li>
                        <li><?php echo $detail[0]['camel_name']; ?></li>
                    </ul>
                    <ul>
                        <li>father name</li>
                        <li><?php echo $detail[0]['father_name']; ?></li>
                    </ul>
                    <ul>
                        <li>Mother Name</li>
                        <li><?php echo $detail[0]['mother_name']; ?></li>
                    </ul>
                    <?php if(!empty($detail[0]['reference_no'])){ ?>
                    <ul>
                        <li>REFERENCE</li>
                        <li><?php if(!empty($detail[0]['reference_no'])){ echo $detail[0]['reference_no']; } ?></li>
                    </ul>
                    <?php } ?>
                </div>
                <div class="contact-details">
                    <div class="head">
                        <span>Owner : <?php echo ucfirst($detail[0]['family_name']); ?></span>
                    </div>
                    <div class="contact-numbers">
                        <span>Mobile : <?php if($this->session->userdata('user_id')==TRUE){ echo $detail[0]['mobile']; } ?></span>
                        <span>Email : <?php if($this->session->userdata('user_id')==TRUE){ echo $detail[0]['email']; } ?></span>
                        <span>City : <?php if($this->session->userdata('user_id')==TRUE){ echo $detail[0]['city_name']; } ?></span>
                        <?php if($this->session->userdata('user_id')==FALSE){ ?>
                        <div class="mask">
                            <p>Please <a href="<?php echo base_url().'login/?status='.$ad_id; ?>">Login</a> or <a href="<?php echo base_url().'register'; ?>">Register</a> to see contact details </p>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if(!empty($detail[0]['description'])){ ?>
            <div class="description">
                <span>Description</span>
                <p><?php if(!empty($detail[0]['description'])){ echo $detail[0]['description']; } ?></p>
            </div>
            <?php } if(!empty($detail[0]['note'])){ ?>
            <div class="note">
                <span>Note</span>
                <p><?php echo $detail[0]['note']; ?></p>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php }else{ ?>
        <h1 class="vendor-heading"><?php echo 'No Record Found'; ?></h1>
    <?php } ?>
</section>