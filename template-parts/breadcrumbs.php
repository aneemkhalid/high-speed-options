<?php 
if( !array_key_exists('exclude_advertiser_disclosure_link', $args) ) {
    $args['exclude_advertiser_disclosure_link'] = false;
}

if (array_key_exists('is_blog', $args) && $args['is_blog']):
?>
<div class="d-flex justify-content-between breadcrumbs-container flex-column flex-md-row is_blog">
    <nav aria-label="breadcrumb">
            <ol class="breadcrumbs-list d-flex align-items-center">
                <span>
                    <span>
                        <a href="<?php echo site_url() ?>">
                            <span class="material-icons">home</span>
                        </a> 
                        <span class="material-icons chevron">chevron_right</span> 
                        <a href="/resources">
                            Resources
                        </a> 
                        <span class="material-icons chevron">chevron_right</span>
                        <?php if (is_singular('post')): ?>
                        <a href="/resources/insights">
                            Insights
                        </a> 
                        <span class="material-icons chevron">chevron_right</span> 
                        <span class="breadcrumb_last" aria-current="page">Article</span>
                        <?php else: ?>
                            Insights
                        <?php endif; ?>
                    </span>
                </span>
            </ol>
    </nav>
    <div class="advertiser-disclosure-link-container">
        <?php if(!$args['exclude_advertiser_disclosure_link']): ?>
            <a href="<?php echo site_url('disclosure'); ?>" class="advertiser-disclosure-link">Advertiser Disclosure</a>
        <?php endif; ?>
    </div>
</div>
<?php elseif(array_key_exists('is_location', $args) && $args['is_location']): ?>
    <div class="d-flex justify-content-between breadcrumbs-container <?php if(array_key_exists('has_banner', $args) && $args['has_banner']) echo 'has-banner'; ?> <?php if(array_key_exists('is_blog', $args) && $args['is_blog']) echo 'is-blog'; ?>">
        <nav aria-label="breadcrumb">
                <ol class="breadcrumbs-list d-flex align-items-center">
                    <span>
                        <span>
                            <a href="<?php echo site_url() ?>">
                                <span class="material-icons">home</span>
                            </a> 
                            <span class="material-icons chevron">chevron_right</span>
                            <a href="<?php //echo $args['state_permalink']; ?>">
                                <?php echo $args['state']; ?>
                            </a>
                            <?php if($args['city']) : ?>
                            <span class="material-icons chevron">chevron_right</span> 
                            <span class="breadcrumb_last" aria-current="page"><?php echo $args['city']; ?></span>
                            <?php endif; ?>
                        </span>
                    </span>
                </ol>
        </nav>
        <div class="advertiser-disclosure-link-container">
            <?php if(!$args['exclude_advertiser_disclosure_link']): ?>
                <a href="<?php echo site_url('disclosure'); ?>" class="advertiser-disclosure-link">Advertiser Disclosure</a>
            <?php endif; ?>
        </div>
    </div>
    <?php elseif(is_singular('authors')): 
        $post_id = get_the_ID();
        ?>
        <div class="d-flex justify-content-between breadcrumbs-container <?php if(array_key_exists('has_banner', $args) && $args['has_banner']) echo 'has-banner'; ?> <?php if(array_key_exists('is_blog', $args) && $args['is_blog']) echo 'is-blog'; ?>">
        <nav aria-label="breadcrumb">
                <ol class="breadcrumbs-list d-flex align-items-center">
                    <span>
                        <span>
                            <a href="<?php echo site_url() ?>">
                                <span class="material-icons">home</span>
                            </a> 
                            <span class="material-icons chevron">chevron_right</span>
                            <a href="/about">
                                Authors
                            </a> 
                            <span class="material-icons chevron">chevron_right</span> 
                            <span class="breadcrumb_last" aria-current="page"><?php echo get_the_title($post_id); ?></span>
                        </span>
                    </span>
                </ol>
        </nav>
        <div class="advertiser-disclosure-link-container">
            <?php if(!$args['exclude_advertiser_disclosure_link']): ?>
                <a href="<?php echo site_url('disclosure'); ?>" class="advertiser-disclosure-link">Advertiser Disclosure</a>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
<div class="d-flex justify-content-between breadcrumbs-container <?php if(array_key_exists('has_banner', $args) && $args['has_banner']) echo 'has-banner'; ?> <?php if(array_key_exists('is_blog', $args) && $args['is_blog']) echo 'is-blog'; ?>">
    <nav aria-label="breadcrumb">
        <?php
            if ( function_exists('yoast_breadcrumb') && $post->post_name !== 'homepage') {
                yoast_breadcrumb( '<ol class="breadcrumbs-list d-flex align-items-center">','</ol>' );
            }
        ?>
    </nav>
    <div class="advertiser-disclosure-link-container">
        <?php if(!$args['exclude_advertiser_disclosure_link']): ?>
            <a href="<?php echo site_url('disclosure'); ?>" class="advertiser-disclosure-link">Advertiser Disclosure</a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>