<section id="main">
    <div class="features">
        <div class="feature-left">
            <!--<img src="<?php echo base_url(); ?>assets/images/img2.jpg" alt="feature image">-->
            <h1>To Advertise</h1>
            <h2 class="call-no">055 744 7447</h2>
            <a href="#ex1" class="btn-postad" rel="modal:open">بعد إعلان مجانا</a>
        </div>
        <div class="vids-area">
            <h3>فيديوات</h3>
            <ul>
                <?php foreach($youtube->items as  $vid){ ?>
                <li>
                    <a href="<?php echo base_url().'watch/'.$vid->id->videoId; ?>" target="_blank">
                        <div class="image-area">
                            <img src="<?php echo $vid->snippet->thumbnails->high->url; ?>" alt="video thumbnail">
                        </div>
                        <div class="vid-details">
                            <strong class="title"><?php echo $vid->snippet->title; ?></strong>
                            <p><?php echo $vid->snippet->description; ?></p>
                        </div>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php include('search_panel.php'); ?>
    <div class="market-data">
        <img src="<?php echo base_url().'assets/images/loader.gif'; ?>" class="mkt-loader" />
    </div>
    <div class="boxes">
        <?php $limit =  0;
        for($i=0; $i<12; $i++){
            if(!empty($posts[$i]['camel_name'])){
        ?>
            <a href="<?php echo base_url().'market_place_detail/'.$posts[$i]['post_id']; ?>" class="box">
                <div class="ad-image">
                    <img src="<?php echo $posts[$i]['picture']; ?>" alt="image" height="200">
                    <div class="mask">
<!--                        <img src="--><?php //echo base_url(); ?><!--assets/images/icon4.png" alt="icon image">-->
                        <?php
                            $sub_eng = array('RASHAB','BOSCH','GALAYES','HERAN','0');
                            $sub_ar  = array('ركاب','بوش','قلايص','حيران','');
                            $sub_cat = str_replace($sub_eng, $sub_ar, $posts[$i]['cat_name']);

                            $type_eng = array('YETHAA','THNAYA','QABAR','LEGAYA','HEGAGAH','FTAMEEN','0');
                            $type_ar  = array('يذاع','ثنايا','كبار','لقايا','حقاقة','فطامين','');
                            $type     = str_replace($type_eng, $type_ar, $posts[$i]['sub_cat_name']);

                            $gender_eng = array('GAOUD','BAKRA','ZMOOL','HOOL','EZZAAF','MADANI','BA’IR','NAQA','0');
                            $gender_ar  = array('قعود','بكرة','زمول','حول','عزف','مداني','بعير','ناقة','0');
                            $gender     = str_replace($gender_eng, $gender_ar, $posts[$i]['type_name']);
                        ?>
                        <span class="text"><?php echo $sub_cat.' - '.$type.' - '.$gender; ?></span>
                    </div>
                </div>
                <strong class="title"><?php echo ucfirst($posts[$i]['camel_name']); ?></strong>
                <p><?php echo ucfirst(substr($posts[$i]['description'], 0, 40)).'....'; ?></p>
                <div class="price-area">
                    <span class="price">AED <?php echo number_format($posts[$i]['price']); ?></span>
                </div>
            </a>
        <?php }
            $limit++;
        } ?>
    </div>
    <div class="vendors">
        <h2>أبحث عن البائعين</h2>
        <div class="jcarousel2-wrapper">
            <div class="jcarousel2">
                <ul>
                    <?php foreach($vendors as $v){ ?>
                    <li>
                        <a href="<?php echo 'vendors/'.$v['vendor_id']; ?>">
                            <img src="<?php echo $v['vendor_image']; ?>" alt="icon">
                            <span class="text"><?php echo $v['vendor_type'] ?></span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <a href="#" class="jcarousel2-control-prev">&lsaquo;</a>
            <a href="#" class="jcarousel2-control-next">&rsaquo;</a>
        </div>
    </div>
</section>
<div class="modal" id="ex1" style="display:none;">
    <p>To post a new ad please download soghan app from App Store</p>
    <div class="modal-head"><span>Download from App Store </span><img src="<?php echo base_url(); ?>assets/images/img15.png" alt="app store image"></div>
    <a href="#" class="btn-download">Download</a>
</div>