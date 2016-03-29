<section id="main">
    <?php if($detail){ ?>
    <div class="container">
        <div class="details-cont">
            <div class="head">
                <div class="title-area">
                    <strong class="title"><?php echo $detail[0]['camel_name']; ?></strong>
                    <ul class="links">
                        <li class="views"><?php echo $detail[0]['views']; ?></li>
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
                                <li>
                                <?php if($pic['picture']){ ?>
                                    <img src="<?php echo $pic['picture']; ?>" alt="slider image">
                                <?php } else { ?>
                                    <img src="<?php echo base_url(); ?>assets/images/placeholder.jpg" alt="slider image">
                                 <?php } ?>
                                </li>
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
                                <li>
                                    <?php if($pic['picture']){ ?>
                                    <img src="<?php echo $pic['picture']; ?>" width="115" alt="slider image">
                                    <?php } else { ?>
                                        <img src="<?php echo base_url(); ?>assets/images/placeholder.jpg" width="115"  alt="slider image">
                                    <?php } ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="inner-cont">
                <div class="detail-list">
                    <ul>
                        <li>الفئة</li>
                        <li><?php
                                $sub_eng = array('RASHAB','BOSCH','GALAYES','HERAN','0');
                                $sub_ar  = array('ركاب','بوش','قلايص','حيران','');
                                $sub_cat = str_replace($sub_eng, $sub_ar, $detail[0]['cat_name']);
                                echo $sub_cat; ?>
                        </li>
                        <?php if(!empty($detail[0]['sub_cat_name'])){ ?>
                        <li><?php
                                $type_eng = array('YETHAA','THNAYA','QABAR','LEGAYA','HEGAGAH','FTAMEEN','0');
                                $type_ar  = array('يذاع','ثنايا','كبار','لقايا','حقاقة','فطامين','');
                                $type     = str_replace($type_eng, $type_ar, $detail[0]['sub_cat_name']);
                                echo $type; ?>
                        </li>
                        <?php } ?>
                        <li><?php
                                $gender_eng = array('GAOUD','BAKRA','ZMOOL','HOOL','EZZAAF','MADANI','BA’IR','NAQA','0');
                                $gender_ar  = array('قعود','بكرة','زمول','حول','عزف','مداني','بعير','ناقة','0');
                                $gender     = str_replace($gender_eng, $gender_ar, $detail[0]['type_name']);
                                echo $gender; ?>
                        </li>
                    </ul>
                    <ul>
                        <li>الاسم</li>
                        <li><?php echo $detail[0]['camel_name']; ?></li>
                    </ul>
                    <ul>
                        <li>اسم الأب</li>
                        <li><?php echo $detail[0]['father_name']; ?></li>
                    </ul>
                    <ul>
                        <li>اسم الأم</li>
                        <li><?php echo $detail[0]['mother_name']; ?></li>
                    </ul>
                    <?php if(!empty($detail[0]['reference_no'])){ ?>
                    <ul>
                        <li>المراجع</li>
                        <li><?php if(!empty($detail[0]['reference_no'])){ echo $detail[0]['reference_no']; } ?></li>
                    </ul>
                    <?php } ?>
                </div>
                <div class="contact-details">
                    <div class="head">
                        <span>مالك : <?php echo ucfirst($detail[0]['family_name']); ?></span>
                    </div>
                    <div class="contact-numbers">
                        <span>الهاتف المتحرك: <?php echo $detail[0]['mobile']; ?></span>
                        <span>لبريد الالكتروني: <?php echo $detail[0]['email']; ?></span>
                        <!--<span>المدينة: <?php //echo $detail[0]['city_name']; ?></span>-->
                        <?php //if($this->session->userdata('user_id')==FALSE){ ?>
<!--                        <div class="mask">
                            <p>الرجاء <a href="<?php //echo base_url().'login/?status='.$ad_id; ?>">الدخول</a> أو <a href="<?php //echo base_url().'register'; ?>">التسجيل</a> لرؤية تفاصيل الاتصال </p>
                        </div>-->
                        <?php //} ?>
                    </div>
                </div>
            </div>
            <?php if(!empty($detail[0]['description'])){ ?>
            <div class="description">
                <span>الوصف</span>
                <p><?php if(!empty($detail[0]['description'])){ echo $detail[0]['description']; } ?></p>
            </div>
            <?php } if(!empty($detail[0]['note'])){ ?>
            <div class="note">
                <span>ملاحظة</span>
                <p><?php echo $detail[0]['note']; ?></p>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php }else{ ?>
        <h1 class="vendor-heading"><?php echo 'لا يوجد سجلات'; ?></h1>
    <?php } ?>
</section>