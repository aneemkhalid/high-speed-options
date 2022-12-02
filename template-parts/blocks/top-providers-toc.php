<?php
/**
 * Top Providers TOC Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

//top providers TOC
global $post;
$top_providers = get_field('top_providers', $post->ID);
if(!empty($top_providers)){ ?>
<div class="pb-4">
    <?php
    echo '<h2 class="pb-2">'.get_field('top_providers_title', $post->ID).'</h2>';
    ?>
    <div class="top-providers-toc border-radius-20 p-4">
        <?php
            foreach($top_providers as $key => $provider){
                $provider_id = $provider['provider'];
                $logo = get_field('logo', $provider_id);
                $anchor_link = slugify(get_the_title($provider_id));
                ?>

                <div class="d-flex align-items-center pt-3 pb-3 pl-sm-2 pr-sm-2">
                    <div class="col-3 col-md-1">
                        <h4 class="rank text-center mb-0"><?php echo $key+1; ?></h4>
                    </div>
                    <div class="col-3 ml-md-2 toc-content-desktop">
                        <a href="#<?php echo $anchor_link; ?>">
                            <img class="" height="30" width="100" src="<?php echo $logo; ?>" alt="logo">
                        </a>
                    </div>
                    <div class="col-sm align-right toc-content-desktop">
                        <p class="font-weight-bold mb-0"><?php echo $provider['superlative']['superlative_text']; ?></p>
                    </div>
                    <div class="col toc-content-mobile">
                        <p class="font-weight-bold mb-2"><?php echo $provider['superlative']['superlative_text']; ?></p>
                        <img class="" height="30" width="100" src="<?php echo $logo; ?>" alt="logo">
                    </div>
                    <div class="col-sm text-right toc-content-desktop">
                        <a href="#<?php echo $anchor_link; ?>" class="cta_btn btn-outline btn-blue">Learn More</a>
                    </div>
                    <div class="col-2 col-sm-3 toc-content-mobile">
                        <a href="#<?php echo $anchor_link; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                </div>  
                <?php
            }
        ?>
    </div>
    <p class="small-p mt-3"><?php the_field('legal_copy', $post->ID, false); ?></p>
</div>  <?php
}