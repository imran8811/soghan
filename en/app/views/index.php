<section id="main">
    <div class="features">
        <div class="feature-left">
            <h1>To Advertise</h1>
            <h2 class="call-no">055 744 7447</h2>
            <a href="#ex1" class="btn-postad" rel="modal:open">post a free ad</a>
        </div>
        <div class="vids-area">
            <h3>Videos</h3>
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
        for($i=0; $i <12; $i++){
            if(!empty($posts[$i]['camel_name'])){
                ?>
                <a href="<?php echo base_url().'market_place_detail/'.$posts[$i]['post_id']; ?>" class="box">
                    <div class="ad-image">
                        <?php if($posts[$i]['picture']) { ?>
                            <img src="<?php echo $posts[$i]['picture']; ?>" alt="image" height="200">
                        <?php } else { ?>
                            <img src="<?php echo base_url()?>assets/images/placeholder.jpg" alt="image" height="200">
                        <?php } ?>
                        <div class="mask">
                            <img src="<?php echo base_url(); ?>assets/images/icon4.png" alt="icon image">
                            <span class="text"><?php echo ucfirst($posts[$i]['cat_name']).' - '.ucfirst($posts[$i]['sub_cat_name']).' - '.ucfirst($posts[$i]['type_name']); ?></span>
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
        <span>Looking for vendors</span>
        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit. ed quia consequunturmagni dolores eos qu</p>
        <div class="jcarousel2-wrapper">
            <div class="jcarousel2">
                <ul>
                    <?php foreach($vendors as $v){ ?>
                    <li>
                        <a href="<?php echo 'vendors/'.$v['vendor_id']; ?>">
                            <img src="<?php echo $v['vendor_image']; ?>" alt="icon">
                            <span class="text"><?php echo $v['english_vendor_type'] ?></span>
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