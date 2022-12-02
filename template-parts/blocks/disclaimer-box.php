<?php

/**
 * Disclaimer Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
 $disclaimer_text = get_field('disclaimer_text');
 $default_text = get_field('disclaimer_text_default', 'options');
 $disclaim_link = get_field('link');
 $default_link = get_field('disclaimer_link_default', 'options');

 $link = ($disclaim_link) ? $disclaim_link : $default_link;

 $text = ($disclaimer_text) ? $disclaimer_text : $default_text;


if($text) :
?>
    <div class="disclaimer-block">
        <?php echo '<p>'. $text .' <a href="' . $link['url'] .'"> '. $link['title'] .'.</a></p>'; ?>
    </div>
<?php
endif;