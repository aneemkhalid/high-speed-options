<?php

/**
 * Comparison - Features
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

global $post;

$provider_1_id = get_field('provider_1', $post->ID);
$provider_2_id = get_field('provider_2', $post->ID);

$provider_1_features = get_field('provider_1_features');
$provider_2_features = get_field('provider_2_features');

$provider_1_features_count = count($provider_1_features);
$provider_2_features_count = count($provider_2_features);

$max_count = $provider_1_features_count;
if ($provider_2_features_count > $provider_1_features_count){
    $max_count = $provider_2_features_count;
}


$provider_1_logo = get_field('logo', $provider_1_id);
$provider_2_logo = get_field('logo', $provider_2_id);

?>

<div class="comparison-features-block container white-background-card thick-boxshadow border-radius-20 with-after-content pt-3 pl-5 pb-5 pr-5 desktop-features-block">
    <div class="features-content">
        <div class="row features-image-row">
            <div class="col d-flex align-items-center justify-content-center">
                <img src="<?php echo $provider_1_logo; ?>" alt="logo" width="180" height="40">
            </div>
            <div class="col d-flex align-items-center justify-content-center">
                <img src="<?php echo $provider_2_logo; ?>" alt="logo" width="180" height="40">
            </div>
        </div>
        <?php if ($max_count):
            for ($i=0;$i<$max_count;$i++): ?>
        <div class="row">
            <div class="col pl-4 pt-2 pb-2 pr-5">
                <?php if (isset($provider_1_features[$i])): ?>
                    <h3><?php echo $provider_1_features[$i]['title']; ?></h3>
                    <p><?php echo preg_replace("/<p[^>]*><\\/p[^>]*>/", '', $provider_1_features[$i]['text']); ?></p>
                <?php endif; ?>
            </div>
            <div class="col pr-4 pt-2 pb-2 pl-5">
                <?php if (isset($provider_2_features[$i])): ?>
                    <h3><?php echo $provider_2_features[$i]['title']; ?></h3>
                    <p><?php echo preg_replace("/<p[^>]*><\\/p[^>]*>/", '', $provider_2_features[$i]['text']); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php 
            endfor;
        endif; ?>
    </div>    
    <h5 class="show-more pt-5 pb-4 mb-0"><a href="#" class="button">SHOW MORE</a></h5>
</div>

<div class="comparison-features-block container white-background-card thick-boxshadow border-radius-20 with-after-content p-4 mb-5 mobile-features-block">
    <div class="features-content">
        <div class="row features-image-row mb-4">
            <div class="col d-flex align-items-center justify-content-center">
                <img src="<?php echo $provider_1_logo; ?>" alt="logo" width="180" height="40">
            </div>
        </div>
        <?php if ($provider_1_features_count):
            for ($i=0;$i<$provider_1_features_count;$i++): ?>
        <div class="row">
            <div class="col pl-5 pt-2 pb-2 pr-5">
                <?php if (isset($provider_1_features[$i])): ?>
                    <h3><?php echo $provider_1_features[$i]['title']; ?></h3>
                    <p><?php echo preg_replace("/<p[^>]*><\\/p[^>]*>/", '', $provider_1_features[$i]['text']); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php 
            endfor;
        endif; ?>
    </div>    
    <h5 class="show-more pt-4 pb-4 mb-0"><a href="#" class="button">SHOW MORE</a></h5>
</div>

<div class="comparison-features-block container white-background-card thick-boxshadow border-radius-20 with-after-content p-4 mobile-features-block">
    <div class="features-content">
        <div class="row features-image-row mb-4">
            <div class="col d-flex align-items-center justify-content-center">
                <img src="<?php echo $provider_2_logo; ?>" alt="logo" width="180" height="40">
            </div>
        </div>
        <?php if ($provider_2_features_count):
            for ($i=0;$i<$provider_2_features_count;$i++): ?>
        <div class="row">
            <div class="col pr-5 pt-2 pb-2 pl-5">
                <?php if (isset($provider_2_features[$i])): ?>
                    <h3><?php echo $provider_2_features[$i]['title']; ?></h3>
                    <p><?php echo preg_replace("/<p[^>]*><\\/p[^>]*>/", '', $provider_2_features[$i]['text']); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php 
            endfor;
        endif; ?>
    </div>    
    <h5 class="show-more pt-4 pb-4 mb-0"><a href="#" class="button">SHOW MORE</a></h5>
</div>