<?php

/**
 * Speed Usage Tiles Element Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$speed_tiles = get_field('speed_usage_tiles');
$speed_tiles_prod_imp_load = '';
global $post;
$speedUsagePageSlug = $post->post_name;
$speedUsagePageSlug = ucwords(str_replace("-", " ", $speedUsagePageSlug));
//Build datalayer product impressions from providers
$speedUsagePageVariant = [
    'text' => 'Speed Usage - ' . $speedUsagePageSlug 
];
$main_title = $post->post_title;
$PrdInd = 0;
$light_arr = [];
$medium_arr = [];
$heavy_arr = [];
foreach($speed_tiles['light_user_providers'] as $provider_id){
    $PrdInd++;
    $speedTilesProdClick = dataLayerProdClick($provider_id, $speedUsagePageVariant, $PrdInd,  $speedUsagePageSlug . ' List', $main_title);
    $light_arr[] = '<a href="'. get_the_permalink($provider_id) .'" onclick="'.$speedTilesProdClick.'">'.get_the_title($provider_id).'</a>';
    $providerProdImp = dataLayerProductImpression($provider_id,  $speedUsagePageSlug, $speedUsagePageVariant, $speedUsagePageSlug . ' List', $PrdInd );
    $speed_tiles_prod_imp_load .= $providerProdImp; 
}
foreach($speed_tiles['medium_user_providers'] as $provider_id){
    $PrdInd++;
    $speedTilesProdClick = dataLayerProdClick($provider_id, $speedUsagePageVariant, $PrdInd,  $speedUsagePageSlug . ' List', $main_title);
    $medium_arr[] = '<a href="'. get_the_permalink($provider_id) .'" onclick="'.$speedTilesProdClick.'">'.get_the_title($provider_id).'</a>';
    $providerProdImp = dataLayerProductImpression($provider_id,  $speedUsagePageSlug, $speedUsagePageVariant, $speedUsagePageSlug . ' List', $PrdInd );
    $speed_tiles_prod_imp_load .= $providerProdImp;
}
foreach($speed_tiles['heavy_user_providers'] as $provider_id){
    $PrdInd++;
    $speedTilesProdClick = dataLayerProdClick($provider_id, $speedUsagePageVariant, $PrdInd,  $speedUsagePageSlug . ' List', $main_title);
    $heavy_arr[] = '<a href="'. get_the_permalink($provider_id) .'" onclick="'.$speedTilesProdClick.'">'.get_the_title($provider_id).'</a>';
    $providerProdImp = dataLayerProductImpression($provider_id,  $speedUsagePageSlug, $speedUsagePageVariant, $speedUsagePageSlug . ' List', $PrdInd );
    $speed_tiles_prod_imp_load .= $providerProdImp;
}

?>
<div class="speed-usage-tiles-container">
    <div class="speed-usage-tile card-border p-4">
        <h4 class="mb-3">Light User - 25+ Mbps</h4>
        <?php echo $speed_tiles['light_user_text']; ?>
        <p class="mt-4 mb-0"><span class="font-weight-bold">Recommended Providers: </span><?php echo implode(', ', $light_arr) ?></p>
    </div>
    <div class="speed-usage-tile card-border mt-4 p-4">
        <h4 class="mb-3">Medium User - 100+ Mbps</h4>
        <?php echo $speed_tiles['medium_user_text']; ?>
        <p class="mt-4 mb-0"><span class="font-weight-bold">Recommended Providers: </span><?php echo implode(', ', $medium_arr) ?></p>
    </div>
    <div class="speed-usage-tile card-border mt-4 p-4">
        <h4 class="mb-3">Heavy User - 500+ Mbps</h4>
        <?php echo $speed_tiles['heavy_user_text']; ?>
        <p class="mt-4 mb-0"><span class="font-weight-bold">Recommended Providers: </span><?php echo implode(', ', $heavy_arr) ?></p>
    </div>
</div>

<script>
<?php 
  //dataLayer info    
    echo dataLayerProductImpressionWrapper($speed_tiles_prod_imp_load);
?>
</script>
