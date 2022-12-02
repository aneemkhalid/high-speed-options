<?php
$connections = get_field('connection_types');

global $post;
$id = $post->ID;

$partner = get_field('partner', $id);


$check = get_stylesheet_directory_uri() . '/images/check.svg';

//CTA Logic
if($partner){
	$buyer_id = get_field('buyer', $id);
	$campaign = get_field( "campaign", $buyer_id );

	foreach($campaign as $key => $camp) {
		$type_of_partnership = $camp['type_of_partnership'];
		if($camp['campaign_name'] == $id){
			// echo $camp['call_center'];
			if($camp['call_center']){
                $cta_phone = [
                    'text' => '<span class="material-icons">call</span>'.$camp['call_center'],
                    'link' => 'tel:'.$camp['call_center'],
                    'target' => '',
                    'class' => '',
                ];
                $cta = $cta_phone['link'];
			}
            if($camp['digital_tracking_link']) {
                $cta_order = [
                    'text' => 'Order Online',
                    'link' => $camp['digital_tracking_link'],
                    'target' => '_blank',
                    'class' => 'digital',
                ];

                $cta = $cta_order['link'];
			}
		}
		
	}			
}
else{
    // $cta_phone = [
    //     'text' => 'View Plans',
    //     'link' => get_field('brands_website_url', $id),
    //     'target' => '_blank',
    //     'class' => 'plan',
    // ];
    // $cta = $cta_phone['link'];
    
}

$providerOutboundClick = dataLayerOutboundLinkClick( $id, 'Provider Plans', $cta );


?>

<section class="provider-plan-connections-block row-full">
    <div class="connections-container">
        <?php foreach($connections as $connection) :
            if($connection['plans']) : 
                $has_prom = false;
                $has_prom_disclaim = false;
                //Check to see if any plans has a promotion
                foreach($connection['plans'] as $plan) {
                    if($plan['promotion']) {
                        $has_prom = true;
                    }
                    if($plan['promotion_disclaimer']) {
                        $has_prom_disclaim = true;
                    }
                }  
            ?>
            <div class="connection-container <?php if($has_prom) { echo 'has-promotions'; } ?>">
                <div class="container">
                    <div class="header-container">
                        <?php $icon = ($connection['icon'] == 'other') ? $connection['custom_icon'] : get_template_directory_uri() . '/images/connections/ss-'. $connection['icon'] . '.svg'; ?>
                        <img src="<?php echo $icon ?>" alt="<?php echo $connection['icon'] ?>" height="40" width="40">
                        <h2 id="<?php echo $connection['anchor_link'] ?>"><?php echo $connection['header'] ?></h2>
                    </div>

                    <div class="plans-container plans-<?php echo count($connection['plans']); if($has_prom && !$has_prom_disclaim) { echo ' has-promotions'; } if($has_prom && $has_prom_disclaim) { echo ' has-promotions-disclaim'; } ?> ">
                        <?php foreach($connection['plans'] as $plan) : ?>
                            <div class="plan-item <?php if($plan['promotion']) { echo 'has-promo'; } ?>">
                                <div class="top-container">
                                    <?php if($prom = $plan['promotion']) : ?>
                                        <div class="promotion-container">
                                            <h5><?php echo $plan['promotion_text'] ?></h5>  
                                                <?php if($plan['promotion_disclaimer_link_or_text'] == 'link'): ?>
                                                    <?php if($plan['promotion_disclaimer']) : ?>
                                                        <a href="<?php echo $plan['promotion_disclaimer']['url'] ?>"><?php echo $plan['promotion_disclaimer']['title'] ?></a>
                                                    <?php endif; ?>
                                                <?php elseif($plan['promotion_disclaimer_link_or_text'] == 'text'): ?>
                                                    <?php if($plan['promotion_disclaimer_text']): ?>
                                                        <p><?php echo $plan['promotion_disclaimer_text']; ?></p>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            
                                        </div>
                                    <?php endif ?>
                                    <div class="main-container <?php if($prom) { echo 'has-promo'; }?>">
                                        <div class="main-width">
                                            <div class="top">
                                                <div class="title-container">
                                                    <h5 class="title"><?php echo $plan['title'] ?></h5>
                                                    <?php if($plan['show_starting']) : ?>
                                                        <div class="starting">starting at</div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="price">
                                                    <h2>$<?php echo $plan['price'] ?><span class="month"><?php echo $plan['post_price']; ?></span></h2>
                                                </div>
                                                <?php if($price_disclaim = $plan['pricing_disclaimer']) : ?>
                                                    <div class="pricing-disclaimer">
                                                        <?php echo $price_disclaim; ?>
                                                    </div>
                                                <?php endif ?>
                                            </div>
                                            <?php if($data = $plan['data_points']) : ?>
                                                <div class="data-container">
                                                    <div class="data-points data-<?php echo count($data); ?>">
                                                    <?php foreach($data as $item) : 
                                                        $title = ($head = $item['custom_header']) ? $head : $item['header']; 
                                                    ?>
                                                        <div class="data-item">
                                                            <h6><?php echo $title ?></h6>
                                                            <div class="value"><?php echo $item['value'] ?> <span><?php echo $item['unit']?></span></div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                    </div>
                                                    <?php if($plan['data_disclaimer']) : ?>
                                                        <div class="data-disclaimer">
                                                            <?php echo $plan['data_disclaimer']; ?>
                                                        </div>      
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if($bullets = $plan['bullets']) : ?>
                                                <div class="bullets-container">
                                                    <?php foreach($bullets as $item) : ?>
                                                        <div class="bullet-item">
                                                            <div class="icon-container">
                                                                <img src="<?php echo $check ?>" alt="check icon" height="20" width="20">
                                                            </div>
                                                            <div class="like-content">
                                                                <?php echo $item['bullet'] ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="cta-container">
                                                <?php
                                                if($partner){
                                                $format = '<a href="%s" target="%s" class="cta_btn %s" onclick="'.$providerOutboundClick .'">%s</a>';
                                                switch($plan['cta']) : 
                                                    case 'Telephone':
                                                        if($cta_phone) {
                                                            echo sprintf($format, $cta_phone['link'], $cta_phone['target'], $cta_phone['class'], $cta_phone['text']);
                                                        }
                                                    break;
                                                    case 'Order Online':
                                                        if($cta_order) {
                                                            echo sprintf($format, $cta_order['link'], $cta_order['target'], $cta_order['class'], $cta_order['text']);
                                                        }
                                                        else {
                                                            echo sprintf($format, $cta_phone['link'], $cta_phone['target'], $cta_phone['class'], $cta_phone['text']);
                                                        }
                                                    break;
                                                    case 'Both':
                                                        if($cta_phone && $cta_order) {
                                                            echo sprintf($format, $cta_phone['link'], $cta_phone['target'], $cta_phone['class'], $cta_phone['text']);
                                                            echo sprintf($format, $cta_order['link'], $cta_order['target'], $cta_order['class'], $cta_order['text']);
                                                        }
                                                        else {
                                                            echo sprintf($format, $cta_phone['link'], $cta_phone['target'], $cta_phone['class'], $cta_phone['text']);
                                                        }
                                                    break;
                                                endswitch; 

                                                } else {
                                                    $cta_text = 'Check Availability';
                                                    $cta_link = '#';
                                                    $rand = rand();
                                                    $data_att = 'data-toggle="modal" data-target="#zipPopupModal-'.$rand.'"';
                                                    $zip_popup_class = 'zip-popup-btn';
                                                    require get_theme_file_path( '/template-parts/zip-search-popup.php' );
                                                    echo '<a href="'.$cta_link.'" class="cta_btn '.$zip_popup_class.'" target="_blank" '.$data_att.'>'.$cta_text.'</a>';
                                                }
                                                ?>
                                            </div>
                                            <?php if(!$partner){ ?>
                                            <div class="mt-3 top-disclaimer">
                                                <p class="mb-0"><?php the_title(); ?> may not be in your area. Check availability to see what's near you.</p>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php if($disclaim = $plan['legal_disclaimer']) : ?>
                                    <div class="plan-disclaim">
                                        <?php echo $disclaim; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if($plan_disclaim = $connection['plans_disclaimer']) : ?>
                        <div class="plans-disclaim">
                            <?php echo $plan_disclaim; ?>
                        </div>
                    <?php endif; ?>
                    <?php endif; ?> <!-- END CONNECTION PLANS -->
                </div>

                <div class="features-parent">
                    <div class="features-container"> <!-- BEGIN FEATURED ITEMS -->
                        <div class="container">
                            <div class="feature-bg">
                                <div class="header">
                                    <div class="title-container">
                                        <div class="pretitle">Features</div>
                                        <h2><?php echo $connection['features']['header'] ?></h2>
                                    </div>
                                </div>

                                <div class="items-container">
                                    <?php foreach($connection['features']['items'] as $item) : 
                                        $has_descrip = false;
                                        //Check to see if any plans has a promotion
                                        if($item['description']) {
                                            $has_descrip = true;
                                        }
                                    ?>
                                        <div class="feature-item <?php if($has_descrip) { echo 'has-descrip'; } ?>">
                                            <div class="icon-container">
                                                <img src="<?php echo $item['icon']['url'] ?>" alt="<?php echo $item['icon']['alt'] ?>" height="40" width="40">
                                            </div>
                                            <h4><?php echo $item['subheader'] ?></h4>
                                            <?php if($descrip = $item['description']) : ?>
                                                <div class="descrip">
                                                    <?php echo $descrip ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div> <!-- END FEATURED ITEMS -->
                    <?php if($feat_disclaim = $connection['features']['legal_text']) : ?>
                        <div class="container">
                            <div class="feature-disclaim">
                                <?php echo $feat_disclaim; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
