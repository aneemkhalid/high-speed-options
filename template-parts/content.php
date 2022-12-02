<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package HSO
 */



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

// check for hmsdin tool elemetn on page
// build element if it exists
$hmsdin_sidebar_element = false;
if ( function_exists( 'get_field' ) ) {
	$pid = get_post();
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

?>
<?php $toc = (get_field('toc_toggle')) ? 'toc-sidebar' : ''; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class($toc); ?>>
	<header class="entry-header">
        <div class="post-tag-container">
		<?php
		$tags = get_the_tags();
		foreach($tags as $tag){
			echo '<div class="post-tag tag">'.$tag->name.'</div>';
		}
        if($format = get_field('format_filters')) :
            foreach($format as $item) : ?>
                <div class="post-format tag"><?php echo $item['label'] ?></div>
            <?php endforeach;
        endif; ?>
        </div>

        <?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title bridge-till-redesign">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="d-flex justify-content-between align-items-center mb-3">
				<a href="<?php echo $authorLink; ?>" class="entry-meta d-flex align-items-center">
								
					<div class="avatar-wrapper">
						<?php echo $author_image; ?> 
					</div>
	
					<div class="author-date">
						<?php
						echo '<div class="author">'.$authorName.'</div>';
						echo '<div class="date">'.get_the_date().'</div>';
						?>
					</div>
							
				</a><!-- .entry-meta -->
				<div class="entry-social d-flex align-items-center">
					<a id="facebook" href="https://www.facebook.com/sharer?u=<?php the_permalink() ?>&amp;t=<?php the_title(); ?>" target="_blank" rel="noopener noreferrer">
						<div class="social-icon" style="background-image: url('<?php echo get_template_directory_uri(). '/images/facebook_social_share.svg'; ?>');"></div>
					</a>
					<a id="twitter" href="https://twitter.com/intent/tweet?url=<?php the_permalink() ?>&amp;text=<?php the_title(); ?>" target="_blank" rel="noopener noreferrer">
						<div class="social-icon" style="background-image: url('<?php echo get_template_directory_uri(). '/images/twitter_social_share.svg'; ?>');"></div>
					</a>
					<a id="linkedin" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink() ?>" target="_blank" rel="noopener noreferrer">
						<div class="social-icon" style="background-image: url('<?php echo get_template_directory_uri(). '/images/linkedin_social_share.svg'; ?>');"></div>
					</a>
				</div><!-- .entry-meta -->
			</div>	
		<?php endif; ?>
	</header><!-- .entry-header -->
	
    <div class="content-container d-flex">
        <div class="main-content order-0">
            <?php 
            if (!wp_is_mobile()){
                hso_post_thumbnail(); 
            }
            ?>

            <?php if( get_field('toc_toggle') || $hmsdin_sidebar_element) : ?>
                <div class="entry-content d-flex flex-column sidebar-added">
            <?php else : ?>	
                 <div class="entry-content d-flex flex-column">   
             <?php endif; ?> 

                
                <?php 
                // Insert Advertiser Disclosure Block
                require get_theme_file_path( '/template-parts/blocks/disclaimer-box.php' ); 
                
                the_content(
                    sprintf(
                        wp_kses(
                            /* translators: %s: Name of current post. Only visible to screen readers */
                            __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'hso' ),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        wp_kses_post( get_the_title() )
                    )
                );
                ?>

                <!-- If TOC toggle move disclaimer block behind ez-toc-container after TOC render -->
                <?php if( get_field('toc_toggle') ) : ?>
                    <script>
                        var toc_toggle_on_page = <?php echo get_field('toc_toggle') ?>;
                
                    </script>
                <?php endif; ?>
                
                <!-- Insert zip search automatically if desired -->
                <?php require get_theme_file_path( '/template-parts/blocks/zip-search.php' ); ?>
            </div><!-- .entry-content -->
			<div class="share-post d-flex mb-4 align-items-center">
				<h6 class="mb-0">Share this post:</h6>
				<div class="social_share">
					<a id="facebook-icon-blog-bottom" href="https://www.facebook.com/sharer?u=<?php the_permalink() ?>&amp;t=<?php the_title(); ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo get_template_directory_uri() ?>/images/facebook_social_share.svg"></a>
					<a id="twitter-icon-blog-bottom" href="https://twitter.com/intent/tweet?url=<?php the_permalink() ?>&amp;text=<?php the_title(); ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo get_template_directory_uri() ?>/images/twitter_social_share.svg"></a>
					<a id="linkedin-icon-blog-bottom" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink() ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo get_template_directory_uri() ?>/images/linkedin_social_share.svg"></a>
				</div>
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
					<?php if($authorbio) : ?>
                    	<div class="mt-2 bio-text"><?php echo $authorbio; ?></div>
					<?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

	<?php if( get_field('toc_toggle') || $hmsdin_sidebar_element) : ?>
            
		<div class="right order-1">
			<div class="toc_wrapper">
			<?php if(get_field('toc_toggle')): ?>
				<?php echo do_shortcode('[toc]'); ?>
				<style>
                    <?php 
                    

                    if( !get_field('toc_heading') ) {
                        $toc_title = 'Table of Contents'; 
                    } else {
                       $toc_title = get_field("toc_heading"); 
                    }
                    ?>

					.content-container .right .ez-toc-title-container:before, .content-container .main-content .ez-toc-title-container:before{
						position: absolute;
						content:'<?php echo addslashes($toc_title); ?>';
						padding: 10px 15px 10px 25px;
						margin-bottom: 0;
						width: 100%;
						top: 0;
						left: 0;
                        font-size: 18px;
                        font-weight: bold;
                        line-height: 1.3;
                        color: #444444;
                        padding-left: 20px;
					}
                    .content-container .right .ez-toc-title, .content-container .main-content .ez-toc-title {
                        visibility: hidden;
                    }
            
				</style>


			<?php endif; ?>
			<?php if($hmsdin_sidebar_element): ?>
				<div class="hsmdin-toole-wrap" id="hmsdin-sidebar">
					<?php 
					$title = $blocks[$key]['attrs']['data']['hmsdin_title'];
					$description = $blocks[$key]['attrs']['data']['hmsdin_description'];
					$button_text = $blocks[$key]['attrs']['data']['hmsdin_button_text'];
					$button_link = $blocks[$key]['attrs']['data']['hmsdin_button_link'];
					$show_speeds = $blocks[$key]['attrs']['data']['hmsdin_show_speed_descriptions'];
					?>
					<p class="hmsdin-title"><?php echo $title; ?></p>
					<p class="hmsdin-description"><?php echo $description; ?></p>
					<a href="<?php echo $button_link; ?>" class="cta_btn"><?php echo $button_text; ?></a>
					<?php 
					if($show_speeds): ?>
						<div class="hmsdin-speed-cont">
							<?php
							$speed_descriptions = $blocks[$key]['attrs']['data']['hmsdin_speed_descriptions'];	
							for( $i = 0; $i <= $speed_descriptions; $i++){
								echo '<h5>' . $blocks[$key]['attrs']['data']['hmsdin_speed_descriptions_' . $i . '_title']	 . '</h5>';
								echo '<p>' . $blocks[$key]['attrs']['data']['hmsdin_speed_descriptions_' . $i . '_description'] . '</p>';
							} ?>
						</div>
					<?php endif;?>
				</div>
			<?php endif; ?>
			</div>
		</div>
        
	<?php else : ?>	

		<style>
			#ez-toc-container{ display: none !important; }
		</style>

	<?php endif; ?>
       
       </div>
    </div>

	</div>

</article><!-- #post-<?php the_ID(); ?> -->