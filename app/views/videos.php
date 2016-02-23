<section id="main">
    <div class="container">
        <div class="find-videos">
            <form action="#" method="post" id="find_form">
                <select id="country" name="country">
                    <option value="">الدولة</option>
                    <?php foreach($countries as $cnt){ ?>
                    <option value="<?php echo $cnt['country_id']; ?>"><?php echo $cnt['arabic_country_name']; ?></option>
                    <?php } ?>
                </select>
                <select class="cities" disabled="disabled">
                    <option value="">المدينة</option>
                </select>
                <select id="maidan" name="maidan" disabled="disabled">
                    <option value="">ميدان</option>
                </select>
                <input type="text" placeholder="التاريخ" id="datepicker" name="date">
                <input type="hidden" id="token" value="Awj231nKIna985jYc">
                <input type="submit" class="btn-submit" value="إيجاد">
            </form>
        </div>
        <img src="<?php echo base_url().'assets/images/loader.gif'; ?>" class="mkt-loader find-loader" />
        <div class="videos-list">
            <?php foreach($youtube->items as $vid){ ?>
            <a href="<?php echo base_url().'watch/'.$vid->id->videoId; ?>" target="_blank" class="single-video">
                <div class="image-area">
                    <img src="<?php echo $vid->snippet->thumbnails->high->url; ?>" alt="image">
                </div>
                <div class="video-details">
                    <strong class="title"><?php echo $vid->snippet->title; ?></strong>
                    <p><?php echo $vid->snippet->description; ?></p>
                </div>
            </a>
            <?php } ?>
        </div>
        <img src="<?php echo base_url().'assets/images/loader.gif'; ?>" class="mkt-loader" />
        <a href="#" class="load-more">حمل أكثر...</a>
    </div>
</section>