<?php
/**
 * Template part for displaying provider in single-provider.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package HSO
 */

$logo = get_field('logo');
$partner = get_field('partner');
$hide_cta = get_field('hide_cta');

$title = ($overrideTitle = get_field('main_title')) ? $overrideTitle : get_the_title();
$overview = get_field('provider_overview');
$hero_img = ($heroImg = get_field('hero_image')) ? $heroImg : get_template_directory_uri() . '/images/provider-hero.png';

$blue_banner = get_field('blue_banner');

$internet_check = get_field('internet_check');
$tv_check = get_field('tv_check');
$bundles_check = get_field('bundles_check');




if( get_field('featured_boxes') ) {
    
$featured_boxes = get_field('featured_boxes'); 
    
$tv = $featured_boxes['tv'];
$internet = $featured_boxes['internet'];
$bundles = $featured_boxes['bundles'];
$phone = $featured_boxes['phone'];
$security_tools = $featured_boxes['security_tools'];
$order_list = $featured_boxes['order'];	
$source = $featured_boxes['source'];
    
}    

$cta_text = '';
$cta_link = '';
$cta_text2 = '';
$cta_link2 = '';

if($partner){
	$buyer_id = get_field('buyer');
	$campaign = get_field( "campaign", $buyer_id );
	$target = $target2 = '';
	foreach($campaign as $key => $camp) {
		$type_of_partnership = $camp['type_of_partnership'];
		if($camp['campaign_name'] == get_the_ID()){

			if($type_of_partnership == 'call_center'){
				$cta_text = '<span class="material-icons">call</span>'.$camp['call_center'];
				$cta_link = 'tel:'.$camp['call_center'];
                
                $variantProvider = [
                        'text' => 'Call'
                ];
		
			}elseif($type_of_partnership == 'digital_link'){
				$cta_text = 'Order Online';
				$cta_link = $camp['digital_tracking_link'];
				$target = 'target="_blank"';
                
                $variantProvider = [
                        'text' => 'Order Online',
                ];
			} else {

				if ($camp['primary_conversion_method'] == 'call_center'){
					$cta_text = '<span class="material-icons">call</span>'.$camp['call_center'];
					$cta_text2 = 'Order Online';
					$cta_link = 'tel:'.$camp['call_center'];
					$cta_link2 = $camp['digital_tracking_link'];
					$variantProvider = [
	                    'text' => 'Call'
	                ];
	                $variantProvider2 = [
                        'text' => 'Order Online',
                        'url' => $cta_link2
                	];
                	$target2 = 'target="_blank"';
				} else {
					$cta_text = 'Order Online';
					$cta_text2 = '<span class="material-icons">call</span>'.$camp['call_center'];
					$cta_link = $camp['digital_tracking_link'];
					$cta_link2 = 'tel:'.$camp['call_center'];
					$variantProvider = [
                        'text' => 'Order Online',
                        'url' => $cta_link
                	];
                	$variantProvider2 = [
	                    'text' => 'Call'
	                ];
	                $target = 'target="_blank"';
				}
			}
		}
		
	}			
}

//Provider Plans Info
$show_plans = get_field('show_plans_page');
$plan_page = isset($_GET['plans']);
$plan_faq = get_field('separate_faqs');

$plan_link = get_the_permalink() . '?plans=show';

//dataLayer info
if ($cta_link){
	$providerOutboundClick = dataLayerOutboundLinkClick( get_the_id(), "Internet", $cta_link );
}
if ($cta_link2){
	$providerOutboundClick2 = dataLayerOutboundLinkClick( get_the_id(), "Internet", $cta_link2 );
}

$providersOnLoad =  dataLayerProductDetail('Providers Page', get_the_title().' Providers', get_the_ID(), get_the_title(), 'Provider', $variantProvider['text'] );


?>
 
  
  
<section class="provider-hero">

	<div class="container">
		<?php get_template_part( 'template-parts/breadcrumbs', null, array( ) ); ?>
	</div>

	<?php if($logo) : ?>
		<div class="logo-mobile">
			<div class="container">
				<img src="<?php echo $logo ?>" alt="<?php echo get_the_title() ?>" width="170" height="55">
			</div>
		</div>
	<?php endif; ?>

	<div class="container">

		<div class="top-content d-flex flex-column">

			<div class="nav-container order-1 order-sm-0">
				<div class="nav-items">
					<a href="<?php echo get_the_permalink() ?>" class="<?php if(!$plan_page) : echo 'active'; endif; ?>">Overview</a>
					<?php if($show_plans) : ?>
						<a href="#" class="plans-page <?php if($plan_page) : echo 'active'; endif; ?>">Plans</a>
					<?php endif; ?>
				</div>
				<?php if($plan_page) : ?>
					<div class="plan-logo">
						<?php if($logo) : ?>
							<div class="logo-container">
								<img src="<?php echo $logo ?>" alt="<?php echo get_the_title() ?>" height="50" width="150">
							</div>
							<div class="gray-bar"></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
			
			<?php if($blue_banner && $blue_banner['display'] !== 'none') : ?>
			<section class="blue-banner-block order-0 mt-5 order-sm-1 mt-sm-0">
				<div>
					<div class="banner-outside">
						<div class="content text-center">
							<?php 
								if($blue_banner['display'] == 'separate' && $plan_page) : 
									echo $blue_banner['banner_plans'];
								else :
									echo $blue_banner['content'];
								endif;
							?>
						</div>
					</div>
				</div>
			</section>
			<?php endif; ?>
		</div>

		<?php if(!$plan_page) : ?>
			<div class="hero-content">

				<div class="img-container">
					<img src="<?php echo $hero_img  ?>" alt="provider hero img" width="470" height="400">
					<div class="dots-container">
						<svg width="100%" height="100%"><pattern id="a" x="0" y="0" width="14" height="14" patternUnits="userSpaceOnUse" patternContentUnits="userSpaceOnUse"><circle cx="2" cy="2" r="2" fill="#D1D3D4"/></pattern><rect width="100%" height="100%" fill="url(#a)"/></svg>
					</div>
				</div>

				<div class="content-container">
					<?php if($logo) : ?>
						<div class="logo-container">
							<img src="<?php echo $logo ?>" alt="<?php echo get_the_title() ?>" width="170" height="55">
						</div>
					<?php endif; ?>
					<h1><?php echo $title ?></h1>
					<?php if($overview) : ?>
						<div class="content">
							<?php echo $overview ?>
						</div>
					<?php endif; ?>

					<?php if(!$hide_cta) : ?>
						<div class="btn-container">
							<?php if(!empty($cta_text)) : ?>
								<a href="<?php echo $cta_link ?>" class="cta_btn font-weight-bold" <?php echo $target ?> onClick="<?php echo $providerOutboundClick ?>"><?php echo $cta_text ?></a>
							<?php endif; ?>
							<?php if($cta_text2) : ?>
								<a href="<?php echo $cta_link2 ?>" class="plans-btn font-weight-bold" <?php echo $target2 ?> onClick="<?php echo $providerOutboundClick2 ?>"><?php echo $cta_text2 ?></a>
							<?php elseif($show_plans) : ?>
								<a href="#" class="plans-page plans-btn">View Plans</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>

			</div>
		<?php endif; ?>
	</div>

</section>

<section>
	<div class="container">
		<?php the_content(); ?>
	</div>
</section>

<?php 
	if($plan_faq && $plan_page) : 
		get_template_part('/template-parts/faq', null, ['unique' => $plan_faq]);
	else: 
		get_template_part('/template-parts/faq', null, []);
	endif;
?>

<?php get_template_part('/template-parts/related_posts', null, ['container' => true]); ?>

<script>
<?php echo $providersOnLoad ?>
</script>
