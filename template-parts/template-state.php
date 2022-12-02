<?php
/**
 * The template for displaying all single locations
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package HSO
 */

get_header();

global $city;
global $state;

// (isset($_GET['type']) && $_GET['type']) ? $type = $_GET['type'] : $type = 'internet';
// $internet_active='';$tv_active='';$bundle_active='';$internet_show='';$tv_show='';$bundle_show='';
// if ($type == 'internet'){
// 	$internet_active = 'active dataLayer-sent';
// 	$internet_show = 'show';
// } elseif($type == 'tv'){
// 	$tv_active = 'active dataLayer-sent';
// 	$tv_show = 'show';
// } elseif ($type == 'bundle'){
// 	$bundle_active = 'active dataLayer-sent';
// 	$bundle_show = 'show';
// }

// if ($is_programmatic_city_page) {
//     $args = array(
// 	  'name'        => 'programmatic-city-pages',
// 	  'post_type'   => 'locations',
// 	  'post_status' => 'private',
// 	  'numberposts' => 1
// 	);
// 	query_posts($args);
// }

// $zip_settings = get_field('zip_search', 'options');
// $zip_tv = $zip_settings['show_tv'];
// $zip_bundle = $zip_settings['show_bundles'];

// $tab_hide = false;
// if(empty($zip_tv) && empty($zip_bundle)) {
// 	$tab_hide = true;
// }


// $zip_search_loader_progress_text = get_field('zip_search_loader_progress_text', 'options');
// (isset($zip_search_loader_progress_text['h3_text']) && $zip_search_loader_progress_text['h3_text'] != 
// '') ? $h3_text = $zip_search_loader_progress_text['h3_text'] : $h3_text = 'Finding the Best Deals for You.';
// (isset($zip_search_loader_progress_text['h4_text']) && $zip_search_loader_progress_text['h4_text'] != '') ? $h4_text = $zip_search_loader_progress_text['h4_text'] : $h4_text = 'This should only take a sec.';

require get_theme_file_path( '/template-parts/zip-search-popup.php' );
while ( have_posts() ) :
	the_post();
  $state_long = get_the_title();
  $state = get_field('abbreviation');
?>

	<main class="locations-main">
		<section class="banner mb-5">
			<div class="container">
			<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'has_banner' => true, 'is_location' => true, 'city' => $city, 'state' => $state, 'state_permalink' => $state_perm)); ?>
				<div class="">
					<h1 class="text-left">Internet Providers in <?php echo $state_long ?></h1>
					<div class="d-flex justify-content-start mt-3">
						<a href="#" class="cta_btn zip-popup-btn btn-outline mt-2" target="_blank" data-toggle="modal" data-target="#zipPopupModal-<?php echo $rand; ?>">Change Zip Code</a>
					</div>
					<div class="input_wrap zip_search_form_wrapper" style="display:none;">
						<form action="" class="zip_search_form" data-form="Search Zip Results">
							<input type="number" class="zip_search_input" id="zip" name="zip" minlength="5" maxlength="5" placeholder="Search by ZIP">
							<input type="hidden" id="type" name="type" value="<?php echo $type; ?>">
							<span class="material-icons submit-zip">
								search
							</span>
						</form>
					</div>
				</div>
			</div>
		</section>

		<section>
			<div class="container locations-content mb-5">
				<?php the_content(); ?>
			</div>
		</section>

		<!-- <section class="related-posts">
		<div class="container">
			<?php require get_theme_file_path( '/template-parts/related_posts.php' ); ?>
		</div>
	</section> -->
	</main><!-- #main -->
<?php
	endwhile;
	wp_reset_query();
get_footer();