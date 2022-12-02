<?php

/**
 * Commercial Provider Block
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$header = get_field('header');
$body = get_field('body');
$features = get_field('features');
$img_side = get_field('image_position');
$img = get_field('image');

//Default images for feature items
$icons = ['clipboard.svg', 'dial.svg', 'cart.svg', 'headphones.svg'];

?>

<section class="commercial-provider-block">
    <div class="provider-heading <?php echo 'img-' . $img_side ?>">
        <div class="content">
            <div>
                <h2><?php echo $header ?></h2>
                <p><?php echo $body ?></p>
            </div>
        </div>
        <div class="image">
            <?php
                if(!wp_is_mobile()){
                    if($img){ 
                        //echo '<img src="' . $img['url'] . '">';
                        echo wp_get_attachment_image($img['id'], 'large');

                    }
                }
            ?>
        </div>
    </div>

    <div class="featured-container">
        <?php $index = 0; foreach($features as $item) : ?>
            <div class="feature-container">
                <div class="icon-container">
                    <div>
                        <?php
                            if($item['change_icon']) { 
                                echo '<img src="' . $item['icon']['url'] . '" alt="' . $item['icon']['alt'] . '" height="36" width="36">';
                            } else {
                                echo '<img src="' . get_template_directory_uri() . '/images/' . $icons[$index] . '" height="36" width="36">';
                            }
                        ?>
                    </div>
                </div>
                <div class="content">
                    <h4><?php echo $item['heading'] ?></h4>
                    <p><?php echo $item['description'] ?></p>
                </div>
            </div>
        <?php $index++; endforeach; ?>
    </div>
</section>