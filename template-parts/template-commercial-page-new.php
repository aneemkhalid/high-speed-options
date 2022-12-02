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
 * Template Name: Template - Commercial Page New
 * Template Post Type: internet-pages, tv-pages, bundle-pages
 * @package HSO
 */

get_header();

	while ( have_posts() ) :
		the_post(); 
		// require get_theme_file_path( '/template-parts/breadcrumbs.php' );  ?>
		
		<div class="container">
			<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'exclude_advertiser_disclosure_link' => false ) ); ?>
		</div>

		<section id="commercial_page_template" class="vertical_page_template toc-sidebar-commercial-page <?php echo ($deal = get_field('deals_page')) ? 'is-deal' : ''; ?>">
			<div class="container">
				<div class="commercial_page_content pt-5">
					<div class="commercial_page_left_content">
						<div class="commercial_page_left_content_head">
							<h1 class="bridge-till-redesign"><?php the_title() ?></h1>
						</div>
						<div class="author-detail_wrapper">
							<?php
								$author = get_field('article_authors_dropdown');

								if($author){
									$authorName = get_the_title($author);
									$author_type = get_field('author_type');
									$authorLink = get_permalink($author);
									$authorImageURL = get_the_post_thumbnail_url($author);
									$author_image = '';
									if($authorImageURL){
										$author_image = '<img src="'.get_the_post_thumbnail_url($author).'" width="50" height="50" alt="'.$authorName.'" />';
									}
								}else{
									$authorName = get_the_author();
									$author_image = '';
								}
								if($author_type == 'Editor') {
									$authorName = 'Edited by: ' . $authorName;
								}
							?>
							<a href="<?php echo $authorLink  ?>" class="author_info">
								<?php echo $author_image; ?>
								<div class="author_info_content">
									<div class="author"><?php echo $authorName ?></div>
									<div class="date"><?php echo get_the_date() ?></div>
								</div>
							</a>
							<div class="social_share">
								<a id="facebook" href="https://www.facebook.com/sharer?u=<?php the_permalink() ?>&amp;t=<?php the_title(); ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo get_template_directory_uri() ?>/images/facebook_social_share.svg" alt="facebook icon" height="30" width="30"></a>
								<a id="twitter" href="https://twitter.com/intent/tweet?url=<?php the_permalink() ?>&amp;text=<?php the_title(); ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo get_template_directory_uri() ?>/images/twitter_social_share.svg" alt="twitter icon" height="30" width="30"></a>
								<a id="linkedin" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink() ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo get_template_directory_uri() ?>/images/linkedin_social_share.svg" alt="linkedin icon" height="30" width="30"></a>
							</div>
						</div>
						<?php 
						the_content(); ?>
					</div>
					<div class="commercial_page_right_content">
						<div class="commercial_page_table_of_content">
							<!-- <div class="commercial-page-right-inner"> -->
							  <?php 
							  $disable_toc = get_post_meta(get_the_id(), '_ez-toc-disabled')[0];
							  if (!$disable_toc){
							  	echo do_shortcode('[toc]');
							  } ?>
							  	<?php  
							  	// if(get_field('show_find_providers')){ 
									 // 	$default_tab = get_field('provider_card_default_tab');
									 // 	$heading = get_field('heading');
									 // 	$description = get_field('description');
										//  if ($default_tab == 'internet'){
										// 	 $internet_checked = 'checked';
										//  } elseif ($default_tab == 'tv'){
										// 	 $tv_checked = 'checked';
										//  } elseif ($default_tab == 'bundle'){
										// 	 $bundle_checked = 'checked';
										//  }
										//  $provider_cta_text = get_field('provider_card_button_text'); 
	    
	                                 
									  ?>
									<!-- <div class="find-providers"> -->
										<?php 
											// if(!empty($heading))
										 // 		echo '<h4>'.$heading.'</h4>';
											// if(!empty($description))
										 // 		echo '<p>'.$description.'</p>';
												 
											// $data_att = 'data-toggle="modal" data-target="#zipPopupModal-"';
	    
	          //                                $dataCheckCategory = get_post_field( 'post_name', get_post() ); 
	     
	          //                                 $dataCheckAvailOnClick = 'onclick="'.dataLayerCheckAvailabilityClick(get_the_ID(), $dataCheckCategory).'"';
	    
											// echo '<a href="#" class="cta_btn zip-popup-btn" target="_blank" data-toggle="modal" '.$data_att.' '.$dataCheckAvailOnClick.'>'.$provider_cta_text.'</a>';
										?>
									<!-- </div> -->
								<?php 
								//} ?>

								<?php
								// check for hmsdin tool elemetn on page
								// find location if exists
								// build in sidebar if sidebar is selected
								$hmsdin_sidebar_element = false;
								if ( function_exists( 'get_field' ) ) {
									$pid = get_post();
									$pid_content = (get_the_content($pid));
									if ( has_blocks( $pid_content ) ) {
										$blocks = parse_blocks( $pid->post_content );
										$key = array_search('acf/hmsdin-tool-elem', array_column($blocks, 'blockName'));
										if($key){
											$location = $blocks[$key]['attrs']['data']['hmsdin_location'];
											if($location == 'sidebar'){
												$hmsdin_sidebar_element = true;
											}
										}
									}
								} 
								if($hmsdin_sidebar_element): ?>
									<div class="hsmdin-toole-wrap" id="hmsdin-sidebar">
										<?php 
										$title = $blocks[$key]['attrs']['data']['hmsdin_title'];
										$description = $blocks[$key]['attrs']['data']['hmsdin_description'];
										$button_text = $blocks[$key]['attrs']['data']['hmsdin_button_text'];
										$button_link = $blocks[$key]['attrs']['data']['hmsdin_button_link'];
										$show_speeds = $blocks[$key]['attrs']['data']['hmsdin_show_speed_descriptions'];
										?>
										<div class="blue-container border-radius-20 p-4 pt-5 pb-5">
											<h4><?php echo $title; ?></h4>
											<p class="hmsdin-description"><?php echo $description; ?></p>
											<a href="<?php echo $button_link; ?>" class="cta_btn"><?php echo $button_text; ?></a>
										</div>	
										<?php 
										if($show_speeds): ?>
											<div class="hmsdin-speed-cont">
												<?php
												$speed_descriptions = $blocks[$key]['attrs']['data']['hmsdin_speed_descriptions'];	
												for( $i = 0; $i < $speed_descriptions; $i++){
													echo '<h5>' . $blocks[$key]['attrs']['data']['hmsdin_speed_descriptions_' . $i . '_title']	 . '</h5>';
													echo '<p>' . $blocks[$key]['attrs']['data']['hmsdin_speed_descriptions_' . $i . '_description'] . '</p>';
												} ?>
											</div>
										<?php endif;?>
									</div>
								<?php endif; ?>
							</div>
<!-- 					    </div> -->

					</div>
				</div>	
				<?php if(get_field('show_find_providers')){ require get_theme_file_path( '/template-parts/zip-search-popup.php' ); } ?>	

				<?php get_template_part('/template-parts/related_posts'); ?>

			</div>

		</section>
	<?php 		
	endwhile;
get_footer();