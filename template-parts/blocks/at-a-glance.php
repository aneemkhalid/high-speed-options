<?php

/**
 * At A Glance Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

?>
<section class="glance-wrapper row-full">
    <div class="container">
        <h3><?php the_field('glance_heading'); ?></h3>
        <p><?php the_field('glance_description'); ?></p>
        <?php if( have_rows('at_a_glance_tiles') ): ?>
        <div class="row">
            <?php while( have_rows('at_a_glance_tiles') ): the_row(); 
            $provider = get_sub_field('glance_select_provider');
            $title = get_sub_field('glance_title');
            $description = get_sub_field('glance_tile_description');
            ?>
            <div class="col-md-6">
                <a class="glance_tile_wrap" href="<?php echo get_permalink($provider->ID); ?>">
                    <div class="img_wrap">
                        <img src="<?php echo get_field('logo', $provider->ID); ?>" alt="<?php echo get_the_title($provider->ID); ?>" height="32" width="auto">
                    </div>
                    <div class="title_wrap">
                        <h4><?php echo $title; ?></h4>
                        <span class="material-icons">arrow_forward</span>
                    </div>
                    <p><?php echo $description; ?></p>
                </a>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </div>
</section>