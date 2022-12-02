<?php

/**
 * Comparison - Provider Highlights Tiles
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

global $post;

$provider_1_id = get_field('provider_1', $post->ID);
$provider_2_id = get_field('provider_2', $post->ID);

$provider_1_highlights = get_field('provider_1_highlights');
$provider_2_highlights = get_field('provider_2_highlights');

$provider_1_logo = get_field('logo', $provider_1_id);
$provider_2_logo = get_field('logo', $provider_2_id);


if (!$provider_1_highlights){
    $provider_1_highlights = get_field('main_features', $provider_1_id);
}

if (!$provider_2_highlights){
    $provider_2_highlights = get_field('main_features', $provider_2_id);
}
?>

<div class="comparison-provider-highlights-block container">
    <div class="row">
        <div class="col-12 col-sm white-background-card thick-boxshadow border-radius-20 p-4 mr-sm-4 d-flex flex-column align-items-center justify-content-between">
            <img src="<?php echo $provider_1_logo; ?>" alt="logo" class="m-2" width="180" height="40">
            <div class="features">
                <ul class="ml-3 mt-2 ml-md-0 mb-4 mb-md-0">
                <?php foreach ($provider_1_highlights as $feature){
                    echo '<li class="li-done mb-2">'.$feature['feature'].'</li>';
                }
                ?>
                </ul>
            </div>
        </div>

        <div class="col-12 col-sm white-background-card thick-boxshadow  border-radius-20 p-4 mt-4 mt-sm-0 d-flex flex-column align-items-center justify-content-between">
            <img src="<?php echo $provider_2_logo; ?>" alt="logo" class="m-2" width="180" height="40">
            <div class="features">
                <ul class="ml-3 mt-2 ml-md-0 mb-4 mb-md-0">
                <?php foreach ($provider_2_highlights as $feature){
                    echo '<li class="li-done mb-2">'.$feature['feature'].'</li>';
                }
                ?>
                </ul>
            </div>
        </div>
    </div>
</div>