<section id="main">
    <!--<a href="videos.php" class="btn-back">Back</a>-->
    <div class="video-cont">
        <strong class="title"><?php echo $youtube->items[0]->snippet->title; ?></strong>
        <iframe id="videoplayer" allowfullscreen src="http://www.youtube.com/embed/<?php echo $youtube->items[0]->id->videoId ?>?html5=1&autoplay=1"></iframe>
        <div class="description">
            <strong>Description</strong>
            <p><?php echo $youtube->items[0]->snippet->description; ?></p>
        </div>
    </div>
</section>