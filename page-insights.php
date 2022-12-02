<?php
/**
 * The template for the insights page
 * @package HSO
 */

get_header();
include get_template_directory() . '/template-parts/recent_posts.php';
include get_template_directory() . '/template-parts/featured_post.php';
?>
	<main class="site-main insights">
		<section>
			<div class="container">
				<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'is_blog' => true, 'exclude_advertiser_disclosure_link' => true ) ); ?>
				<h1 class="mt-2 mb-4">Insights</h1>
			</div>
		</section>
		<section class="insights_nav">
			<div class="container">
				<div class="row no-gutters">
					<div class="col-lg-8 col-12 order-lg-1 order-2">
						<ul class="nav nav-tabs" id="typeTab" role="tablist">
							<li class="nav-item">
								<a class="nav-link ripple-el active" id="featured-search-tab" data-toggle="tab" href="#featured-search" role="tab" aria-controls="featured-search" aria-selected="true">Featured</a>
							</li>
							<li class="nav-item">
								<a class="nav-link ripple-el" id="internet-search-tab" data-toggle="tab" href="#internet-search" role="tab" aria-controls="internet-search" aria-selected="false">Internet</a>
							</li>
							<li class="nav-item">
								<a class="nav-link ripple-el" id="tv-search-tab" data-toggle="tab" href="#tv-search" role="tab" aria-controls="tv-search" aria-selected="false">TV</a>
							</li>
							<li class="nav-item">
								<a class="nav-link ripple-el" id="streaming-search-tab" data-toggle="tab" href="#streaming-search" role="tab" aria-controls="streaming-search" aria-selected="false">Streaming</a>
							</li>
			            </ul>
					</div>
					<div class="col-lg-4 col-12 order-lg-2 order-1 d-flex justify-content-end align-items-center">
						<div class="blog-search-container pb-4 pb-lg-0">
							<div id="blog-search-form" class="blog_search_form" action="">
								<input type="search" id="blog-search-input" class="blog-search-input blog_search_input" name="blog_search" placeholder="Search"/>
								<button type="button" id="blog-search-btn">
									<span class="material-icons submit-blog-search">
										search
									</span>
								</button>
							</div>
						</div>
					</div>
				<div>
			</div>
		</section>

		<section class="insights-list-container container common-style mt-md-5 mt-4" id="accordion">
			<div class="tab-content" id="typeTabContent">
				<div class="tab-pane fade active show featured-search" id="featured-search" role="tabpanel" aria-labelledby="featured-search-tab">
					<div class="featured-post row mb-5">
						<?php 
						$args = [];
						$featured_post_arr = get_featured_post($args);
						echo $featured_post_arr['code']; ?>
					</div>	
					<div class="latest-posts-title row">
						<div class="col-12">
							<h3>Latest Posts</h3>
						</div>
					</div>
					<div class="latest-posts-wrapper">
						<?php 
						//get total post count
						$args = [
					        'exclude' => array($featured_post_arr['id'])
						];
						$total_post_count = get_total_post_count($args);
						$args = [
							'posts_per_page' => 5,
							'paged' => 1,
							'exclude' => array($featured_post_arr['id']),
							'count' => 1,
							'total_post_count' => $total_post_count
						];
						$recent_posts_arr = recent_posts_function($args);
						echo $recent_posts_arr['code']; ?>
					</div>
					<div class="row mt-4 mb-5">
						<div class="col-12 d-flex justify-content-center">
							<?php if ($recent_posts_arr['post_count'] != $total_post_count): ?>
							<button class="load-more-button" data-paged="2" data-posts_per_page="5" data-tag="" data-search-term="" data-exclude="<?php echo $featured_post_arr['id']; ?>" data-tab="featured" data-total-post-count="<?php echo $total_post_count ?>" type="button"> See More </button>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="tab-pane fade internet-search" id="internet-search" role="tabpanel" aria-labelledby="internet-search-tab">
					<div class="featured-post row mb-5">
						<?php 
						$args = [
							'tag' => 'internet'
						];
						$featured_post_arr = get_featured_post($args);
						echo $featured_post_arr['code']; ?>
					</div>	
					<div class="latest-posts-title row">
						<div class="col-12">
							<h3>Latest Posts</h3>
						</div>
					</div>
					<div class="latest-posts-wrapper">
						<?php 
						//get total post count
						$args = [
					        'exclude' => array($featured_post_arr['id']),
					        'tag' => 'internet'
						];
						$total_post_count = get_total_post_count($args);
						$args = [
							'posts_per_page' => 5,
							'paged' => 1,
							'exclude' => array($featured_post_arr['id']),
							'count' => 1,
							'tag' => 'internet',
							'total_post_count' => $total_post_count
						];
						$recent_posts_arr = recent_posts_function($args);
						echo $recent_posts_arr['code']; ?>
					</div>	
					<div class="row mt-4 mb-5">
						<div class="col-12 d-flex justify-content-center">
							<?php if ($recent_posts_arr['post_count'] != $total_post_count): ?>
							<button class="load-more-button" data-paged="2" data-posts_per_page="5" data-tag="internet" data-search-term="" data-exclude="<?php echo $featured_post_arr['id']; ?>" data-tab="internet" data-total-post-count="<?php echo $total_post_count ?>" type="button"> See More </button>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="tab-pane fade tv-search" id="tv-search" role="tabpanel" aria-labelledby="tv-search-tab">
					<div class="featured-post row mb-5">
						<?php 
						$args = [
							'tag' => 'tv'
						];
						$featured_post_arr = get_featured_post($args);
						echo $featured_post_arr['code']; ?>
					</div>	
					<div class="latest-posts-title row">
						<div class="col-12">
							<h3>Latest Posts</h3>
						</div>
					</div>
					<div class="latest-posts-wrapper">
						<?php 
						//get total post count
						$args = [
					        'exclude' => array($featured_post_arr['id']),
					        'tag' => 'tv'
						];
						$total_post_count = get_total_post_count($args);
						$args = [
							'posts_per_page' => 5,
							'paged' => 1,
							'exclude' => array($featured_post_arr['id']),
							'count' => 1,
							'tag' => 'tv',
							'total_post_count' => $total_post_count
						];
						$recent_posts_arr = recent_posts_function($args);
						echo $recent_posts_arr['code']; ?>
					</div>	
					<div class="row mt-4 mb-5">
						<div class="col-12 d-flex justify-content-center">
							<?php if ($recent_posts_arr['post_count'] != $total_post_count): ?>
							<button class="load-more-button" data-paged="2" data-posts_per_page="5" data-category="" data-tag="tv" data-search-term="" data-exclude="<?php echo $featured_post_arr['id']; ?>" data-tab="tv" data-total-post-count="<?php echo $total_post_count ?>" type="button"> See More </button>
							<?php endif; ?>	
						</div>
					</div>
				</div>
				<div class="tab-pane fade streaming-search" id="streaming-search" role="tabpanel" aria-labelledby="streaming-search-tab">
					<div class="featured-post row mb-5">
						<?php 
						$args = [
							'tag' => 'streaming'
						];
						$featured_post_arr = get_featured_post($args);
						echo $featured_post_arr['code']; ?>
					</div>	
					<div class="latest-posts-title row">
						<div class="col-12">
							<h3>Latest Posts</h3>
						</div>
					</div>
					<div class="latest-posts-wrapper">
						<?php 
						//get total post count
						$args = [
					        'exclude' => array($featured_post_arr['id']),
					        'tag' => 'streaming'
						];
						$total_post_count = get_total_post_count($args);
						$args = [
							'posts_per_page' => 5,
							'paged' => 1,
							'exclude' => array($featured_post_arr['id']),
							'count' => 1,
							'tag' => 'streaming',
							'total_post_count' => $total_post_count
						];
						$recent_posts_arr = recent_posts_function($args);
						echo $recent_posts_arr['code']; ?>
					</div>	
					<div class="row mt-4 mb-5">
						<div class="col-12 d-flex justify-content-center">
							<?php if ($recent_posts_arr['post_count'] != $total_post_count): ?>
							<button class="load-more-button" data-paged="2" data-posts_per_page="5" data-category="" data-tag="streaming" data-search-term="" data-exclude="<?php echo $featured_post_arr['id']; ?>" data-tab="streaming" data-total-post-count="<?php echo $total_post_count ?>" type="button"> See More </button>
							<?php endif; ?>	
						</div>
					</div>
				</div>
				<div class="tab-pane fade search" id="search" role="tabpanel" aria-labelledby="search-tab">
					<div class="latest-posts-title row">
						<div class="col-12">
							<h3></h3>
						</div>
					</div>
					<div class="latest-posts-wrapper">
					</div>	
					<div class="row mt-4 mb-5">
						<div class="col-12 d-flex justify-content-center">
							<button class="load-more-button" data-paged="2" data-tab="" data-posts_per_page="5" data-search-term="" data-total-post-count="" data-tag="" data-exclude="" type="button"> See More </button>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main><!-- #main -->

<?php
get_footer();
