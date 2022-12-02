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
 * Template Name: Template - Comparison
 * Template Post Type: post, comparisons
 * @package HSO
 */

get_header();
$provider_1_id = get_field('provider_1');
$provider_2_id = get_field('provider_2');

$provider_1_logo = get_field('logo', $provider_1_id);
$provider_2_logo = get_field('logo', $provider_2_id);


	while ( have_posts() ) :
		the_post(); 
		// require get_theme_file_path( '/template-parts/breadcrumbs.php' );  ?>
		<div class="comparison-page-template">
			<div class="comparison-header-container">
				<div class="container">
					<div class="row pt-3">
						<div class="col">
							<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'exclude_advertiser_disclosure_link' => false ) ); ?>
						</div>
					</div>
					<div class="row pt-5 pb-3 pb-sm-5">
						<div class="col mr-5">
							<div class="commercial_page_left_content_head">
								<span>
									<?php 
									global $post;     // if outside the loop

									if ( $post->post_parent ) { 
										echo get_the_title( $post->post_parent ); 
									} ?>
								</span>
								<h1 class="bridge-till-redesign"><?php the_title() ?></h1>
							</div>
							<div class="author-detail_wrapper">
								<?php
									$author = get_field('article_authors_dropdown');

								if($author){
									$authorName = get_the_title($author);
									$author_type = get_field('author_type');
									$authorLink = get_permalink($author);
									$authorbio = get_field('bio', $author);
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
						</div>	
						<div class="col d-flex align-items-center comparison-logos-header">
							<div class="dots-container">
			                    <svg width="100%" height="100%"><pattern id="a" x="0" y="0" width="14" height="14" patternUnits="userSpaceOnUse" patternContentUnits="userSpaceOnUse"><circle cx="2" cy="2" r="2" fill="#D1D3D4"/></pattern><rect width="100%" height="100%" fill="url(#a)"/></svg>
			                </div>
							<div class="container">
								<div class="row">
									<div class="col white-background-card thin-boxshadow border-radius-20 p-3 pt-4 pb-4 mr-4 d-flex justify-content-center align-items-center">
										<img src="<?php echo $provider_1_logo; ?>" alt="logo" class="m-2" width="180" height="50">
									</div>
									<div class="col white-background-card thin-boxshadow border-radius-20 p-3 pt-4 pb-4 d-flex justify-content-center align-items-center">
										<img src="<?php echo $provider_2_logo; ?>" alt="logo" class="m-2" width="180" height="50">
									</div>
								</div>
							</div>		
						</div>
					</div>	
				</div>
			</div>

			<section class="vertical_page_template">
				<div class="container">
					<div class="commercial_page_content">
						<div class="commercial_page_left_content post-content">
							<?php the_content(); ?>
							<div class="share-post d-flex mb-4 align-items-center">
								<h6 class="mb-0">Share this post:</h6>
								<div class="social_share">
									<a id="facebook-icon-blog-bottom" href="https://www.facebook.com/sharer?u=<?php the_permalink() ?>&amp;t=<?php the_title(); ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo get_template_directory_uri() ?>/images/facebook_social_share.svg"></a>
									<a id="twitter-icon-blog-bottom" href="https://twitter.com/intent/tweet?url=<?php the_permalink() ?>&amp;text=<?php the_title(); ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo get_template_directory_uri() ?>/images/twitter_social_share.svg"></a>
									<a id="linkedin-icon-blog-bottom" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink() ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo get_template_directory_uri() ?>/images/linkedin_social_share.svg"></a>
								</div>
							</div>
							<?php
				            if ($authorbio): ?>
				            <div class="author-bio d-flex align-items-center flex-wrap flex-md-nowrap pt-4 pb-4 mb-5">
									<a href="<?php echo $authorLink; ?>">
										<div class="avatar-wrapper flex-shrink-0 mr-4"> 
											<?php echo $author_image; ?> 
										</div>
									</a>
								
									<div class="author-info-mobile">
										<div class="light-text">Written By</div>
										<a href="<?php echo $authorLink; ?>">
											<div class="font-weight-bold author-title"><?php echo $authorName; ?></div>
										</a>
									</div>
								
								
				                <div class="bio pr-0 pr-md-5">
									
									<div class="author-info-desktop">
										<div class="light-text">Written By</div>
										<a href="<?php echo $authorLink; ?>">
											<div class="font-weight-bold author-title"><?php echo $authorName; ?></div>
										</a>
									</div>
									
				                    <div class="mt-2 bio-text"><?php echo $authorbio; ?></div>
				                </div>
				            </div>
				            <?php endif; ?>
						</div>
						<div class="commercial_page_right_content comparison_page_right_content">
							<div class="commercial_page_table_of_content">
							  <?php 
								$disable_toc = get_post_meta(get_the_id(), '_ez-toc-disabled')[0];
								  if (!$disable_toc){
								  	echo do_shortcode('[toc]');
								} ?>
							  	<?php  if(get_field('show_find_providers')){ 
									 	$default_tab = get_field('provider_card_default_tab');
									 	$heading = get_field('heading');
									 	$description = get_field('description');
										 if ($default_tab == 'internet'){
											 $internet_checked = 'checked';
										 } elseif ($default_tab == 'tv'){
											 $tv_checked = 'checked';
										 } elseif ($default_tab == 'bundle'){
											 $bundle_checked = 'checked';
										 }
										 $provider_cta_text = get_field('provider_card_button_text'); 
	    
	                                 
									  ?>
									<div class="find-providers">
										<?php 
											if(!empty($heading))
										 		echo '<h4>'.$heading.'</h4>';
											if(!empty($description))
										 		echo '<p>'.$description.'</p>';
												 
											$data_att = 'data-toggle="modal" data-target="#zipPopupModal-"';
	    
	                                         $dataCheckCategory = get_post_field( 'post_name', get_post() ); 
	     
	                                          $dataCheckAvailOnClick = 'onclick="'.dataLayerCheckAvailabilityClick(get_the_ID(), $dataCheckCategory).'"';
	    
											echo '<a href="#" class="cta_btn zip-popup-btn" target="_blank" data-toggle="modal" '.$data_att.' '.$dataCheckAvailOnClick.'>'.$provider_cta_text.'</a>';
										?>
									</div>
								<?php } ?>
						    </div>
						</div>
					</div>	

					<?php get_template_part('/template-parts/related_posts'); ?>

				</div>

			</section>
		</div>	
	<?php 		
	endwhile;
get_footer();
