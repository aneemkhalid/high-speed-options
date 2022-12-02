<?php

/**
 * Provider Plan Details Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

 $providerId = get_the_ID();
 $rand = rand();
 // CTA
 $buyer_id = get_field('buyer', $providerId);
 $partner = get_field('partner', $providerId);
//echo "<pre>"; print_r($buyer_id); echo "</pre>";
$campaign = get_field( 'campaign', $buyer_id );
  //echo "<pre>"; print_r($campaign); echo "</pre>";
  foreach($campaign as $key => $camp) {
      $type_of_partnership = $camp['type_of_partnership'];
      if($camp['campaign_name'] == $providerId){
          if($type_of_partnership == 'call_center'){
              $cta_text = '<span class="material-icons">call</span>'.$camp['call_center'];
              $cta_link = 'tel:'.$camp['call_center'];
          }else{
              $cta_text = ($partner) ? 'Order Online' : 'View Plans';
              $cta_link = $camp['digital_tracking_link'];
              $target = 'target="blank"';
          }
      }
  }
$planTiles = get_field('provider_plan_tiles');
//echo "<pre>"; print_r($planTiles); echo "</pre>";
foreach($planTiles as $planTile) {  
    $productType[] = $planTile['plan_tile']['select_product_type'];
}
if(get_field('provider_plan_detail_toggle')) {
?>
<section id="plans" class="row-full provider-plan-details-wrap">
    <div class="container">
        <ul class="nav nav-tabs" id="provider-plan-details-Tab" role="tablist">
            <?php if (in_array("internet", $productType)) { ?>
            <li class="nav-item">
                <a class="nav-link active" id="internet-tab" data-toggle="tab" href="#internet" role="tab"
                    aria-controls="internet" aria-selected="true">Internet</a>
            </li>
            <?php } 
            if (in_array("tv", $productType)) {
            ?>
            <li class="nav-item">
                <a class="nav-link" id="tv-tab" data-toggle="tab" href="#tv" role="tab" aria-controls="tv"
                    aria-selected="false">TV</a>
            </li>
            <?php } 
            if (in_array("bundle", $productType)) {
            ?>
            <li class="nav-item">
                <a class="nav-link" id="bundle-tab" data-toggle="tab" href="#bundle" role="tab" aria-controls="bundle"
                    aria-selected="false">Bundles</a>
            </li>
            <?php } ?>
        </ul>
        <div class="tab-content" id="provider-plan-details-Tab-Content">
        <?php if (in_array("internet", $productType)) { ?>
            <div class="tab-pane fade show active" id="internet" role="tabpanel" aria-labelledby="internet-tab">
                <div class="provider-plan-tile-main-wrap">

                    <?php
                    foreach($planTiles as $planTile):

                    $providerOutboundClick = dataLayerOutboundLinkClick( $providerId, $planTile['plan_tile']['select_product_type'], $cta_link );

                    if($planTile['plan_tile']['select_product_type'] == 'internet'):
                    ?>
                    <div class="provider-plan-tile-wrap">
                        <div class="provider-plan-tile">
                            <?php if($planTile['plan_tile']['superlative_banner'] != ''): ?>
                            <div class="superlative-banner">
                                <?php echo $planTile['plan_tile']['superlative_banner']; ?>
                            </div>
                            <?php endif; ?>
                            <img class="logo" src="<?php echo get_field('logo', $providerId); ?>" alt="logo">
                            <h4><?php echo $planTile['plan_tile']['plan_title']; ?></h4>
                            <h3><?php echo $planTile['plan_tile']['price']; ?> <span>/mo.</span></h3>
                            <span
                                class="legal-pricing-text"><?php echo $planTile['plan_tile']['legal_pricing_text']; ?></span>
                            <div class="feature-plan-stats-wrap">
                                <?php foreach($planTile['plan_tile']['feature_plan_columns'] as $featurePlan): ?>
                                <div class="feature-plan-stats">
                                    <?php if($featurePlan['header_options'] == 'Other'){ ?>
                                    <span><?php echo $featurePlan['custom_header_option']; ?></span>
                                    <?php } else { ?>
                                    <span><?php echo $featurePlan['header_options']; ?></span>
                                    <?php } ?>
                                    <h5><?php echo $featurePlan['header_text']; ?></h5>
                                </div>
                                <?php 
                                // End loop.
                                endforeach;
                                ?>
                            </div>
                            <ul>
                                <?php foreach($planTile['plan_tile']['bullet_points'] as $bullet_point): ?>
                                <li><?php echo $bullet_point['bullet_text']; ?></li>
                                <?php 
                                // End loop.
                                endforeach;
                                ?>
                            </ul>
                            <div class="blue-btn">
                                <a class="cta-btn" href="<?php echo $cta_link; ?>" onclick="<?php echo $providerOutboundClick; ?>"
                                    <?php echo $target; ?>><?php echo $cta_text; ?></a>
                            </div>
                            <div class="check-availability">
                                <a href="#" id="internet-popup" class="zip-popup-btn" data-prodtype="internet"
                                    data-toggle="modal" data-target="#zipPopupModal-<?php echo $rand; ?>">Check
                                    Availability</a>
                            </div>
                            <p><?php echo $planTile['plan_tile']['terms_&_conditions']; ?> </p>
                        </div>
                    </div>
                    <?php
                    endif;
                    // End loop.
                    endforeach;
                    ?>
                </div>
                <p><?php the_field('internet_plan_tiles_t&c'); ?></p>
            </div>
            <?php } 
            if (in_array("tv", $productType)) {
            ?>
            <div class="tab-pane fade" id="tv" role="tabpanel" aria-labelledby="tv-tab">
                <div class="provider-plan-tile-main-wrap">
                    <?php
                    foreach($planTiles as $planTile):
                    if($planTile['plan_tile']['select_product_type'] == 'tv'):
                    ?>
                    <div class="provider-plan-tile-wrap">
                        <div class="provider-plan-tile">
                            <?php if($planTile['plan_tile']['superlative_banner'] != ''): ?>
                            <div class="superlative-banner">
                                <?php echo $planTile['plan_tile']['superlative_banner']; ?>
                            </div>
                            <?php endif; ?>
                            <img class="logo" src="<?php echo get_field('logo', $providerId); ?>" alt="logo">

                            <h4><?php echo $planTile['plan_tile']['plan_title']; ?></h4>
                            <h3><?php echo $planTile['plan_tile']['price']; ?><span>/mo.</span></h3>
                            <span
                                class="legal-pricing-text"><?php echo $planTile['plan_tile']['legal_pricing_text']; ?></span>
                            <div class="feature-plan-stats-wrap">
                                <?php foreach($planTile['plan_tile']['feature_plan_columns'] as $featurePlan): ?>
                                <div class="feature-plan-stats">
                                    <?php if($featurePlan['header_options'] == 'Other'){ ?>
                                    <span><?php echo $featurePlan['custom_header_option']; ?></span>
                                    <?php } else { ?>
                                    <span><?php echo $featurePlan['header_options']; ?></span>
                                    <?php } ?>
                                    <h5><?php echo $featurePlan['header_text']; ?></h5>
                                </div>
                                <?php 
                                // End loop.
                                endforeach;
                                ?>
                            </div>
                            <ul>
                                <?php foreach($planTile['plan_tile']['bullet_points'] as $bullet_point): ?>
                                <li><?php echo $bullet_point['bullet_text']; ?></li>
                                <?php 
                                // End loop.
                                endforeach;
                                ?>
                            </ul>
                            <div class="blue-btn">
                                <a class="cta-btn" href="<?php echo $cta_link; ?>" onclick="<?php echo $providerOutboundClick; ?>"
                                    <?php echo $target; ?>><?php echo $cta_text; ?></a>
                            </div>
                            <div class="check-availability">
                                <a href="#" id="tv-popup" class="zip-popup-btn" data-prodtype="tv" data-toggle="modal"
                                    data-target="#zipPopupModal-<?php echo $rand; ?>">Check Availability</a>
                            </div>
                            <p><?php echo $planTile['plan_tile']['terms_&_conditions']; ?> </p>
                        </div>
                    </div>
                    <?php
                    endif;
                    // End loop.
                    endforeach;
                    ?>
                </div>
                <p><?php the_field('tv_plan_tiles_t&c'); ?></p>
            </div>
            <?php }
             if (in_array("bundle", $productType)) {
            ?>
            <div class="tab-pane fade" id="bundle" role="tabpanel" aria-labelledby="bundle-tab">
                <div class="provider-plan-tile-main-wrap">
                    <?php
                    foreach($planTiles as $planTile):
                    if($planTile['plan_tile']['select_product_type'] == 'bundle'):
                    ?>
                    <div class="provider-plan-tile-wrap">
                        <div class="provider-plan-tile">
                            <?php 
                            if($planTile['plan_tile']['superlative_banner'] != ''):
                            ?>
                            <div class="superlative-banner">
                                <?php echo $planTile['plan_tile']['superlative_banner']; ?>
                            </div>
                            <?php endif; ?>
                            <img class="logo" src="<?php echo get_field('logo', $providerId); ?>" alt="logo">

                            <h4><?php echo $planTile['plan_tile']['plan_title']; ?></h4>
                            <h3><?php echo $planTile['plan_tile']['price']; ?> <span>/mo.</span></h3>
                            <span
                                class="legal-pricing-text"><?php echo $planTile['plan_tile']['legal_pricing_text']; ?></span>
                            <div class="feature-plan-stats-wrap">
                                <?php foreach($planTile['plan_tile']['feature_plan_columns'] as $featurePlan): ?>
                                <div class="feature-plan-stats">
                                    <?php if($featurePlan['header_options'] == 'Other'){ ?>
                                    <span><?php echo $featurePlan['custom_header_option']; ?></span>
                                    <?php } else { ?>
                                    <span><?php echo $featurePlan['header_options']; ?></span>
                                    <?php } ?>
                                    <h5><?php echo $featurePlan['header_text']; ?></h5>
                                </div>
                                <?php 
                                // End loop.
                                endforeach;
                                ?>
                            </div>
                            <ul>
                                <?php foreach($planTile['plan_tile']['bullet_points'] as $bullet_point): ?>
                                <li><?php echo $bullet_point['bullet_text']; ?></li>
                                <?php 
                            // End loop.
                            endforeach;
                            ?>
                            </ul>
                            <div class="blue-btn">
                                <a class="cta-btn" href="<?php echo $cta_link; ?>" onclick="<?php echo $providerOutboundClick; ?>"
                                    <?php echo $target; ?>><?php echo $cta_text; ?></a>
                            </div>
                            <div class="check-availability">
                                <a href="#" id="bundle-popup" class="zip-popup-btn" data-prodtype="bundle"
                                    data-toggle="modal" data-target="#zipPopupModal-<?php echo $rand; ?>">Check
                                    Availability</a>
                            </div>
                            <p><?php echo $planTile['plan_tile']['terms_&_conditions']; ?> </p>
                        </div>
                    </div>
                    <?php
                    endif;
                    // End loop.
                    endforeach;
                    ?>
                </div>
                <p><?php the_field('bundle_plan_tiles_t&c'); ?></p>
            </div>
            <?php } ?>
        </div>

    </div>
</section>
<?php 
require get_theme_file_path( '/template-parts/zip-search-popup.php' );
}
?>