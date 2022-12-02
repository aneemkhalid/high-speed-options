<?php

/**
 * Comparison - Provider Main Differences
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

global $post;

$provider_1_id = get_field('provider_1', $post->ID);
$provider_2_id = get_field('provider_2', $post->ID);

$provider_1_main_differences = get_field('provider_1_main_differences');
$provider_2_main_differences = get_field('provider_2_main_differences');

$provider_1_logo = get_field('logo', $provider_1_id);
$provider_2_logo = get_field('logo', $provider_2_id);
?>

<div class="comparison-provider-main-differences container">
    <div class="row border-radius-20 mb-sm-5 comparison-provider-main-differences-content">
        <div class="col-12 main-differences-logo">
            <div class="col white-background-card thin-boxshadow border-radius-20 p-3 pt-4 pb-4 d-flex justify-content-center align-items-center">
                <img src="<?php echo $provider_1_logo; ?>" alt="logo" class="m-2" width="180" height="50">
            </div>
        </div>
        <div class="col-12 main-differences-text pb-4 pr-4 pt-sm-4">
            <h3 class="mb-3"><?php echo $provider_1_main_differences['title']; ?></h3>
            <?php echo preg_replace("/<p[^>]*><\\/p[^>]*>/", '', $provider_1_main_differences['text']); ?>
        </div>
    </div>
     <div class="row border-radius-20 comparison-provider-main-differences-content">
        <div class="col-12 main-differences-logo">
            <div class="col white-background-card thin-boxshadow border-radius-20 p-3 pt-4 pb-4 d-flex justify-content-center align-items-center">
                <img src="<?php echo $provider_2_logo; ?>" alt="logo" class="m-2" width="180" height="50">
            </div>
        </div>
        <div class="col-12 main-differences-text pb-4 pr-4 pt-sm-4">
            <h3 class="mb-3"><?php echo $provider_2_main_differences['title']; ?></h3>
            <?php echo preg_replace("/<p[^>]*><\\/p[^>]*>/", '', $provider_2_main_differences['text']); ?>
        </div>
    </div>
</div>