<section id="main">
    <div class="container">
        <?php include('search_panel.php'); ?>
        <div class="market-data">
        <?php if(count($posts) > 0){ ?>
        <div class="ads-list">
            <?php foreach($posts as $p){ ?>
            <a href="<?php echo base_url().'market_place_detail/'.$p['post_id']; ?>" class="single-ad">
                <div class="image-area">
                    <img src="<?php echo $p['picture']; ?>" alt="image">
                </div>
                <div class="ad-details">
                    <div class="title-area">
                        <strong class="title"><?php echo ucfirst($p['camel_name']); ?></strong>
                        <span class="price">AED <?php echo number_format($p['price']); ?></span>
                    </div>
                    <p><?php echo ucfirst($p['description']); ?></p>
                    <span class="location"><?php echo ucfirst($p['location']); ?></span>
                </div>
            </a>
            <?php } ?>
        </div>
        <?php } else{ echo '?? ???? ?????'; } ?>
        <?php echo $links; ?>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function(){
          $('.pagination li a').click(function (e) {
            e.preventDefault();
            var h = $(this).attr('href');
            var page = h.split('/');
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url().'quick_search/'; ?>"+page[1],
                cache: false,
                success: function (data) {
                    $('html, body').animate({
                        scrollTop: $(".ads-list").offset().top
                    }, 500); 
                    $('.market-data').html(data);
                }
            });
        }); 
    });
</script>