<?php
use ZipSearch\BDAPIConnection as BDAPIConnection;
use ZipSearch\ProviderSearchController as ProviderSearchController;
use Dotenv\Dotenv as DotEnv;

function blog_load_more() {
	//get data
	include get_template_directory() . '/template-parts/recent_posts.php';
	$paged = isset( $_POST['paged'] ) ? $_POST['paged'] : false;
	$paged = sanitize_text_field( $paged );
	$search_term = isset( $_POST['search_term'] ) ? $_POST['search_term'] : false;
	$search_term = sanitize_text_field( $search_term );
	$posts_per_page = isset( $_POST['posts_per_page'] ) ? $_POST['posts_per_page'] : false;
	$posts_per_page = sanitize_text_field( $posts_per_page );
	$total_post_count = isset( $_POST['total_post_count'] ) ? $_POST['total_post_count'] : false;
	$total_post_count = sanitize_text_field( $total_post_count );
	$exclude = isset( $_POST['exclude'] ) ? $_POST['exclude'] : false;
	$exclude = sanitize_text_field( $exclude );
	$tag = isset( $_POST['tag'] ) ? $_POST['tag'] : false;
	$tag = sanitize_text_field( $tag );

	$args = array (
		'posts_per_page' => $posts_per_page,
		'paged' => $paged,
		'total_post_count' => $total_post_count,
		'tag' => $tag,
		'exclude' => array($exclude),
		'search_term' => $search_term
	);
	$new_paged = intval($paged) + 1;
	$new_paged = strval($new_paged);

	$recent_posts_load = recent_posts_function($args);

	$results = array(
		'paged_new' => $new_paged,
		'recent_posts_load' => $recent_posts_load['code'],
	);

	// output results
	print json_encode($results);
	// end processing
	wp_die();
}
// ajax hook for logged-in users: wp_ajax_{action}
add_action( 'wp_ajax_blog_load_more', 'blog_load_more' );
// ajax hook for non-logged-in users: wp_ajax_nopriv_{action}
add_action( 'wp_ajax_nopriv_blog_load_more', 'blog_load_more' );


function blog_search() {
	//get data
	include get_template_directory() . '/template-parts/recent_posts.php';
	$search_term = isset( $_POST['search_term'] ) ? $_POST['search_term'] : false;
	$search_term = sanitize_text_field( $search_term );

	$args = array (
		'search_term' => $search_term,
	);

	$recent_posts_load = recent_posts_function($args);

	$results = array(
		'recent_posts_load' => $recent_posts_load['code'],
	);

	// output results
	print json_encode($results);
	// end processing
	wp_die();
}
// ajax hook for logged-in users: wp_ajax_{action}
add_action( 'wp_ajax_blog_search', 'blog_search' );
// ajax hook for non-logged-in users: wp_ajax_nopriv_{action}
add_action( 'wp_ajax_nopriv_blog_search', 'blog_search' );

function zip_to_city($zipcode) {
	//get data
	if(!$zipcode) {
		$zip = isset( $_POST['zip'] ) ? $_POST['zip'] : false;
		$zip = sanitize_text_field( $zip );
	}
	else {
		$zip = $zipcode;
	}

	global $wpdb;
	$table_name = $wpdb->prefix . "zip_tract";

	//get city based on zip code
	$city_query = "SELECT usps_zip_pref_city, usps_zip_pref_state FROM $table_name WHERE zip = $zip LIMIT 1";
    $row = $wpdb -> get_results($city_query);
    $city = $row[0]->usps_zip_pref_city;
    $state = $row[0]->usps_zip_pref_state;
    $city = strtolower($city);
    $state = strtoupper($state);
    $state_lower = strtolower($state);
    $location = '';
    $url = '';
    if ($city != ''){
    	//check if that city is in backend
	    $args = array(
		    'numberposts'   => -1,
		    'post_type'     => 'locations',
		    'title'             => $city
		);
		$location = get_posts($args);

		if (!empty($location)){
			$parent_abbrev = get_field('abbreviation', $location[0]->post_parent);
			if ($state == $parent_abbrev){
				$url = get_the_permalink($location[0]->ID);
			}
		} else {
			$url = get_home_url().'/'.$state_lower.'/'.slugify($city);
		}
    }

	$results = array(
		'url' => $url,
		'city' => $city,
		'state' => $state
	);

	// output results
	if(!$zipcode) {
		print json_encode($results);
	}
	else {
		return json_encode($results);
	}
	// end processing
	wp_die();
}
// ajax hook for logged-in users: wp_ajax_{action}
add_action( 'wp_ajax_zip_to_city', 'zip_to_city' );
// ajax hook for non-logged-in users: wp_ajax_nopriv_{action}
add_action( 'wp_ajax_nopriv_zip_to_city', 'zip_to_city' );

//function to just save the bd api data for a given zip code (to have in case BD API shuts off service)
function saveBDAPIData() {
	//get data
	$zip = isset( $_POST['zip'] ) ? $_POST['zip'] : false;
	$zip = sanitize_text_field( $zip );
	$auth = (new ProviderSearchController)->get_auth();
	$result = (new BDAPIConnection)->get_api_providers_by_zip($zip, $auth);

	$return = array('content' => $result);
	// end processing
	wp_die();
}
// ajax hook for logged-in users: wp_ajax_{action}
add_action( 'wp_ajax_saveBDAPIData', 'saveBDAPIData' );
// ajax hook for non-logged-in users: wp_ajax_nopriv_{action}
add_action( 'wp_ajax_nopriv_saveBDAPIData', 'saveBDAPIData' );

function load_zip_search() {
	// Grab php file output from server
	$is_city = filter_var ($_POST['is_city'], FILTER_VALIDATE_BOOLEAN);
	$city_data = "";
	if($is_city){
		$city = isset( $_POST['city'] ) ? $_POST['city'] : false;
		$state = isset( $_POST['state'] ) ? $_POST['state'] : false;
		$zip_qual = false;
		$is_programmatic_city_page = isset( $_POST['is_programmatic_city_page'] ) ? $_POST['is_programmatic_city_page'] : false;
	}
	//Check for zip and type from post
	if(isset($_POST['zip'])) {
		$zipcode = isset( $_POST['zip'] ) ? $_POST['zip'] : false;
		$type = isset( $_POST['type'] ) ? $_POST['type'] : 'internet';
		$provider_id = isset( $_POST['provider'] ) ? $_POST['provider'] : false;
		$zip_qual = true;
		$city_data = zip_to_city($zipcode);
	}
	//error_log('HAS A ZIPCODE: ' . $zipcode);

	//ob_start();
	require get_template_directory() . '/inc/zip-loader.php';
	//$result = ob_get_contents();
	//ob_end_clean();
	$return = array('content' => $html, 'city' => $city_data);
	wp_send_json($return);
	wp_die();

}
// ajax hook for logged-in users: wp_ajax_{action}
add_action( 'wp_ajax_load_zip_search', 'load_zip_search' );
// ajax hook for non-logged-in users: wp_ajax_nopriv_{action}
add_action( 'wp_ajax_nopriv_load_zip_search', 'load_zip_search' );

// Function that Loads More Resource Posts
function more_post_ajax(){
    $ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] : 2;
    $paged = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 1;
    $offset = ($ppp * $page);
    header("Content-Type: text/html");

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $ppp,
        // 'offset'         => $offset,
		'paged' => $paged,
        'post_status' => 'publish',
        'orderby'  => 'date',
        'order' => 'DESC',
    );
	
    $loop = new WP_Query($args);

    if ($loop -> have_posts()) :  

		while ($loop -> have_posts()) : $loop -> the_post(); ?>

	<div class="box">
		<a href="<?php echo get_the_permalink(); ?>" class="inner">
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
					<?php }
							} ?>
				</div>
				<h4><?php the_title(); ?></h4>
				<?php $excerpt = get_the_excerpt(); 
							$excerpt = substr( $excerpt, 0, 125 ); ?>
				<p><?php echo $excerpt; ?> <span>Read More</span></p>
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
		endwhile;

    else:
    	$out = '';
    endif;
    
    wp_reset_postdata();
    die($out);
}

add_action('wp_ajax_nopriv_more_post_ajax', 'more_post_ajax');
add_action('wp_ajax_more_post_ajax', 'more_post_ajax');

function resources_filter_tags() {
	
	$selectedTags = $_POST['selectedTags'];
	$selectedTags = explode(',', $selectedTags);
	$formatTags = $_POST['selectedFormats'];
	$formatTags = explode(',', $formatTags);
	$paged = isset($_POST['pagenumber']) ? $_POST['pagenumber'] : 1;

    $args = array(
		'post_type' => 'post',
        'post_status' => 'publish',
		'posts_per_page' => 10,
		'paged'         => $paged,
		'orderby'  => 'date',
        'order' => 'DESC',
    );
	if( isset( $_POST['search_keyword'] ) && !empty( $_POST['search_keyword'] ) ){
		$args['s'] =  $_POST['search_keyword'];
	}

		if( !in_array( 'all',$selectedTags ) ) {
			if(!empty($selectedTags[0])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'post_tag',
						'field'    => 'slug',
						'terms'    => $selectedTags,
					),
				);
			}
		}
		if( !in_array( 'all', $formatTags ) ) {
			if(!empty($formatTags[0])) {
                $args['meta_query']['relation'] = 'OR';
				foreach( $formatTags as $formatTag ){
					$args['meta_query'][] = array(
						'key'     => 'format_filters',
						'value'   => $formatTag,
						'compare' => 'LIKE'
					);
				}
			}
		}

	$return_resources = "";
    $loop = new WP_Query($args);

    if ($loop -> have_posts()) :  
		while ($loop -> have_posts()) : $loop -> the_post();
			$return_resources .= '<div class="box">';
			$return_resources .= '<a  href="'.get_the_permalink().'" class="inner">';
			$return_resources .= '<div class="img_wrap">';
			$return_resources .= '<div class="post-thumbnail">';
			//$return_resources .= '<img src="'.get_the_post_thumbnail_url().'" alt="resource_featured_image">';
			$return_resources .= wp_get_attachment_image(get_post_thumbnail_id(), 'large');
			$return_resources .=  '</div>';
			$return_resources .=  '</div>';
			$return_resources .=  '<div class="content">';
			$return_resources .=  '<div>';
			$return_resources .=  '<div class="categories">';
			$tags = get_the_tags();
			foreach($tags as $tag) {
			$return_resources .= '<span>'.$tag->name.'</span>';
			}
			$format_filters = get_field('format_filters');
			if($format_filters){
			foreach($format_filters as $format_filter) {
				$return_resources .= '<span class="green-tag">'.$format_filter['label'].'</span>';
				}
			}
			$return_resources .= '</div>';
			$return_resources .= '<h4>'.get_the_title().'</h4>';
			$excerpt = get_the_excerpt(); 
			$excerpt = substr( $excerpt, 0, 125 );
			$return_resources .= '<p>'.$excerpt.' ... <span>Read More</span></p>';
			$return_resources .=  '</div>';
			$author = get_field('article_authors_dropdown');  
			if($author){
				$authorName = get_the_title($author);
			} else{
				$authorName = get_the_author();
			}
			$return_resources .= '<span>By '.$authorName.' '.get_the_date().'</span></div></a></div>';
	endwhile;
    else:
    	$return_resources = '';
    endif;
    
    wp_reset_postdata();
    die($return_resources);
}

add_action('wp_ajax_nopriv_resources_filter_tags', 'resources_filter_tags');
add_action('wp_ajax_resources_filter_tags', 'resources_filter_tags');


function resources_search_filter(){

	$selectedTags = $_POST['selectedTags'];
	if( !is_array( $selectedTags ) ){
		$selectedTags = explode(',', $selectedTags);
	}
	$formatTags = $_POST['selectedFormats'];
	$formatTags = explode(',', $formatTags);
	$paged = ( is_integer( $_POST['paged'] ) && is_integer( $_POST['paged'] ) ) ? $_POST['paged'] : 1;

		$args = array(
			'post_type' => 'post',
			'posts_per_page' => $_POST['ppp'],
			'paged' => $paged,
			'post_status' => 'publish',
			'orderby'  => 'date',
			'order' => 'DESC',
		);

		if( isset( $_POST['resource_keyword'] ) && !empty( $_POST['resource_keyword'] ) ){
            $args['s'] = $_POST['resource_keyword'];
		}

		if( !in_array( 'all', $selectedTags ) ){
			if( !empty( $selectedTags[0] ) ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'post_tag',
						'field'    => 'slug',
						'terms'    => $selectedTags,
					),
				);
			}
		}

		if( !in_array( 'all', $formatTags ) ) {
			if(!empty($formatTags[0])) {
				$args['meta_query']['relation'] = 'OR';
				foreach( $formatTags as $formatTag ){
					$args['meta_query'][] = array(
						'key'     => 'format_filters',
						'value'   => $formatTag,
						'compare' => 'LIKE'
					);
				}
			}
		}

		$resource_query = new WP_Query( $args );

		if ($resource_query -> have_posts()) :  
			while ($resource_query->have_posts()) : $resource_query->the_post();
			$return_resources .= '<div class="box">';
			$return_resources .= '<a href="'.get_the_permalink().'" class="inner">';
			$return_resources .= '<div class="img_wrap">';
			$return_resources .= '<div class="post-thumbnail">';
			//$return_resources .= '<img src="'.get_the_post_thumbnail_url().'" alt="resource_featured_image">';
			$return_resources .= wp_get_attachment_image(get_post_thumbnail_id(), 'large');
			$return_resources .=  '</div>';
			$return_resources .=  '</div>';
			$return_resources .=  '<div class="content">';
			$return_resources .=  '<div>';
			$return_resources .=  '<div class="categories">';
			$tags = get_the_tags();
			foreach($tags as $tag) {
			$return_resources .= '<span>'.$tag->name.'</span>';
			}
			$format_filters = get_field('format_filters');
			if($format_filters){
			foreach($format_filters as $format_filter) {
				$return_resources .= '<span class="green-tag">'.$format_filter['label'].'</span>';
				}
			}
			$return_resources .= '</div>';
			$return_resources .= '<h4>'.get_the_title().'</h4>';
			$excerpt = get_the_excerpt(); 
			$excerpt = substr( $excerpt, 0, 125 );
			$return_resources .= '<p>'.$excerpt.' ... <span>Read More</span></p>';
			$return_resources .=  '</div>';
			if($author){

				$authorName = get_the_title($author);
				} else{
				$authorName = get_the_author();
				}
				$return_resources .= '<span>By '.$authorName.' '.get_the_date().'</span></div></a></div>';

	        endwhile;
		else:
			$return_resources = '';
		endif;

		wp_reset_postdata();
		die($return_resources);
}
add_action('wp_ajax_nopriv_resources_search_filter', 'resources_search_filter');
add_action('wp_ajax_resources_search_filter', 'resources_search_filter');


function resources_search_filter_load_more(){

	$selectedTags = $_POST['selectedTags'];
	if( !is_array( $selectedTags ) ){
		$selectedTags = explode(',', $selectedTags);
	}

	$formatTags = $_POST['selectedFormats'];
	$formatTags = explode(',', $formatTags);
	$paged = isset( $_POST['paged'] ) ? $_POST['paged'] : 1;
	$offest = intval( $_POST['ppp'] ) * intval( $paged );

		$args = array(
			'post_type' => 'post',
			'posts_per_page' => $_POST['ppp'],
			'paged' => $paged,
			// 'offset' => $offest,
			'post_status' => 'publish',
			'orderby'  => 'date',
			'order' => 'DESC',
		);

		if( isset( $_POST['resource_keyword'] ) && !empty( $_POST['resource_keyword'] ) ){
            $args['s'] = $_POST['resource_keyword'];
		}

		if( !in_array( 'all', $selectedTags ) ){
			if( !empty( $selectedTags[0] ) ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'post_tag',
						'field'    => 'slug',
						'terms'    => $selectedTags,
					),
				);
			}
		}

		if( !in_array( 'all', $formatTags ) ) {
			if(!empty($formatTags[0])) {
				$args['meta_query']['relation'] = 'OR';
				foreach( $formatTags as $formatTag ){
					$args['meta_query'][] = array(
						'key'     => 'format_filters',
						'value'   => $formatTag,
						'compare' => 'LIKE'
					);
				}
			}
		}

		$resource_query = new WP_Query( $args );

		if ($resource_query -> have_posts()) :  
			while ($resource_query->have_posts()) : $resource_query->the_post();
			$return_resources .= '<div class="box">';
			$return_resources .= '<a href="'.get_the_permalink().'" class="inner">';
			$return_resources .= '<div class="img_wrap">';
			$return_resources .= '<div class="post-thumbnail">';
			//$return_resources .= '<img src="'.get_the_post_thumbnail_url().'" alt="resource_featured_image">';
			$return_resources .= wp_get_attachment_image(get_post_thumbnail_id(), 'large');
			$return_resources .=  '</div>';
			$return_resources .=  '</div>';
			$return_resources .=  '<div class="content">';
			$return_resources .=  '<div>';
			$return_resources .=  '<div class="categories">';
			$tags = get_the_tags();
			foreach($tags as $tag) {
			$return_resources .= '<span>'.$tag->name.'</span>';
			}
			$format_filters = get_field('format_filters');
			if($format_filters){
			foreach($format_filters as $format_filter) {
				$return_resources .= '<span class="green-tag">'.$format_filter['label'].'</span>';
				}
			}
			$return_resources .= '</div>';
			$return_resources .= '<h4>'.get_the_title().'</h4>';
			$excerpt = get_the_excerpt(); 
			$excerpt = substr( $excerpt, 0, 125 );
			$return_resources .= '<p>'.$excerpt.' ... <span>Read More</span></p>';
			$return_resources .=  '</div>';
			if($author){

				$authorName = get_the_title($author);
				} else{
				$authorName = get_the_author();
				}
				$return_resources .= '<span>By '.$authorName.' '.get_the_date().'</span></div></a></div>';

	        endwhile;
	        // $return_resources .= '<input type="hidden" class="pageNumber" id="paged" value="'.$paged.'">';
		else:
			$return_resources = '';
		endif;

		wp_reset_postdata();
		die($return_resources);
}
add_action('wp_ajax_nopriv_resources_search_filter_load_more', 'resources_search_filter_load_more');
add_action('wp_ajax_resources_search_filter_load_more', 'resources_search_filter_load_more');


function author_load_more_posts(){
	$paged = isset( $_POST['authorPageNumber'] ) ? $_POST['authorPageNumber'] : 0;
	$offest = intval( $_POST['postperpage'] ) * intval( $paged );

		$args = array(
			'post_type' => 'post',
			'posts_per_page' => $_POST['postperpage'],
			'offset' => $offest,
			'post_status' => 'publish',
			'orderby'  => 'date',
			'order' => 'DESC',
			'meta_key' => 'article_authors_dropdown',
			'meta_value' => $_POST['authorid']
		);

		$resource_query = new WP_Query( $args );

		if ($resource_query -> have_posts()) :  
			while ($resource_query->have_posts()) : $resource_query->the_post();
			$return_resources .= '<div class="box">';
			$return_resources .= '<a href="'.get_the_permalink().'" class="inner">';
			$return_resources .= '<div class="img_wrap">';
			$return_resources .= '<div class="post-thumbnail">';
			//$return_resources .= '<img src="'.get_the_post_thumbnail_url().'" alt="resource_featured_image">';
			$return_resources .= wp_get_attachment_image(get_post_thumbnail_id(), 'large');
			$return_resources .=  '</div>';
			$return_resources .=  '</div>';
			$return_resources .=  '<div class="content">';
			$return_resources .=  '<div>';
			$return_resources .=  '<div class="categories">';
			$tags = get_the_tags();
			foreach($tags as $tag) {
			$return_resources .= '<span>'.$tag->name.'</span>';
			}
			$format_filters = get_field('format_filters');
			if($format_filters){
			foreach($format_filters as $format_filter) {
				$return_resources .= '<span class="green-tag">'.$format_filter['label'].'</span>';
				}
			}
			$return_resources .= '</div>';
			$return_resources .= '<h4>'.get_the_title().'</h4>';
			$excerpt = get_the_excerpt(); 
			$excerpt = substr( $excerpt, 0, 125 );
			$return_resources .= '<p>'.$excerpt.' ... <span>Read More</span></p>';
			$return_resources .=  '</div>';
			if($author){
			$authorName = get_the_title($author);
			} else{
			$authorName = get_the_author();
			}
			$return_resources .= '<span>By '.$authorName.' '.get_the_date().'</span></div></a></div>';
	        endwhile;
	        // $return_resources .= '<input type="hidden" class="pageNumber" id="paged" value="'.$paged.'">';
		else:
			$return_resources = '';
		endif;

		wp_reset_postdata();
		die($return_resources);
}
add_action('wp_ajax_nopriv_author_load_more_posts', 'author_load_more_posts');
add_action('wp_ajax_author_load_more_posts', 'author_load_more_posts');


function comparisons_load_more_posts(){
	/**
	 * Get the total number of the records (tr)
	 */
	$tr_args = array(
		'post_type' => 'comparisons',
		'post_status' => 'publish',
		'orderby'  => 'date',
		'order' => 'DESC',
	);

	$tr_query = new WP_Query( $tr_args );
	$total_rec = $tr_query->post_count;

	$paged = isset( $_POST['compPageNumber'] ) ? $_POST['compPageNumber'] : 0;
	$offest = intval( $_POST['postperpage'] ) * intval( $paged );

		$args = array(
			'post_type' => 'comparisons',
			'posts_per_page' => $_POST['postperpage'],
			'offset' => $offest,
			'post_status' => 'publish',
			'orderby'  => 'date',
			'order' => 'DESC',
		);

		$comparison_query = new WP_Query( $args );
		$current_total_rec = $_POST['postperpage'] *  ( $paged + 1 );

		if ($comparison_query -> have_posts()) :  
			while ($comparison_query->have_posts()) : 
				$comparison_query->the_post();
				$return_comparison .= '<a href="'.get_permalink().'" class="box common-box d-flex justfy-content-start mb-4">';
				$return_comparison .= '<div class="img_wrap">';
				$return_comparison .= '<div class="post-thumbnail">';
				$return_comparison .= '<img src="'.get_the_post_thumbnail_url().'">';
				$return_comparison .= '</div>';
				$return_comparison .= '</div>';
				$return_comparison .= '<div class="content d-flex">';
				$return_comparison .= '<div>';
				$return_comparison .= '<h4 class="mb-2">'.get_the_title().'</h4>';

				$excerpt = get_the_excerpt(); 
				$excerpt = substr( $excerpt, 0, 120 );
				if(($current_total_rec!=0)){
					$return_comparison .= '<p class="mb-4">'.$excerpt.' …<span>Read More</span></p>';
				}

				$return_comparison .= '</div>';

				$author = get_field('article_authors_dropdown');
				
				if($author){
					$authorName = get_the_title($author);
				} else{
					$authorName = get_the_author();
				}

				$return_comparison .= '<span class="align-self-end">By '.$authorName.' · '.get_the_date().'</span>';
				$return_comparison .= '</div>';
				$return_comparison .= '</a>';
	
	        endwhile;
			
			if($current_total_rec < $total_rec ):
				$return_loadmore = '<div class="comparisons-posts-btn-wrap text-center"><a href="#" class="comp-view-more text-center">View More</a></div>';
			endif;
	        // $return_comparison .= '<input type="hidden" class="pageNumber" id="paged" value="'.$paged.'">';
		else:
			$return_comparison = '';
		endif;
		wp_reset_postdata();
		$return['comparison']	=	$return_comparison;
		$return['loadmore']		=	$return_loadmore;
		echo json_encode($return);
		die();
		// die($return_comparison);
}
add_action('wp_ajax_nopriv_comparisons_load_more_posts', 'comparisons_load_more_posts');
add_action('wp_ajax_comparisons_load_more_posts', 'comparisons_load_more_posts');


function get_select_providers(){

	$pid = $_POST['pid'];

	$args = array(
		'post_type' => 'comparisons',
		'post_status' => 'publish',
		'posts_per_page'=> -1,
		'orderby'  => 'date',
		'order' => 'DESC',
			'meta_query' => array(
			    'relation' => 'OR',
				array(
					'key' 		=> 'provider_1',
					'value'		=> $pid,
					'compare'	=> '='
				),
				array(
					'key' 		=> 'provider_2',
					'value'		=> $pid,
					'compare'	=> '='
				),
			),
	);

	$provider_query = new WP_Query( $args );

	if ($provider_query -> have_posts()) :  
		while ($provider_query->have_posts()) : 
			$provider_query->the_post();

			$providerId1 = get_field('provider_1');
			$providerId2 = get_field('provider_2');
			if($providerId1 == $pid){
				echo '<li class="second-provider-generator" data-value="'.$providerId2.'"><span class="d-flex align-items-center justify-content-center">'.get_the_title($providerId2).'</span></li>';
			} else{
				echo '<li class="second-provider-generator" data-value="'.$providerId1.'"><span class="d-flex align-items-center justify-content-center">'.get_the_title($providerId1).'</span></li>';
			}
		endwhile;
	else:
		echo '';
	endif;
	wp_reset_postdata();
	die();
	
}
add_action('wp_ajax_nopriv_get_select_providers', 'get_select_providers');
add_action('wp_ajax_get_select_providers', 'get_select_providers');



function get_search_comparisons(){
	$pid1 = isset($_POST['pid1'])? $_POST['pid1'] :0;
	$pid2 = isset($_POST['pid2'])? $_POST['pid2'] :0;

	$args = array(
		'post_type' => 'comparisons',
		'post_status' => 'publish',
		'posts_per_page'=> -1,
		'orderby'  => 'date',
		'order' => 'DESC',
			'meta_query' => array(
			    'relation' => 'OR',
				array(
					'key' 		=> 'provider_1',
					'value'		=> $pid2,
					'compare'	=> '='
				),
				array(
					'key' 		=> 'provider_2',
					'value'		=> $pid2,
					'compare'	=> '='
				),
				array(
					'relation' => 'OR',
				array(
					'key' 		=> 'provider_1',
					'value'		=> $pid1,
					'compare'	=> '='
				),
				array(
					'key' 		=> 'provider_2',
					'value'		=> $pid1,
					'compare'	=> '='
				),
				),
			),
	);

	$comparison_query = new WP_Query( $args );

		if ($comparison_query -> have_posts()) :  
			while ($comparison_query->have_posts()) : $comparison_query->the_post();

			$providerId1 = get_field('provider_1');
			$providerId2 = get_field('provider_2');

			if($providerId1 == $pid1 && $providerId2 == $pid2){
				
				$permalink = get_permalink();
				$title = get_the_title();
				$thumbnail = get_the_post_thumbnail_url();
				$excerpt = get_the_excerpt();
				$author = get_field('article_authors_dropdown');   

			} elseif($providerId1 == $pid2 && $providerId2 == $pid1){

				$permalink = get_permalink();
				$title = get_the_title();
				$thumbnail = get_the_post_thumbnail_url();
				$excerpt = get_the_excerpt();
				$author = get_field('article_authors_dropdown');
			}

	        endwhile;

			$return_comparison .= $permalink;

		else:
			$return_comparison = '';
		endif;

		wp_reset_postdata();
		die($return_comparison);

}
add_action('wp_ajax_nopriv_get_search_comparisons', 'get_search_comparisons');
add_action('wp_ajax_get_search_comparisons', 'get_search_comparisons');

