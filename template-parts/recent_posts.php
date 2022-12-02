<?php 

function recent_posts_function($atts){

	extract( shortcode_atts( array(
		'posts_per_page' => 5,
		'paged' => 1,
		'tag' => false,
		'exclude' => [],
		'count' => 2,
		'search_term' => false
	), $atts ) );
	$posts_list = '';
	//get most recent monetized post
	$args = [
		'post_type' => 'post',
        'post_status'=> 'publish',
        'posts_per_page'=> $posts_per_page,
        'paged' => $paged,
	];
	if ($tag){
		$args['tax_query'][] = 
        [
            'taxonomy'  => 'post_tag',
            'field'     => 'slug',
            'terms'     => sanitize_title( $tag )
        ];
	}
	if (!empty($exclude)){
		$args['post__not_in'] = $exclude;
	}
	if ($search_term){
		$args['s'] = htmlspecialchars($search_term);
		$args['s'] = '"'.$args['s'].'"';
		//get total post count for load more
		$args2 = $args;
		$args2['posts_per_page'] = -1;
		$total_posts = get_posts($args2);
		$total_post_count = count($total_posts);
		$posts_list .= '<div style="display:none" class="search-total-posts">'.$total_post_count.'</div>';
	}
	$latest_posts = get_posts($args);
	foreach($latest_posts as $post): 
		$title = the_title_attribute(array('echo' => false, 'post'=>$post));
		$tags = get_the_tags($post->ID);
		$posts_list .= '<div class="latest-post row '.($count === 1 ? 'mt-4' :  'mt-5').'">
			<div class="col-12 col-md-4">
				<a class="post-thumbnail" href="'.esc_url( get_the_permalink($post->ID) ).'" aria-hidden="true" tabindex="-1">
					<img src="'.get_the_post_thumbnail_url($post->ID, 'post-thumbnail') .'" alt="'.$title.'">
				</a>
			</div>
			<div class="col-12 col-md-8">
				<div class="post-tags pt-1">';
				foreach($tags as $tag){
					$posts_list .= '<span class="post-tag light-text mr-2">'.$tag->name.'</span>';
				}
				$posts_list .= '</div>
				<h4 class="post-title"><a href="'. esc_url( get_the_permalink($post->ID) ).'" rel="bookmark">'.$title.'</a></h4>
				<div class="author-date">
					<span class="author light-text">By '.get_the_author_meta('first_name', $post->post_author).' '.get_the_author_meta('last_name', $post->post_author).'</span> &#183; <span class="date light-text">'.date("F j, Y", strtotime($post->post_date)).'</span>
				</div>	

				<p class="post-excerpt mt-3">'.sprintf("%s &hellip; ", word_count(get_the_excerpt($post->ID), 45)).'<a href="'.esc_url( get_the_permalink($post->ID) ).'">Read More</a></p>
			</div>
		</div>';
		$count++;
	endforeach;

	$return = [
		'code' => $posts_list,
		'post_count' => count($latest_posts)
	];
	return $return;	
}