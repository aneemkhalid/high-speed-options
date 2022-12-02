<?php
function get_featured_post($atts){
	//get most recent monetized post
	extract( shortcode_atts( array(
		'tag' => false,
	), $atts ) );
	$featured_post_code = '';

	$args = [
		'post_type' => 'post',
        'post_status'=> 'publish',
        'posts_per_page'=> 1,
        'tax_query' => [
            [
                'taxonomy' => 'category',
                'terms' => 10
            ],
        ],
	];
	if ($tag){
		$args['tax_query'][] = 
		[
            'taxonomy'  => 'post_tag',
            'field'     => 'slug',
            'terms'     => sanitize_title( $tag )
        ];
	}
	$featured_post = get_posts($args);
	$title = the_title_attribute(array('echo' => false, 'post'=>$featured_post[0]));
	$tags = get_the_tags($featured_post[0]->ID);
	$featured_post_code .= '<div class="col-12 col-md-5">
		<a class="post-thumbnail" href="'.esc_url( get_the_permalink($featured_post[0]->ID) ).'" aria-hidden="true" tabindex="-1">
			<img src="'.get_the_post_thumbnail_url($featured_post[0]->ID, 'post-thumbnail').'" alt="'. $title.'">
		</a>
	</div>
	<div class="col-12 col-md-7">
		<div class="post-tags pt-3">';
		foreach($tags as $tag){
			$featured_post_code .= '<span class="post-tag light-text mr-2">'.$tag->name.'</span> ';
		}
		$featured_post_code .= '</div>
		<h4 class="post-title"><a href="'.esc_url( get_the_permalink($featured_post[0]->ID) ).'" rel="bookmark">'. $title.'</a></h4>
		<div class="author-date">
			<span class="author light-text">By '.get_the_author_meta('first_name', $featured_post[0]->post_author).' '.get_the_author_meta('last_name', $featured_post[0]->post_author).'</span> &#183; <span class="date light-text">'.date("F j, Y", strtotime($featured_post[0]->post_date)).'</span>
		</div>	
		<p class="post-excerpt mt-3">'.sprintf("%s &hellip; ", word_count(get_the_excerpt($featured_post[0]->ID), 45)).'<a href="'.esc_url( get_the_permalink($featured_post[0]->ID) ).'">Read More</a></p>
	</div>';
	$return = [
		'code' => $featured_post_code,
		'id' => $featured_post[0]->ID
	];

	return $return;
}