<?php

/**
 * Provider By Connection Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

    $heading = get_field('connection_heading');
    $description = get_field('connection_description');

?>
<section class="connection-type-providers-wrap row-full">
    <div class="container">
        <?php if(!empty($heading)){ ?>
        <h3><?php echo $heading; ?></h3>
        <?php } else{ ?>
           <h3>Best Providers by Connection Type</h3> 
        <?php } ?>
        <p><?php echo $description; ?></p>

        <?php if( have_rows('provider_by_connection_tiles') ): ?>
        <div class="row">
            <?php while( have_rows('provider_by_connection_tiles') ): the_row();
            $select_provider_type = get_sub_field('select_provider_type_connection');
            $select_internet_connection_type = get_sub_field('select_internet_connection_type');
            $select_tv_connection_type = get_sub_field('select_tv_connection_type');
            
            if($select_provider_type == 'internet'){
                $provider_link = get_page_link($select_internet_connection_type);
            } else {
                $provider_link = get_page_link($select_tv_connection_type);
            }
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="connection-type-providers">
                    <div class="title_wrap">
                        <a href="<?php echo $provider_link; ?>">
                            <h4><?php echo get_sub_field('tile_blue_title'); ?></h4>
                        </a>
                        <a href="<?php echo $provider_link; ?>">
                            <span class="material-icons">arrow_forward</span>
                        </a>
                    </div>
                    <div class="connection-type-providers-pros-cons">
                        <div class="connection-type-providers-pros">
                        <?php if(!empty(get_sub_field('pro_blank'))){ ?>
                            <p><span class="material-icons">done</span><?php echo get_sub_field('pro_blank'); ?></p>
                        <?php } ?>
                        </div>
                        <div class="connection-type-providers-cons">
                        <?php if(!empty(get_sub_field('con_blank'))){ ?>
                            <p><span class="material-icons">close</span><?php echo get_sub_field('con_blank'); ?></p>
                        <?php } ?>
                        </div>
                    </div>
                    <?php
                    $select_providers = get_sub_field('select_provider');
                    if( $select_providers ): ?>
                    <ul class="connection-type-providers-selection">
                        <?php foreach( $select_providers as $provider ): 
                        $permalink = get_permalink( $provider->ID );
                        $title = get_the_title( $provider->ID );
                        ?>
                        <li><a href="<?php echo $permalink; ?>"><?php echo $title; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </div>
</section>