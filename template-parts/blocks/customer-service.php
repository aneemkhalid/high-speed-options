<?php 

$title = get_field('title');
$content = get_field('content');
$img = ($bgImg = get_field('image_override')) ? $bgImg : get_template_directory_uri() . '/images/cs_bg.png';

?>

<section class="customer-service-block row-full">
    <div class="container">
        <div class="bg-container">
            <img src="<?php echo $img ?>" alt="customer service" width="325" height="400">
            <div class="blue-bg"></div>
        </div>
        <div class="header">
            <div class="title-container">
                <div class="pretitle">Customer Service</div>
                <h2><?php echo $title ?></h2>
                <div class="descrip">
                    <?php echo $content ?>
                </div>
            </div>
        </div>
    </div>
</section>