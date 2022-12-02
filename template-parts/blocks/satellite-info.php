<?php

/**
 * Satellite Info Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$content = get_field('content');
$image = get_field('image');


?>

<section class="satellite-info-block">
    <div class="content-container">
        <div class="image">
            <?php 
                if( $image ) {
                    echo wp_get_attachment_image( $image, 'full' );
                }
            ?>
        </div>
        <div class="content">
            <div>
                <?php echo $content ?>
            </div>
        </div>
    </div>
</section>