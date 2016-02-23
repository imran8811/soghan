<section id="main">
    <div class="links-cont">
        <h1>RELATED LINKS</h1>
        <div class="related-links">
            <?php 
            if(count($data) > 0){
            foreach($data as $row){ ?>
            <a href="<?php echo base_url().'link_details/'.$row['link_id']; ?>" class="single-link">
                <div class="image-area">
                    <img src="<?php echo $row['image']; ?>" alt="image">
                </div>
                <div class="ad-details">
                    <h2 class="link-name"><?php echo $row['english_username']; ?></h2>
                    <span class="link-url"><?php echo $row['email']; ?></span>
                </div>
            </a>
            <?php }
            }else{ echo 'No Record Found...'; }
            ?>
        </div>
        <?php echo $links; ?>
    </div>
</section>