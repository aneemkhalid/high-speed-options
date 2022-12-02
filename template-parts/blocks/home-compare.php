<?php
    $title = get_field('title');
    $disclosure = get_field('disclosure_link');
    $image = get_field('image');
    $items = get_field('items');
    $hmsdin = get_field('hmsdin');
?>

<div class="home-compare-block">
    <div class="container">

        <div class="content-container">

            <div class="img-container">
                <?php echo  wp_get_attachment_image( $image, 'full', null, array("class" => 'img-left')) ?>
            </div>

            <div class="right-content">
                <h3><?php echo $title ?></h3>
                <div class="disclose-link">
                    <a href="<?php echo $disclosure['url'] ?>"><?php echo $disclosure['title'] ?></a>
                </div>
                <div class="item-container">
                    <?php foreach($items as $item) : ?>
                        <div>
                            <h5><?php echo $item['title'] ?></h5>
                            <div><?php echo $item['content'] ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="hmsdin-box">
                    <h3><?php echo $hmsdin['title'] ?></h3>
                    <div><?php echo $hmsdin['content'] ?></div>
                    <div class="hmsdin-link">
                        <a href="<?php echo $hmsdin['link']['url'] ?>" class="cta_btn"><?php echo $hmsdin['link']['title'] ?></a>
                    </div>
                </div>
            </div>

            <img src="<?php echo get_template_directory_uri() . '/images/dots.svg' ?>" alt="dots" class="dots" width="300" height="225">

        </div>
    </div>
</div>