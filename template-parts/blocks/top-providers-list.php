<?php
/**
 * Top Providers List Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

//top providers list
global $post;
$top_providers = get_field('top_providers', $post->ID);
if($top_providers){
    $top_prov_count = count($top_providers);
    if($list_title = get_field('providers_list_title', $post->ID)) {
        echo '<h2 class="pb-2">'.$list_title.'</h2>';
    }
    echo '<div class="top-providers-list">';
    foreach($top_providers as $key => $provider):

        // $margin_top = '';
        // if ($key !== 0 ){
        //     $margin_top = 'mt-5';
        // }
        $provider_id = $provider['provider'];
        $anchor_link = slugify(get_the_title($provider_id));
        $logo = get_field('logo', $provider_id);
        if ($provider['superlative']['superlative_icon'] == 'other'){
            $sup_icon = $provider['superlative']['icon_upload'];
            $sup_alt = 'top-providers';

        } else {
            $sup_icon = get_template_directory_uri() . '/images/'.$provider['superlative']['superlative_icon'].'.svg';
            $sup_alt = $provider['superlative']['superlative_icon'];
        }
        if (!$provider['provider_features']){
            $provider['provider_features'] = get_field('main_features', $provider_id);
        }
        $rand = rand();
        require get_theme_file_path( '/template-parts/zip-search-popup.php' );
        ?>
        <div id="<?php echo $anchor_link; ?>" class="top-providers-container">
            <h2 style="overflow: hidden; height: 0;" class="mb-0"><?php echo get_the_title($provider_id); ?></h2>
            <div class="top-providers-box border-radius-20">
                <div class="superlative-row d-flex justify-content-center align-items-center border-radius-top-20 p-3">
                    <img src="<?php echo $sup_icon ?>" alt="<?php echo $sup_alt; ?>" class="mr-3" width="30" height="30">
                    <p class="font-weight-bold mb-0"><?php echo $provider['superlative']['superlative_text']; ?></p>
                </div>
                <div class="d-flex align-items-center flex-md-row flex-column pt-3 pb-4 pl-4 pr-4 p-md-4">
                    <div class="col-12 col-md-1 top-providers-rank">
                        <h4 class="rank text-center mb-md-0 mx-auto"><?php echo $key+1; ?></h4>
                    </div>
                    <div class="col-md-3 mr-md-3 mb-4 mb-md-0">
                        <h4 class="rank text-center mb-3 mx-auto toc-content-tablet"><?php echo $key+1; ?></h4>
                        <img class="mx-auto d-block ml-lg-0" height="30" width="125" src="<?php echo $logo; ?>" alt="logo">
                    </div>
                    <div class="col-md features">
                        <ul class="ml-3 ml-md-0 mb-4 mb-md-0">
                        <?php foreach ($provider['provider_features'] as $feature){
                            echo '<li class="li-done mb-2">'.$feature['feature'].'</li>';
                        }
                        ?>
                        </ul>
                    </div>
                    <div class="col col-md pb-3 pb-md-0 text-md-right text-center">
                        <a href="#" class="cta_btn w-100 zip-popup-btn" data-toggle="modal" data-target="#zipPopupModal-<?php echo $rand; ?>">Check Availability</a>
                    </div>
                </div>
            </div>
            <div class="section-content">
                <?php if (isset($provider['legal_copy']) && $provider['legal_copy']): ?>
                    <div class="small-p mt-3"><?php echo $provider['legal_copy'] ?></div>
                <?php endif; ?>
                <?php if (isset($provider['section_header']) && $provider['section_header']): ?>
                    <h5 class=""><?php echo $provider['section_header']; ?></h5>
                <?php endif; ?>
                <?php if (isset($provider['section_text']) && $provider['section_text']): ?>
                    <div class="section-text"><?php echo $provider['section_text'] ?></div>
                <?php endif; ?>
            </div>
            <?php if (isset($provider['things_we_like']) && !empty($provider['things_we_like']) && isset($provider['things_to_consider']) && !empty($provider['things_to_consider'])): ?>
                <div class="row">
                    <div class="col-md pt-4 pros">
                    <h5>Things we like:</h5>    
                        <ul>
                        <?php foreach($provider['things_we_like'] as $like): ?>
                            <li><?php echo $like['item']; ?></li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="col-md pt-4 cons">
                    <h5>Things to consider:</h5>    
                        <ul>
                        <?php foreach($provider['things_to_consider'] as $consider): ?>
                            <li><?php echo $consider['item']; ?></li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                </div>  
            <?php endif; ?>
        </div>  

        <?php
    endforeach;
    echo '</div>';
}