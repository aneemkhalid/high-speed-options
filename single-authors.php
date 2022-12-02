<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package HSO
 */

get_header(); 
while ( have_posts() ) : 
	the_post();
	$page_id = get_the_ID();
?>
<main class="resources author-bio-page">
	<section>
		<div class="container">
			<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'exclude_advertiser_disclosure_link' => false ) ); ?>
		</div>
	</section>
	<section class="single_author">
		<div class="container">
			<div class="single_author_inner">
				<div class="img_wrap">
					<img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>"
						title="<?php the_title(); ?>" height="200" width="200">
				</div>
				<div class="author_content">
					<div class="head">
						<div>
							<h3><?php the_title(); ?></h3>
							<p><?php the_field('designation'); ?></p>
						</div>
						<?php if(get_field('linkedin_profile')) : ?>
						<div class="social_icons">
							<a href="<?php the_field('linkedin_profile'); ?>" target="_blank"><img
									src="<?php echo get_template_directory_uri(); ?>/images/linkedin_icon.svg"
									alt="linkedin logo" height="18" width="18"></a>
						</div>
						<?php endif; ?>
					</div>
					<p><?php the_field('bio'); ?></p>
				</div>
			</div>
		</div>
	</section>
	<section class="resource-main">
		<div class="container">
			<div class="resource-featured-post all three">
				<h3>Featured</h3>
				<?php
				$featured_posts = get_field('select_featured_articles');
				if( $featured_posts ): ?>
				<div class="row">
					<?php foreach( $featured_posts as $post ): 
					setup_postdata($post);
					?>
					<div class="col-xl-4 col-md-6 box">
						<a href="<?php the_permalink(); ?>" class="inner">
							<div class="img_wrap">
								<div class="post-thumbnail">
									<?php echo wp_get_attachment_image(get_post_thumbnail_id(), 'large'); ?>
								</div>
							</div>
							<div class="content">
								<div>
									<div class="categories">
										<?php $tags = get_the_tags();
											foreach($tags as $tag) {
											?>
										<span><?php echo $tag->name; ?></span>
										<?php } ?>
										<?php $format_filters = get_field('format_filters');
											if($format_filters){
											foreach($format_filters as $format_filter) {
											?>
										<span class="green-tag"><?php echo $format_filter['label']; ?></span>
										<?php 
											} 
											} 
											?>
									</div>
									<h4><?php echo get_the_title(); ?></h4>
									<p><?php $excerpt = get_the_excerpt(); 
													$excerpt = substr( $excerpt, 0, 125 ); ?>
										<?php echo $excerpt; ?> … <span>Read More</span></p>
								</div>
								<?php
								if($author){
								$authorName = get_the_title($author);
								} else{
								$authorName = get_the_author_meta('first_name', $post->post_author).' '.get_the_author_meta('last_name', $post->post_author);
								} ?>
								<span>By
									<?php echo $authorName; ?>
									·
									<?php echo date("F j, Y", strtotime($post->post_date)); ?></span>
							</div>
						</a>
					</div>
					<?php endforeach; ?>
				</div>
				<?php 
				wp_reset_postdata();
				endif; ?>
			</div>
			<div class="all-resources">
				<h3><?php the_title(); ?>'s Articles</h3>
				<div class="row">
					<div class="col-xl-12">
						<div class="resource-featured-post all">
							<?php
							$paged = (get_query_var('paged')) ? get_query_var('paged') : 0;
							$args = array(
								'post_type'		=> 'post',
								'posts_per_page' => 5,
								'post_status' => 'publish',
								'paged'   => $paged,
								'orderby'  => 'date',
								'order' => 'DESC',
								'meta_key' => 'article_authors_dropdown',
								'meta_value' => $page_id
						/*		'meta_query' => array(
									'relation' => 'OR',
									array(
										'relation' => 'AND',
										array(
											'key' => 'article_authors_dropdown',
											'value' => $page_id,
											'compare' => '=',
										),
										array(
											'key' => 'author_type',
											'value' => 'Editor',
											'compare' => '!=',
										)
									),
									array(
										'relation' => 'AND',
										array(
											'key' => 'article_authors_dropdown',
											'value' => $page_id,
											'compare' => '=',
										),
										array(
											'key' => 'author_type',
											'compare' => 'NOT EXISTS',
										),
									),      
					
								)*/
							);
							$query = new WP_Query( $args );
							if ( $query->have_posts() ) {
								while ( $query->have_posts() ) {
									$query->the_post();
									// echo "<pre>";print_r($query->post_count);echo "</pre>";
									// exit;
									$post_count = $query->post_count;
							?>
							<div class="box">
								<a href="<?php the_permalink(); ?>" class="inner">
									<div class="img_wrap">
										<div class="post-thumbnail">
											<img src="<?php echo get_the_post_thumbnail_url(); ?>"
												alt="resource_featured_image">
										</div>
									</div>
									<div class="content">
										<div>
											<div class="categories">
												<?php $tags = get_the_tags();
													foreach($tags as $tag) {
													?>
												<span><?php echo $tag->name; ?></span>
												<?php } ?>
												<?php $format_filters = get_field('format_filters');
														if($format_filters){
														foreach($format_filters as $format_filter) {
														?>
												<span class="green-tag"><?php echo $format_filter['label']; ?></span>
												<?php }
													} ?>
											</div>
											<h4><?php the_title(); ?></h4>
											<?php $excerpt = get_the_excerpt(); 
													$excerpt = substr( $excerpt, 0, 125 ); ?>
											<p><?php echo $excerpt; ?> ... <span>Read More</span>
											</p>
										</div>
										<?php
										if($author){
										$authorName = get_the_title($author);
										} else{
										$authorName = get_the_author();
										} ?>
										<span>By <?php echo $authorName; ?> · <?php echo get_the_date(); ?></span>
									</div>
								</a>
							</div>
							<?php 
							}
							} 
							wp_reset_query();
							?>
							<!-- Hidden Fields -->
						</div>
						<input type="hidden" class="authorpageNumber" value="<?php echo $paged; ?>">
						<input type="hidden" class="authorid" value="<?php echo $page_id; ?>">
						<div class="author-no-more-posts">No more Posts</div>
						<div class="resource-featured-post-btn-wrap">
							<?php if($post_count == 5):  ?>
							<a href="#" class="see-more_posts_btn">See More</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="meet_the_team">
		<div class="container">
			<h2>View Other Authors</h2>
			<?php
			$args = array(
				'post_type' => 'authors',
				'post_status' => 'publish',
				'orderby' => 'publish_date',
				'order' => 'DESC',
				'posts_per_page' => 2,
				'post__not_in' => array($page_id)
			);
			$authors = new WP_Query( $args ); 
			if( $authors->have_posts() ): ?>
			<div class="row">
			<?php while ( $authors->have_posts()  ) : $authors->the_post(); ?>
				<div class="col-lg-6">
					<a href="<?php the_permalink(); ?>" class="team-info">
						<img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>"
							title="<?php the_title(); ?>" height="200" width="200">
						<div class="team-content">
							<h4><?php the_title(); ?></h4>
							<p><?php the_field('designation'); ?></p>
							<span>View Bio</span>
						</div>

					</a>
				</div>
				<?php endwhile; ?>
			</div>
			<?php 
					wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>
	</section>
</main>


<?php
endwhile;
get_footer();