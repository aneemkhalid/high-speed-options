<?php

/**
 * FAQ List Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$header = get_field('header');
$body = get_field('body');
$icon = get_field('icon');
$other = get_field('other');

if($other) {
   $animation_path = '  
          <picture class="lottie">           
           <img src="'.$other['url'].'"   >
         </picture>';
    
} else {
   // $animation_path = get_template_directory_uri() . '/images/satellite.json';
    $animation_path = '
         <picture class="lottie">
           <source type="image/apng" srcset="'. get_template_directory_uri() . '/images/animations/satellite.apng">
           <img src="'. get_template_directory_uri() . 'images/animations/satellite-fallback.svg"  width="80" height="80"  alt="satellite">
         </picture>

    ';
}

?>

<section class="icon-callout-block">
    <div>
        <div class="content-container">
            <h3><?php echo $header ?></h3>
            <p><?php echo $body ?></p>
            <div class="lottie-container">
                <?php echo $animation_path ?>
            </div>
        </div>
    </div>
</section>