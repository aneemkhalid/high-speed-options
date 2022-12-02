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
 * template Name: Template - About Us
 * @package HSO
 */

get_header();

use ZipSearch\ProviderSearchController as ProviderSearchController;
$type = ProviderSearchController::getZipType();


//datalayer info
$providerAboutCounter = 0;

?>
<section class="vertical_page_template about_us_template">
	<div class="container">
		<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'exclude_advertiser_disclosure_link' => false ) ); ?>
		<h3>About Us</h3>
			<div class="goal">
				<div class="row">
				<div class="col-lg-8">
					<h2><?php the_field('about_main_title'); ?> <span><?php the_field('about_main_title_green'); ?></span></h2>
				</div>
				</div>         
				<div class="goal-inner">
					<div class="row">
							<div class="col-md-7">
							<?php echo get_field('about_description'); ?>
							</div>
							<div class="col-md-5">
								<div class="img-wrap">
									<img src="<?php the_field('about_image'); ?>" alt="satellite_graphic" width="380" height="370">
									<div class="bg">
									</div>
								</div>
							</div>
					</div>
						
				</div>
			</div>
			<div class="find-service">
			<div class="row">

				<div class="col-lg-6 col-md-6">

					<div class="img-wrap">
						<img src="<?php the_field('about_left_image'); ?>" alt="find service" width="510" height="362">
						<div class="frame-bg"></div>
					</div>
				</div>

				<div class="col-lg-6 col-md-6">

				<div class="find-service-content">
						<?php echo get_field('right_content'); ?>
				</div>
				</div>
				<div class="col-lg-6">
				<div class="find-service-content zip-wrapper">
						<h4>Find services in your area</h4>
						<form action="/zip-search" class="zip_search_form search_wrap justify-content-center" data-form="Search Inline">
						<div class="icon-container">
							<span class="material-icons">search</span>
						</div>
						<input type="number" class="zip_search_input" id="zip" name="zip" minlength="5" maxlength="5" placeholder="Search by ZIP" pattern="\d*"/>
						<input type="hidden" id="type" name="type" value="<?php echo $type; ?>">
						<button type="button" class="submit-zip">Search</button>
					</form>
				</div>
				</div>
			</div>
			</div>
			<div class="meet_the_team">
				<h2><?php the_field('team_title'); ?></h2>
				<?php
			$team = get_field('select_the_team');
			if( $team ): ?>
				<div class="row">
				<?php foreach( $team as $post ):
				setup_postdata($post); ?>
					<div class="col-lg-4">
						<a href="<?php the_permalink(); ?>" class="team-info">
							<img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" height="200" width="200">
							<div class="team-content">
							<h4><?php the_title(); ?></h4>
							<p><?php the_field('designation'); ?></p>
							<span>View Bio</span>
							</div>
							
						</a>
					</div>
					<?php endforeach; ?>
				</div>
				<?php 
					wp_reset_postdata(); ?>
				<?php endif; ?>
			</div>
			<div class="row-full make-money">
			<div class="container">
				<div class="row">
					<div class="col-md-10 col-lg-5">
						<div class="make-money-content">
							<h2><?php the_field('make_title'); ?></h2>
							<?php echo get_field('make_description'); ?>
						</div>
					</div>
					<div class="col-lg-7 col-md-12">
						<div class="providers-list">
						<?php
							$providers = get_field('about_select_providers');
							if( $providers ): ?>
							<ul>
								<?php foreach( $providers as $post ):
								setup_postdata($post); ?>

                                <?php 
                                            //datalayer info
                                            $providerID = get_the_ID();
                                            $providerAboutCounter++;
                                            $providerAboutVariant = [
                                                'text' => 'How we make money providers'
                                            ];
                            
                                            $providerAboutProductClick = dataLayerProdClick($providerID, $providerAboutVariant, $providerAboutCounter,  "About us", "How we make money");
                                ?>
								<li>
									<a href="<?php the_permalink(); ?>" onClick="<?php echo $providerAboutProductClick  ?>">

										<img src="<?php echo the_field('logo'); ?>" alt="logo" width="120" height="50">
									</a>
								</li>
								<?php endforeach; ?>
							</ul>
							<?php 
							wp_reset_postdata(); ?>
						<?php endif; ?>
						</div>

					</div>
				</div>
			</div>
			</div>
			<div class="about-social">
				<div class="reach-out">
					<p><?php the_field('reach_out_content'); ?>: <a href="mailto:<?php the_field('reach_out_email'); ?>"><?php echo the_field('reach_out_email'); ?></a></p>
					<div class="bg"></div>
				</div>
				<div class="social-icons">
					<span>Visit us at:</span>
					<div class="links">
					<?php
					$social_icons = get_field('about_social_links');
					if( $social_icons ): ?>
					<a href="<?php echo $social_icons['facebook_link'] ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/facebook_white.svg" alt="facebook white" height="25" width="25"></a>
					<?php endif; ?>
					<a href="<?php echo $social_icons['twitter_link'] ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/twitter_white.svg" alt="twitter white" height="25" width="25"></a>
					<a href="<?php echo $social_icons['linkedin_link'] ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/linkedin_white.svg" alt="linkedin white" height="25" width="25"></a>
					</div>
				</div>
			</div>
		<!-- <div class="faq">
			<h3>FAQs</h3>
			<div id="accordion">

				<?php
					// Check rows exists.
					if( have_rows('faqs') ):
						$counter = 1;
						// Loop through rows.
						while( have_rows('faqs') ) : the_row();

							// Load sub field value.
							$question = get_sub_field('question'); 
							$answer = get_sub_field('answer'); 
								?>
								<div class="card">
									<div class="card-header" id="heading<?php echo $counter; ?>">
										<h4>
											<button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $counter; ?>"aria-expanded="true" aria-controls="collapse<?php echo $counter; ?>"><?php echo $question; ?><span class="material-icons">expand_less</span></button>
										</h4>
									</div>
									<div id="collapse<?php echo $counter; ?>" class="collapse show" aria-labelledby="heading<?php echo $counter; ?>" data-parent="#accordion">
										<div class="card-body">
											<?php echo $answer; ?>
										</div>
									</div>
								</div>

							<?php $counter++;
						endwhile;
						// Do something...
					endif;
				?>
			</div>
		</div> -->
	</div>
</section>
	<?php 		
	// endwhile;
get_footer();