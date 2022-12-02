<?php 

$title = get_field('title');
$descrip = get_field('description');

$pro_title = get_field('pros_title');
$pros = get_field('pro');
$cons_title = get_field('cons_title');
$cons = get_field('cons');

$check = get_stylesheet_directory_uri() . '/images/check.svg';
$close = get_stylesheet_directory_uri() . '/images/close.svg';

$disclaim = get_field('disclaimer_text');

?>

<section class="blue-overview-block row-full">
    <div class="blue-container">
        <div class="container top-container">
            <h2><?php echo $title ?></h2>
            <div class="descrip-container">
                <?php echo $descrip ?>
            </div>
        </div>
        <div class="container white-container">
            <div class="pro-container">
                <h5><?php echo $pro_title ?></h5>
                <div>
                    <?php foreach($pros as $item) : ?>
                        <div class="like-item">
                            <div class="icon-container">
                                <img src="<?php echo $check ?>" alt="check icon" height="24" width="24">
                            </div>
                            <div class="like-content">
                                <?php echo $item['bullet'] ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="con-container">
                <h5><?php echo $cons_title ?></h5>
                <div>
                    <?php foreach($cons as $item) : ?>
                        <div class="like-item">
                            <div class="icon-container">
                                <img src="<?php echo $close ?>" alt="close icon" height="24" width="24">
                            </div>
                            <div class="like-content">
                                <?php echo $item['bullet'] ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php if($disclaim) : ?>
        <div class="container disclaim-container">
            <div class="disclaimer-text">
                <?php echo $disclaim; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
