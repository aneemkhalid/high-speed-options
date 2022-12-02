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
 * template Name: Template - Satellite Page
 * Template Post Type: internet-pages, tv-pages, bundle-pages
 * @package HSO
 */

$author = get_field('article_authors_dropdown');

if($author){
	$authorName = get_the_title($author);
	$author_type = get_field('author_type');
	$authorLink = get_permalink($author);
	$authorImageURL = get_the_post_thumbnail_url($author);
	$authorbio = get_field('bio', $author);
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


get_header();

	while ( have_posts() ) :
		the_post(); 
		// require get_theme_file_path( '/template-parts/breadcrumbs.php' );  ?>

		<section id="satellite_page_template">
			<div class="container">
				<div class="d-flex page-header-container">
					<div class="page-header-left">
                        <div class="breadcrumb-container">
                            <?php get_template_part( 'template-parts/breadcrumbs', null, array( 'exclude_advertiser_disclosure_link' => false ) ); ?>
                        </div>
						<div class="commercial_page_left_content_head">
							<h1><?php the_title() ?></h1>
                            <?php if($descrip = get_field('general_descriptions')) : ?>
                                <p><?php echo $descrip ?></p>
                            <?php endif; ?>
						</div>
						<div class="author-detail_wrapper">
							<a href="<?php echo $authorLink; ?>" class="author_info">
								<?php echo $author_image; ?>
								<div class="author_info_content">
									<div class="author"><?php echo $authorName ?></div>
									<div class="date"><?php echo get_the_date() ?></div>
								</div>
							</a>
						</div>

						
					</div>
                    <div class="feat-img-container">
                    <?php 
                        if(!wp_is_mobile()){
                            if(!empty(get_the_post_thumbnail_url())){ 
                                //echo '<img src="'.get_the_post_thumbnail_url().'" width="540" height="360">';
																echo wp_get_attachment_image(get_post_thumbnail_id(), 'large');
                            }
                        }
                    ?>
                    </div>
					
				</div>

                <div>
                    <?php the_content(); ?>
                </div>
				
				<?php
				if ($authorName): ?>
				<div class="author-bio d-flex align-items-center flex-wrap flex-md-nowrap pt-4 pb-4 mb-5"> 
					<a href="<?php echo $authorLink; ?>">
						<div class="avatar-wrapper flex-shrink-0 mr-4"> 
							<?php echo $author_image; ?> 
						</div>
					</a>
				
					<div class="author-info-mobile">
						<div class="light-text">Written By</div>
						<a href="<?php echo $authorLink; ?>">
							<div class="font-weight-bold"><?php echo $authorName; ?></div>
						</a>
					</div>
					<div class="bio pr-0 pr-md-5">
						<div class="author-info-desktop">
							<div class="light-text">Written By</div>
							<a href="<?php echo $authorLink; ?>">
								<div class="font-weight-bold author-title"><?php echo $authorName; ?></div>
							</a>
						</div>
						<?php if($authorbio) : ?>
							<div class="mt-2 bio-text"><?php echo $authorbio; ?></div>
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?>

				<?php get_template_part('/template-parts/related_posts'); ?>
			</div>

		</section>
	<?php 		
	endwhile;
get_footer();