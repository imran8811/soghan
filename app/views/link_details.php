<section id="main">
    <div class="vendor-cont">
        <div class="vendor-inner">
            <div class="inner-top">
                <div class="image-area">
                    <img src="<?php echo $detail->image; ?>" alt="vendor image">
                </div>
                <div class="details">
                    <strong class="title"><?php echo $detail->username; ?></strong>
                    <strong class="sub-title"><?php echo $detail->company; ?></strong>
                    <span class="location"><?php echo $detail->location; ?></span>
                </div>
            </div>
            <div class="inner-bottom">
                <a href="https://www.google.com/maps/place/<?php echo $detail->location; ?>" target="_blank">احصل على الاتجاهات</a>
            </div>
        </div>
    </div>
</section>