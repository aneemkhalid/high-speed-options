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
 * template Name: Template - Vertical Page
 * Template Post Type: internet-pages, tv-pages, bundle-pages
 * @package HSO
 */

get_header();

	while ( have_posts() ) :
		the_post(); 
		require get_theme_file_path( '/template-parts/page-banner.php' );?>

		<section class="vertical_page_template">
			<div class="container">

				<section class="right_service_provider">
					<h1 class="bridge-till-redesign">Find the Right <span><?php the_field('main_title') ?></span></h1>
					<p><?php the_field('description') ?></p>
				</section>			

				<?php 
					if(get_field('show_providers')){ 
						$providers = get_field('providers');
						$title = $providers['title'];
						$allProviders = $providers['providers'];
						?>
						<section class="row-full best_service_providers">
							<div class="container">
								<h2><?php echo $title; ?></h2>
								<div class="row">
									<?php 
										if($allProviders){
											foreach ($allProviders as $key => $provider) { 
												$providerID = $provider['provider'];
												$logo = get_field('logo',$providerID);
												$main_features = $provider['features'];
												?>
													<div class="col-xl-3 col-lg-4 col-sm-6">
														<a href="<?php the_permalink($providerID); ?>" class="best-service-provider-box">
															<div class="img-wrap">
																<?php if(!empty($logo)) echo '<img src="'.$logo.'" alt="'.get_the_title($providerID).'">'; ?>
															</div>
															<div class="info">
																<h4><?php echo get_the_title($providerID); ?></h4>
																<?php 
																	if($main_features){
																		echo '<ul>';
																			$i = 0; 
																			foreach ($main_features as $key => $feature) {
																				if($i >= 2) {break;}else{
																					echo '<li><span class="material-icons">check_circle</span>'.$feature['feature'].'</li>';
																					$i++;
																				}
																			}
																		echo '</ul>';
																	}
																?>
															</div>
														</a>
													</div>
												<?php 
											}
										}
									?>
								</div>
							</div>
						</section>
						<?php 
					}
				?>

				<?php 
					if(get_field('show_pros_&_cons')){ 
						$proscons = get_field('pros_&_cons');
						$main_title = $proscons['main_title'];
						$prosconsDetial = $proscons['pros_&_cons'];
						?>
						<section class="pros-cons-type">
							<h2><?php echo $main_title; ?></h2>
							<?php 
								if($prosconsDetial){
									foreach ($prosconsDetial as $key => $detail) { 
											$image = $detail['image'];
											$heading = $detail['heading'];
											$description = $detail['description'];
											$pros = $detail['pros'];
											$cons = $detail['cons'];
										?>
											<div class="pros-cons-box">
												<div class="img-wrap">
													<?php
														if(!empty($image)) echo '<img src="'.$image.'" alt="'.$heading.'">';
													?>										
												</div>
												<div class="info">
													<h4><?php echo $heading; ?></h4>
													<p><?php echo $description; ?></p>
													<div class="row">
														<div class="col-lg-6">
															<?php 
																if($pros){
																	echo '<ul class="pros">';
																	foreach ($pros as $key => $pros) {
																		echo '<li><span class="material-icons">check_circle</span>'.$pros['pros'].'</li>';
																	}
																	echo '</ul>';
																}
															?>
														</div>
														<div class="col-lg-6">
															<?php 
																if($cons){
																	echo '<ul class="cons">';
																	foreach ($cons as $key => $cons) {
																		echo '<li><span class="material-icons">cancel</span>'.$cons['cons'].'</li>';
																	}
																	echo '</ul>';
																}
															?>
														</div>
													</div>
												</div>
											</div>
										<?php
									}
								}
							?>
						</section>
						<?php 
					}
				?>

				<?php the_content(); ?>

				<section id="faq" class="faq">
					<h3><?php the_title(); ?> FAQs</h3>
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
				</section>
				
				<?php get_template_part('/template-parts/related_posts'); ?>
			</div>

		</section>

	<?php 		
	endwhile;
get_footer();