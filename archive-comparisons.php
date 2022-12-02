<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * Template Name: Comparison Aggregate Page
 * @package HSO
 */

get_header();

use ZipSearch\ProviderSearchController as ProviderSearchController;
$type = ProviderSearchController::getZipType();
?>
<section class="home-hero-block comparison-aggregate-hero text-md-center">
	<div class="container pt-0 pb-0">
		<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'exclude_advertiser_disclosure_link' => false ) ); ?>
	</div>
	<div class="container">
		<div class="bg-container">
			<h1 class="mb-2" >Compare Internet Providers</h1>
			<p>Use the dropdown here to select providers to compare</p>
			<div class="comparison-providers-box text-left d-flex justify-content-between">
				<div class="comparison-providers-box-inner d-flex justify-content-between">
					<div class="select-box" id="select-provider-box1">
						<div class="init">Provider 1</div>
						<span class="material-icons">expand_more</span>
						<div class="inner text-center">
							<ul class="list-unstyled">

								<div class="main-list">
								<?php 
								$ar_provider['id'] = [];
								$ar_provider['title'] = [];

								$args = array(
									'post_type' => 'comparisons',
									'post_status' => 'publish',
									'posts_per_page'=> -1,
									'orderby'  => 'date',
									'order' => 'DESC',
								);
									$providers = new WP_Query( $args );
									if ( $providers->have_posts() ) :
										while ( $providers->have_posts() ) : 
										$providers->the_post();
										$providerId1 = get_field('provider_1');
										$providerId2 = get_field('provider_2');

										if(!in_array($providerId1, $ar_provider['id'])){
											$ar_provider['id'][] = $providerId1;
											$ar_provider['title'][] = get_the_title($providerId1);
										}

										if(!in_array($providerId2, $ar_provider['id'])){
											$ar_provider['id'][] = $providerId2;
											$ar_provider['title'][] = get_the_title($providerId2);
										}

										//var_dump($ar_provider);
									endwhile;
									wp_reset_postdata();
									endif;
									for($i=0; $i<count($ar_provider['id']); $i++){ ?>
										<li data-value="<?php echo $ar_provider['id'][$i]; ?>"><span class="d-flex align-items-center justify-content-center"><?php echo $ar_provider['title'][$i]; ?></span></li>	
									<?php } ?>
								</div>
							</ul>
							<a href="/providers" class="see-more">See More Providers</a>
						</div>
					</div>
					<div class="select-box default" id="select-provider-box2">
						<div class="gif-loader">
							<img src="<?php echo get_template_directory_uri(); ?>/images/ajaxloader.gif" alt="">
						</div>
						<div class="init">Provider 2</div>
						<span class="material-icons">expand_more</span>
						<div class="inner text-center">
							<ul class="list">
								<div class="main-list">
									<!-- <li data-value="value 1">Option 1</li>
									<li data-value="value 2">Option 2</li>
									<li data-value="value 3">Option 3</li>
									<li data-value="value 1">Option 4</li>
									<li data-value="value 2">Option 5</li>
									<li data-value="value 3">Option 6</li> -->
								</div>
							</ul>
							<a href="/providers" class="see-more">See More Providers</a>
						</div>
					</div>
				</div>
				<a href="" class="cta_btn">Compare</a>
			</div>
		</div>
	</div>
    <div class="featured-container m-0 d-xl-block"></div>
</section>
<section class="all-comparisons-wrapper">
	<div class="container pr-xl-0 pl-xl-0">
		<div class="all-comparisons-inner">
			<h2 class="mb-4">All Comparisons</h2>
			<div class="row">
				<div class="col-lg-8">
                  <div class="comparison-posts mb-4 mb-lg-0">
					<div class="comparison-posts-inner pb-4"></div>
					<input type="hidden" class="compPageNumber" value="<?php echo $paged; ?>">
					<div class="comparisons-posts-btn-wrap text-center comparisons-loadmore"></div>
				  </div>
				</div>
				<div class="col-lg-4">
					<div class="comparisons-aggregate-sidebar pl-xl-3">
						<?php
						$speed_calculator_toggle = get_field('speed_calculator_toggle');
						$speed_calculator = get_field('speed_calculator');
						if( $speed_calculator_toggle ): ?>
						<div class="speed-calculator">
							<h4 class="mb-4">Tools</h4>
							<div class="speed-calculator-inner common-box">
								<div class="img-wrap p-2 text-center d-flex align-items-center justify-content-center">
									<img src="<?php echo esc_url( $speed_calculator['hmsdin_image']['url'] ); ?>">
								</div>
								<div class="content p-4">
									<h5 class="mb-3"><?php echo $speed_calculator['hmsdin_title']; ?></h5>
									<p class="mb-4"><?php echo $speed_calculator['hmsdin_description']; ?></p>
									<div class="text-center">
										<?php if(!empty($speed_calculator['hmsdin_button_link'])) : ?>
										<a class="view-link" href="<?php echo $speed_calculator['hmsdin_button_link']; ?>"><?php echo $speed_calculator['hmsdin_button_text']; ?></a>
										<?php else : ?>
										<a class="view-link" href="/how-much-internet-speed-do-i-need/"><?php echo $speed_calculator['hmsdin_button_text']; ?></a>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
						<?php endif; ?>
						<div class="resources">
							<h4>Resources</h4>
							<div class="resources-sidebar common-box pb-4">
								<?php
								$resource_posts = get_field('select_resource_article');
								if( $resource_posts ): ?>
								<div class="resources-inner pb-2">
									<?php foreach( $resource_posts as $post ): 
										setup_postdata($post);
									?>
									<a class="resource-posts d-flex align-items-center" href="<?php the_permalink(); ?>">
										<div class="content">
											<span class="mb-2 d-block"><?php the_date(); ?></span>
											<h5 class="mb-0"><?php the_title(); ?></h5>
										</div>
										<img src="<?php echo get_the_post_thumbnail_url(); ?>">
									</a>
									<?php endforeach; ?>
								</div>
								<?php wp_reset_postdata(); ?>
								<?php endif; ?>
								<div class="view-resources-link-wrap text-center">
									<a class="view-resources view-link" href="/resources/">View Resources</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="zip-search-wrap blue-bg-mobile mb-6">
	<div class="container pl-xl-0 pr-xl-0">
		<div class="blue-bg-desktop pl-0 pr-0">
			<div class="md-container mb-4 text-center">
				<h3>Find the best providers in your area</h3>
			</div>
			<form action="/zip-search" class="zip_search_form search_wrap" data-form="Search Banner">
				<div class="input-container d-flex align-items-center mb-0">
					<div class="icon-container d-flex align-items-center align-self-stretch">
						<span class="material-icons">search</span>
					</div>
					<input type="number" class="zip_search_input" id="zip" name="zip" minlength="5" maxlength="5" placeholder="Search by Zip Code" pattern="\d*"/>
					<input type="hidden" id="type" name="type" value="<?php echo $type; ?>">
					<button type="button" class="submit-zip cta_btn align-self-stretch mb-0">Search</button>
				</div>
			</form>	
		</div>
	</div>
</section>

<?php the_content(); ?>
<?php
get_footer();