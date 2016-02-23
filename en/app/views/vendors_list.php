<section id="main">
    <div class="container">
        <div class="vendors">
            <h2>Looking for vendors</h2>
            <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit. ed quia consequunturmagni dolores eos qu</p>
            <div class="jcarousel2-wrapper">
                <div class="jcarousel2">
                    <ul>
                        <?php foreach($vendors as $v){ ?>
                        <li>
                            <a href="<?php echo base_url().'vendors/'.$v['vendor_id']; ?>">
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
        <?php if($list){ ?>
        <h1 class="vendor-heading"><?php echo strtoupper($list[0]['english_vendor_type']); ?></h1>
        <div class="vendor-list">
            <?php foreach($list as $row){ ?>
            <a href="<?php echo base_url().'detail/'.$row['vendor_detail_id']; ?>" class="single-ad">
                <div class="image-area">
                    <img src="<?php echo $row['image']; ?>" alt="image">
                </div>
                <div class="ad-details">
                    <strong class="title"><?php echo $row['english_company_name']; ?></strong>
                    <span class="place"><?php echo $row['english_contact_name']; ?></span>
                    <span class="phone">
                        <?php 
                    $comma = (!empty($row['mobile']) && !empty($row['phone'])) ? ', ' : '';
                        echo $row['phone'].$comma.$row['mobile']; ?>
                    </span>
                    <span class="email"><?php echo $row['email']; ?></span>
                </div>
            </a>
            <?php } ?>
        </div>
        <?php } else { ?>
        <h1 class="vendor-heading"><?php echo 'No record found.'; ?></h1>
        <?php } ?>
        
        <?php echo $links; ?>
    </div>
</section>