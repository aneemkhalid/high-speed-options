<div class="make-money home-providers-block">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-lg-5">
                <div class="make-money-content">
                    <h3><?php echo get_field('title'); ?></h3>
                    <div>
                        <?php echo get_field('content'); ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-md-12">
                <div class="providers-list">
                <?php
                    $providers = get_field('select_providers');
                    if( $providers ): ?>
                    <ul>
                        <?php foreach( $providers as $post ):
                        setup_postdata($post); 
                        $id = $post->ID;
                        ?>
                        <li>
                            <a href="<?php the_permalink($id); ?>">

                                <img src="<?php echo get_field('logo', $id); ?>" alt="<?php echo get_the_title($id) ?>" width="120" height="50">
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php 
                    wp_reset_postdata(); ?>
                <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>