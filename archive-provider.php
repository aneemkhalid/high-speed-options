<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package HSO
 */

use ZipSearch\ProviderSearchController as ProviderSearchController;
get_header();
$zip_type = ProviderSearchController::getZipType();
$types= array('Internet', 'TV', 'Bundles');

$providerPageCounter = 0;

?>
	<section>
		<div class="container">
			<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'exclude_advertiser_disclosure_link' => false ) ); ?>
		</div>
	</section>
	<section class="home-hero-block provider-aggregate">
		<div class="zip-search-container">
			<div class="container">
				<div class="bg-container">
					<div class="md-container mb-4">
						<h2>Find Internet, Cable TV & Bundle Providers in your Area</h2>
					</div>
					<div class="zipcode inner">
						<form action="/zip-search" class="zip_search_form search_wrap" data-form="Search Banner">
							<div class="input-container">
								<div class="icon-container">
									<span class="material-icons">search</span>
								</div>
								<input type="number" class="zip_search_input" id="zip" name="zip" minlength="5" maxlength="5" placeholder="Search by ZIP" pattern="\d*"/>
								<button type="button" class="submit-zip">Search</button>
							</div>
							<div class="check-list">
								<?php foreach($types as $type):
									$type_lower = strtolower( $type );
								?>
									<input type="radio" id="<?php echo $type_lower; ?>-radio" name="type" value="<?php echo $type_lower; ?>" <?php if($zip_type === $type_lower) echo 'checked'; ?> >
									<label for="<?php echo $type_lower; ?>-radio"><?php echo $type; ?></label>
								<?php endforeach; ?>
							</div>
						</form>	
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="vertical_page_template">
			<div class="container">
				<section class="right_service_provider trusted-providers">
						<?php 
						$my_id = 2750;
						$post_id_5369 = get_post($my_id);
						$provider_who_shouldnot_be_included = get_field('provider_who_shouldnot_be_included',$my_id);
						$content = $post_id_5369->post_content;
						$content = apply_filters('the_content', $content);
						$content = str_replace(']]>', ']]>', $content);
						echo $content;
						?>
				</section>
				<section class="row-full best_service_providers all_providers">
					<div class="container">
						<div class="row">
							<?php 
								// the query
								$wpb_all_query = new WP_Query(array(
									'post_type' => 'provider',
									'post_status'=>'publish',
									'posts_per_page'=>-1,
									'orderby' => 'title',
    								'order' => 'ASC',
									'post__not_in' => $provider_who_shouldnot_be_included
								)); ?>
								<?php if ( $wpb_all_query->have_posts() ) : ?>
										<?php while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); ?>
                            
                                        <?php
                                            //dataLayer info
                                            $providerID = get_the_ID();
                                            $providerPageCounter++;
                                            $providerPageVariant = [
                                                'text' => 'Providers Cards'
                                            ];
                            
                                            $providerPageProductClick = dataLayerProdClick($providerID, $providerPageVariant, $providerPageCounter,  "Providers", "All Providers");
                                            //dataLayerProductImpression($provider, $dataLayerCategory, $dataLayerVariant, $dataLayerList, $dataLayerPosition )
                                            $providerPageIndv = dataLayerProductImpression($providerID,  "Providers", $providerPageVariant, "Providers Page List", $providerPageCounter );
                                            
                                            $providerPagePageLoad .= $providerPageIndv;  
                                   
                            
                
                                        ?>        
											<div class="col-xl-3 col-lg-4 col-sm-6">
												<div class="best-service-provider-box-wrap">
												<a href="<?php the_permalink(); ?>" class="best-service-provider-box" onclick="<?php echo $providerPageProductClick; ?>">
													<?php 
														if(get_field('logo')){
															echo '<div class="img-wrap"> <img src="'.get_field('logo').'" alt="'.get_the_title().'" width="140" height="50"></div>';
														}
													?>
												</a>
												<a href="<?php the_permalink(); ?>"  onclick="<?php echo $providerPageProductClick; ?>" class="info"><p class="link-style"><?php the_title(); ?><span class="material-icons chevron">chevron_right</span> </p></a>
												</div>
											</div>
										<?php endwhile; ?>
										<?php wp_reset_postdata(); ?>
									</div>
								<?php else : ?>
									<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
								<?php endif; ?>
						</div>
					</div>
				</section>
			</div>
	</section>
	

	<?php get_template_part('/template-parts/related_posts', null, ['container' => true, 'class' => 'all_providers_posts']); ?>


    <?php 
      //dataLayer info    
        $providerPageWrapper = dataLayerProductImpressionWrapper($providerPagePageLoad);

    ?>

    <script>
        <?php echo $providerPageWrapper ?>
        
    </script>

<?php		
get_footer();