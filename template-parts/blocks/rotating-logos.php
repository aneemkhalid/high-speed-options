<?php

/**
 * Rotating Logos Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */


if (!wp_is_mobile()){
    $args = array(  
        'post_type'         => 'provider',
        'post_status'       => 'publish',
        'orderby'           => 'post_title',
        'order'             => 'ASC',
        'posts_per_page'    => -1,
        'meta_query'        => array(
            array(
                'key'       => 'partner',
                'value'     => true,
                'compare'   => '=',
            )
        )
    );
    $providers = new WP_Query( $args );
    $provider_posts = $providers->posts;


//dataLayer info
$sliderLogoCounter = 0;


if(is_array($provider_posts)):
?>
<div class="slick-slide-container">
    <div class="left-blur"></div>
        <div class="row slick-slide-row">
            <?php foreach( $provider_posts as $post ): ?>
            <?php 
                  //dataLayer info
                  $providerID = $post->ID;
                  $sliderLogoCounter++;
                  $sliderLogoVariant = [
                      'text' => 'Homepage Logo Slider'
                  ];

                  $sliderLogoProductClick = dataLayerProdClick($providerID, $sliderLogoVariant, $sliderLogoCounter,  "Homepage", "Homepage Logo Slider");
            
                 
                  //dataLayerProductImpression($provider, $dataLayerCategory, $dataLayerVariant, $dataLayerList, $dataLayerPosition );
                  $sliderLogoIndv = dataLayerProductImpression($providerID, "Homepage", $sliderLogoVariant, "Homepage Slider List", $sliderLogoCounter );
                                            
                  $sliderLogoLoad .= $sliderLogoIndv;  
            
            
                 ?>
                <a href="<?php echo get_permalink($post->ID); ?>" class="slick-slide-link" onclick="<?php echo $sliderLogoProductClick; ?>">
                    <img src="<?php echo get_field('logo', $post->ID); ?>" alt="<?php echo $post->post_title; ?>" class="slick-slide-logo">
                </a>
                
            <?php endforeach; ?>
        </div>
    <div class="right-blur"></div>
</div>

    <?php 
      //dataLayer info    
     $sliderLogoWrapper = dataLayerProductImpressionWrapper($sliderLogoLoad );

    ?>


<script>
    
    <?php echo $sliderLogoWrapper ?>
    
    window.addEventListener('DOMContentLoaded', () => {
        ($ => {
            $('.slick-slide-container').show();
            $('.slick-slide-row').slick({
                dots: false,
                nextArrow: false,
                prevArrow: false,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 0,
                speed: 5500,
                cssEase: 'linear',
                slidesToShow: 5,
                pauseOnHover: true,
                pauseOnFocus: true,
                // use width of .slick-slide from css instead 
                // of automatically calculating width
                variableWidth: true,
            });
        })(jQuery);
    });
</script>
    <?php endif;

}