<div class="videos-list vids">
    <?php 
    if(count($youtube->items) > 0){
    foreach ($youtube->items as $vid) { ?>
        <a href="<?php echo base_url() . 'watch/' . $vid->id->videoId; ?>" target="_blank" class="single-video">
            <div class="image-area">
                <img src="<?php echo $vid->snippet->thumbnails->high->url; ?>" alt="image">
            </div>
            <div class="video-details">
                <strong class="title"><?php echo $vid->snippet->title; ?></strong>
                <p><?php echo $vid->snippet->description; ?></p>
            </div>
        </a>
    <?php } }else{ echo 'No record found.'; } ?>
</div>