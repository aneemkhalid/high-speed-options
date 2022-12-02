<?php

/**
 * Comparison Tabel Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
$comparison_tile = get_field('comparison_tile');
$source = get_field('source');
$comparisonCounter = 0;
if($comparison_tile):
?>
    <section class="comparison-tile-main">
        <div class="row">
            <?php 
            foreach ($comparison_tile as $tile) { 
                $tile_width = $tile['tile_width'];
                if(!empty($tile['providers_logo'])){ $logo = get_field('logo',$tile['providers_logo']);}
                
                //dataLayer info

                $variantComparison = [
                    'text' => 'Compare Providers Cards'
                ];
                
                
                 $comparisonSlug = get_post_field( 'post_name', get_post() );
                 $comparisonCounter++;
                 $comparisonProductClick = dataLayerProdClick($tile['providers_logo'], $variantComparison, $comparisonCounter,  $comparisonSlug, $tile['title']);
                ?>
                <div class="col-md-<?php echo $tile_width; ?>">
                    <div class="comparison-tile">
                        <?php 
                            if(!empty($tile['title'])) echo '<h4>'.$tile['title'].'</h4>';
                            if(!empty($tile['providers_logo'])) echo '<a href="'.get_the_permalink($tile['providers_logo']).'" onclick="'.$comparisonProductClick.'"><img src="'.get_field('logo',$tile['providers_logo']).'"/></a>';
                            echo $tile['detail'];
                        ?>
                    </div>
                </div>
            <?php } ?>
            <?php
            if ($source){
                echo '<figcaption class="figcaption-source col-md-12">'.$source.'</figcaption>';
            }
            ?>
        </div>
    </section>
<?php 
endif;