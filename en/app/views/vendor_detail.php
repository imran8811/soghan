<section id="main">
    <div class="vendor-cont">
        <div class="vendor-inner">
            <div class="inner-top">
                <div class="image-area">
                    <img src="<?php echo $detail[0]['image']; ?>" alt="vendor image">
                </div>
                <div class="details">
                    <strong class="title"><?php echo $detail[0]['english_contact_name']; ?></strong>
                    <strong class="sub-title"><?php echo $detail[0]['english_company_name']; ?></strong>
                    <span class="phone"><?php echo $detail[0]['phone'].' '.$detail[0]['mobile']; ?></span>
                    <span class="email"><?php echo $detail[0]['email']; ?></span>
                    <span><?php echo $detail[0]['english_area']; ?></span>
                    <span><?php echo $detail[0]['english_city']; ?></span>
                    <span><?php echo $detail[0]['english_country']; ?></span>
                </div>
            </div>
            <div class="inner-bottom">
                
            <?php //$add = json_decode(file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$detail[0]['latitude'].','.$detail[0]['longitude'].'&sensor=true')); ?>
                
                <a href="http://maps.google.com/?q=<?php echo $detail[0]['latitude'].','.$detail[0]['longitude'];?>" target="_blank">Get Direction</a>
            </div>
                        
        </div>
    </div>
</section>