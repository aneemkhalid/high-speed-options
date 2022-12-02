<?php

/**
 * Connetion Type Details Card Block Template.
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
             $cta_text = 'View Plans';
             $cta_link = $camp['digital_tracking_link'];
             $target = 'target="blank"';
         }
     }
 }
$connectionTypeDetails = get_field('connection_type_detail_tiles');
//echo "<pre>"; print_r($connectionTypeDetails); echo "</pre>";
if(get_field('connection_type_detail_toggle')) {
?>
<section id="plans" class="row-full connection-type-details-card-wrap">
    <div class="container">
        <div class="provider-plan-tile-main-wrap connection-type-details-card">
        <?php foreach($connectionTypeDetails as $connectionTypeDetail): ?>
            <div class="provider-plan-tile-wrap ">
                <div class="provider-plan-tile">
                <?php if($connectionTypeDetail['connection_type_detail_tile']['superlative_banner'] != ''): ?>
                        <div class="superlative-banner">
                        <?php echo $connectionTypeDetail['connection_type_detail_tile']['superlative_banner']; ?>
                        </div>
                        <?php endif; ?>
                        <?php $image = $connectionTypeDetail['connection_type_detail_tile']['connection_type_image']; 
                            if($connectionTypeDetail['connection_type_detail_tile']['select_connection_type'] == "other") {
                                $imageUrl = $image;
                            } else {
                                $imageUrl = get_template_directory_uri() . '/images/' . $connectionTypeDetail['connection_type_detail_tile']['select_connection_type'] . '_icon.png';
                            }
                        
                        ?>
                    <img class="connection-icon" src="<?php echo $imageUrl; ?>" alt="connection icon">
                    <h3><?php echo $connectionTypeDetail['connection_type_detail_tile']['tile_title']; ?></h3>
                    <span class="disclaimer-text"><?php echo $connectionTypeDetail['connection_type_detail_tile']['connection_type_description']; ?></span>
                    <ul>

                        <?php foreach($connectionTypeDetail['connection_type_detail_tile']['bullet_points'] as $bullet_point): ?>
                            <li><?php echo $bullet_point['bullet_text']; ?></li> 
                        <?php 
                        // End loop.
                        endforeach;
                        ?> 
                    </ul>
                    <div class="blue-btn">
                            <a class="cta-btn" href="<?php echo $cta_link; ?>" <?php echo $target; ?>><?php echo $cta_text; ?></a>
                    </div>
                    <div class="check-availability">
                        <a href="#" id="internet-popup" class="zip-popup-btn" data-prodtype="internet" data-toggle="modal" data-target="#zipPopupModal-<?php echo $rand; ?>">Check Availability</a>
                    </div>
                    <p><?php echo $connectionTypeDetail['connection_type_detail_tile']['connection_type_disclaimer']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <p><?php the_field('connection_type_details_t&c');  ?></p>
    </div>
</section>
<?php 
require get_theme_file_path( '/template-parts/zip-search-popup.php' );
}
?>