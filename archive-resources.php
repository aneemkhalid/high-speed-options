<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package HSO
 * 
 * Template Name: Resources
 */

get_header();
?>
<main class="site-main resources">
	<section>
		<div class="container">
			<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'exclude_advertiser_disclosure_link' => false ) ); ?>
			<h1 class="mt-2 mb-4">Resources</h1>
		</div>
	</section>
	<section class="resource-main">
		<div class="container">
			<?php
			$main_featured_post = get_field('main_featured_resource');
			if( $main_featured_post ):
			?>
			<a href="<?php the_permalink($main_featured_post->ID); ?>" class="resource-featured-post main">
				<div class="inner">
					<div class="img_wrap">
						<div class="post-thumbnail">
							<?php echo wp_get_attachment_image(get_post_thumbnail_id($main_featured_post->ID), 'large'); ?>
						</div>
					</div>
					<div class="content-wrap">
						<div class="content">
							<div class="categories">
								<?php $tags = get_the_tags($main_featured_post->ID);
								foreach($tags as $tag) {
								?>
								<span><?php echo $tag->name; ?></span>
								<?php } ?>
								<?php $format_filters = get_field('format_filters', $main_featured_post->ID);
								if($format_filters){
								foreach($format_filters as $format_filter) {
								?>
								<span class="green-tag"><?php echo $format_filter['label']; ?></span>
								<?php }
								} ?>
							</div>
							<h3><?php echo $main_featured_post->post_title; ?></h3>
							<?php $excerpt = get_the_excerpt($main_featured_post->ID); 
 										$excerpt = substr( $excerpt, 0, 360 ); ?>
							<p><?php echo $excerpt; ?> … <span>Read More</span></p>
							<?php $author = get_field('article_authors_dropdown', $main_featured_post->ID);  
							if($author){
								$authorName = get_the_title($author);
							} else{
								$authorName = get_the_author_meta('first_name', $main_featured_post->post_author).' '.get_the_author_meta('last_name', $main_featured_post->post_author);
							}
							?>
							<span>By <?php echo $authorName; ?> ·
								<?php echo date("F j, Y", strtotime($main_featured_post->post_date)); ?></span>
						</div>
					</div>
				</div>
			</a>
			<?php endif; ?>
			<div class="resource-featured-post all three">
				<?php
				$three_featured_posts = get_field('three_featured_resources');
				if( $three_featured_posts ): ?>
				<div class="row">
				<?php foreach( $three_featured_posts as $post ): 
					setup_postdata($post);
					?>
					<div class="col-xl-4 col-md-6 box">
						<a href="<?php the_permalink(); ?>" class="inner">
							<div class="img_wrap">
								<div class="post-thumbnail">
									<?php echo wp_get_attachment_image(get_post_thumbnail_id(), 'medium'); ?>
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
								<?php $author = get_field('article_authors_dropdown');  
								if($author){
									$authorName = get_the_title($author);
								} else{
									$authorName = get_the_author_meta('first_name', $post->post_author).' '.get_the_author_meta('last_name', $post->post_author);
								}
								?>
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
				<h3>All Resources</h3>
				<div class="row">
					<div class="col-xl-8">
						<div class="resource-featured-post all">
							<?php
									$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
									$args = array(
										'post_type'		=> 'post',
										'posts_per_page' => 10,
										'post_status' => 'publish',
										'paged'   => $paged,
										'orderby'  => 'date',
										'order' => 'DESC',
									);
									$query = new WP_Query( $args );
									if ( $query->have_posts() ) {
										while ( $query->have_posts() ) {
											$query->the_post();
									?>
							<div class="box">
								<a href="<?php the_permalink(); ?>" class="inner">
									<div class="img_wrap">
										<div class="post-thumbnail">
											<?php echo wp_get_attachment_image(get_post_thumbnail_id(), 'medium'); ?>
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
										<?php $author = get_field('article_authors_dropdown');  
										if($author){
											$authorName = get_the_title($author);
										} else{
											$authorName = get_the_author();
										}
										?>
										<span>By <?php echo $authorName; ?> · <?php echo get_the_date(); ?></span>
									</div>
								</a>
							</div>
							<?php 
							}
							} 
							wp_reset_postdata();
							?>
							<!-- Hidden Fields -->
						</div>
						<input type="hidden" class="pageNumber" id="pageno" value="<?php echo $paged; ?>">
						<input type="hidden" class="hiddentags" value="">
						<div class="no-more-posts">No more Posts</div>
						<div class="resource-featured-post-btn-wrap">
							<a href="#" class="more_posts_btn" data-action="more_post_ajax">View More</a>
						</div>
					</div>
					<div class="col-xl-4">
						<div class="resource-filters">
							<div class="input-wrap">
								<input type="search" id="resource-search-input" name="resource_search" placeholder="Search">
								<span class="material-icons">search</span>
							</div>
							<h4>Explore Topics</h4>
							<div class="explore topics">

								<?php $tags = get_tags();
										foreach($tags as $tag) {
										?>
								<div class="filter-tag explore-topic blue tags" id="resource-<?php echo $tag->slug; ?>"
									data-tag="<?php echo $tag->slug; ?>">
									<span class="material-icons">done</span>
									<?php echo $tag->name; ?>
								</div>
								<?php } ?>
								<div class="filter-tag explore-topic-all blue tags" id="resource-topic-select">
									<span class="material-icons">done</span>
									select all
								</div>
							</div>
							<h4>Explore Formats</h4>
							<div class="explore formats">
								<div class="filter-tag explore-format green tags" id="resource-guide" data-format="guide">
									<span class="material-icons">done</span>
									guide
								</div>
								<div class="filter-tag explore-format green tags" id="resource-video" data-format="video">
									<span class="material-icons">done</span>
									video
								</div>
								<div class="filter-tag explore-format green tags" id="resource-news" data-format="news">
									<span class="material-icons">done</span>
									news
								</div>
								<div class="filter-tag explore-format green tags" id="resource-insights" data-format="insights">
									<span class="material-icons">done</span>
									insights
								</div>
								<div class="filter-tag explore-format green tags" id="resource-howto" data-format="howto">
									<span class="material-icons">done</span>
									how-to
								</div>
								<div class="filter-tag explore-format-all green tags" id="resource-format-select">
									<span class="material-icons">done</span>
									select all
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>
<?php		
get_footer();