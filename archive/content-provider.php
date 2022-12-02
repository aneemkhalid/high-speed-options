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

$internet_check = get_field('internet_check');
$tv_check = get_field('tv_check');
$bundles_check = get_field('bundles_check');

$featured_boxes = get_field('featured_boxes');

$tv = $featured_boxes['tv'];
$internet = $featured_boxes['internet'];
$bundles = $featured_boxes['bundles'];
$phone = $featured_boxes['phone'];
$security_tools = $featured_boxes['security_tools'];
$order_list = $featured_boxes['order'];	
$source = $featured_boxes['source'];	

$cta_text = '';
$cta_link = '';
$cta_text2 = '';
$cta_link2 = '';
if($partner){
	$buyer_id = get_field('buyer');
	$campaign = get_field( "campaign", $buyer_id );
	$target = '';
	foreach($campaign as $key => $camp) {
		$type_of_partnership = $camp['type_of_partnership'];
		if($camp['campaign_name'] == get_the_ID()){
			// echo $camp['call_center'];
			if($type_of_partnership == 'call_center'){
				$cta_text = '<span class="material-icons">call</span>'.$camp['call_center'];
				$cta_text2 = $camp['call_center'];
				$cta_link = 'tel:'.$camp['call_center'];
				$cta_link2 = 'tel:'.$camp['call_center'];
                
                $variantProvider = [
                        'text' => 'Call'
                ];
			}else{
				$cta_text = 'Order Online';
				$cta_link = $camp['digital_tracking_link'];
				$target = 'target="_blank"';
                
                $variantProvider = [
                        'text' => 'Order Online',
                        'url' => $cta_link
                ];
			}
		}
		
	}			
}else{
	$cta_text = 'View Plans';
	$cta_link = get_field('brands_website_url');
	$target = 'target="_blank"';
    
      $variantProvider = [
                        'text' => 'View Plans',
                        'url' => $cta_link
                ];
}



//dataLayer info




$providersAddToCart = dataLayerAddToCart(get_the_ID(), $variantProvider, "Provider" );



$providersViewPlansClick = dataLayerViewPlansClick(get_the_ID(), $variantProvider, "Provider" );

$providersCallsClick = dataLayerCallsClick(get_the_ID(), "Provider" );

if ($variantProvider['text'] === 'View Plans') {
   $providersAddToCart .= $providersViewPlansClick;
    
} else {
    $providersAddToCart .= $providersCallsClick;
}
$providersOnLoad =  dataLayerProductDetail('Providers Page', get_the_title().' Providers', get_the_ID(), get_the_title(), 'Provider', $variantProvider['text'] );



?>
 
  
  
<section class="banner">
	<div class="container">
		<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'has_banner' => true ) ); ?>
		<h1><?php the_field('main_title') ?></h1>
	</div>
</section>

<section class="providers_card">
	<div class="container">
		<div class="inner">
			<div class="img_wrap">
				<?php if($logo) echo '<img src="'.$logo.'" alt="'.get_the_title().'">' ?>
			</div>
			<?php
				// Check rows exists.
				if( have_rows('main_features') ):
					// Loop through rows.
					echo '<ul>';
					while( have_rows('main_features') ) : the_row();
						// Load sub field value.
						$feature = get_sub_field('feature');
						echo '<li>'.$feature.'</li>';
					// End loop.
					endwhile;
					echo '</ul>';
				endif;
			?>
			<?php if(!empty($cta_text)) echo '<a href="'.$cta_link.'" class="cta_btn" '.$target.' onClick="'. $providersAddToCart.'">'.$cta_text.'</a>'; ?>
			
		</div>
	</div>
</section>

<section class="providers_overview  common-style">
	<div class="container">
		<div class="border_heading">
			<h2><?php the_title(); ?> Overview</h2>
		</div>
		<?php the_field('provider_overview') ?>
		
		<?php if(get_field('featured_box_toggle')) { ?>
		<div id="plans" class="providers_features">
			<div class="row">
				
				<?php 
				$count_boxes = 0;
				foreach ($order_list as $key => $order) {
					$box = $featured_boxes[$order['value']];
					if($box[$order['value']]){ 
						$count_boxes++;
						?>
						<div class="col-xl-4 col-md-6 ">
							<div class="providers_box">
								<div class="img-wrap">
									<img src="<?php echo get_template_directory_uri() ?>/images/<?php echo $order['value']; ?>.svg" alt="<?php echo $order['label'] ?>">
								</div>
								<h4><?php  if(!empty($box['title'])){
										echo $box['title']; 
									}else{
										echo get_the_title().' '.$order['label'];
									} ?></h4>
								<ul>
									<?php 
										if($box['features']){
											foreach ($box['features'] as $key => $feature) {
												echo '<li>'.$feature['feature'].'</li>';
											}
										}
									?>
								</ul>
							</div>
						</div>
					<?php } 
				}
				?>
					</div>
					<div class="row">
					<?php
					$source_align_xl = $count_boxes*4;
					$source_align_md = $count_boxes*6;
					if ($source_align_xl >= 12){
						$source_align_xl = 12;
					}
					if ($source_align_md >= 12){
						$source_align_md = 12;
					}
					if ($source){
					    echo '<figcaption class="figcaption-source col-xl-'.$source_align_xl.' col-md-'.$source_align_md.' ">'.$source.'</figcaption>';
					}
				?>
			</div>
		</div>
		<?php } ?>
		<?php if(!empty($cta_text2)){ ?>
			<div class="call-details">
				<h3>Want to learn more? For complete plan details call
					<div>
						<?php echo '<a href="'.$cta_link2.'" onClick="'. $providersAddToCart.'">'.$cta_text2.'</a>'; ?>
					</div>
					</h3>
			</div>
		<?php } ?>

		<?php the_content(); ?>
	</div>
</section>



<?php 
if( have_rows('faqs') ): ?>
	<section class="faq">
		<div class="container">
			<h3><?php the_title() ?> FAQs</h3>
			<div id="accordion">
				<?php
					$counter = 1;
					// Loop through rows.
					while( have_rows('faqs') ) : the_row();

						// Load sub field value.
						$question = get_sub_field('question'); 
						$answer = get_sub_field('answer'); ?>

						<div class="card">
							<div class="card-header" id="heading<?php echo $counter ?>">
							<h4>
								<button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $counter ?>" aria-expanded="true" aria-controls="collapse<?php echo $counter ?>"><?php echo $question; ?><span class="material-icons">expand_less</span></button>
							</h4>
							</div>
							<div id="collapse<?php echo $counter ?>" class="collapse  show<?php //if($counter == 1) echo 'show'; ?>" aria-labelledby="heading<?php echo $counter ?>" data-parent="#accordion">
								<div class="card-body"><?php echo $answer; ?></div>
							</div>
						</div>

					<?php $counter++; endwhile;	?>
			
			</div>
		</div>	
	</section>
<?php endif; ?>

<?php get_template_part('/template-parts/related_posts', null, ['container' => true]); ?>

<script>
<?php echo $providersOnLoad ?>
</script>