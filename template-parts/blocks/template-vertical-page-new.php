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
 * Template Name: Template - Vertical Page New
 * @package HSO
 */

get_header();

use ZipSearch\ProviderSearchController as ProviderSearchController;
$type = ProviderSearchController::getZipType();
$hero_img = get_field('main_hero_image');
?>
<section class="vertical-page-new-wrapper">
	<div class="container">
		<section class="vertical_page_template vertical-page-new row-full">
			<div class="container">
				<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'exclude_advertiser_disclosure_link' => false ) ); ?>
				<!-- <img class="hero_img" src="<?php the_field('main_hero_image'); ?>" alt=""> -->
				<?php echo wp_get_attachment_image($hero_img['id'], 'full', null, array("class" => 'hero_img')) ?>
				<div class="zipcode_wrapper">
					<div class="zipcode_inner_wrap">
						<h1><?php the_field('vertical_main_heading'); ?></h1>
						<form action="/zip-search" class="zip_search_form search_wrap justify-content-center"
							data-form="Search Inline">
							<div class="icon-container">
								<span class="material-icons">search</span>
							</div>
							<input type="number" class="zip_search_input" id="zip" name="zip" minlength="5" maxlength="5"
								placeholder="Search by ZIP" pattern="\d*" />
							<input type="hidden" id="type" name="type" value="<?php echo $type; ?>">
							<button type="button" class="submit-zip">Search</button>
						</form>
						<p><?php the_field('vertical_page_description'); ?></p>
					</div>	
				</div>
			</div>
		</section>


				<!-- <section class="how-we-evaluate-wrap bg-blue">
				<div class="container">
				<div class="row">
					<div class="col-xl-7 col-lg-6">
						<div class="how-we-evaluate-content">
							<h3>How we evaluate</h3>
							<p>Between hidden fees and price hikes, finding the right internet plan can feel overwhelming. To
								help with your search, our team of internet experts evaluates ISPs on categories including
								performance, affordability, and customer satisfaction to provide you with the best options near
								you.</p>
						</div>
					</div>
					<div class="col-xl-5 col-lg-6">
						<div class="">
							<h4>Best providers in your area</h4>
							<form action="/zip-search" class="zip_search_form search_wrap justify-content-center"
								data-form="Search Inline">
								<div class="icon-container">
									<span class="material-icons">search</span>
								</div>
								<input type="number" class="zip_search_input" id="zip" name="zip" minlength="5" maxlength="5"
									placeholder="Search by ZIP" pattern="\d*" />
								<input type="hidden" id="type" name="type" value="<?php echo $type; ?>">
								<button type="button" class="submit-zip">Search</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section> -->

		<?php the_content(); ?>
		<?php get_template_part('/template-parts/related_posts'); ?>
	</div>
</section>

<?php
get_footer();