<?php 

$id = get_the_ID();
$post_data = [];
$overview_post_data = [];
$plans_post_data = [];
$overview_feat_ids = null;
$plans_feat_ids = null;
$feat_posts_on = false;
$args_container = $args['container'] ? '<div class="container">' : '';
$args_class = $args['class'] ? $args['class'] : '';

$featured_posts_html = array();

$plan_page = isset($_GET['plans']);

function feat_post_html($post_data, $custom_classes = '', $args_cont = '', $args_classes = '' ){

    $html = '<section class="related-posts ' . $args_classes . ' ' . $custom_classes . '">';
    $html .= $args_cont;
    $html .= '<div class="header-container">' .
            '<h3>Featured Posts</h3>' .
            '<a href="/resources">Read More</a>' .
        '</div>';

        if ($post_data) :
            $html .= '<div class="post-container">';
                
                foreach($post_data as $item) :
                    $html .= 
                    '<a href="' . $item['link'] . '" class="posts">' .
                        wp_get_attachment_image( $item['img'], 'large' ) .
                        '<div class="posts-content">' .
                            '<h4> '. $item['title'] . '</h4>' .
                            '<span>By ' . $item['author'] . ' - ' . $item['date'] . '</span>' .
                        '</div>' .
                    '</a>';
                endforeach;
                
            $html .= '</div>';
        else :
            $html .= '<p>' . _e( 'Sorry, no posts matched your criteria.' ) . '</p>';
        endif;

        if( $args_cont ):
            $html .= '</div>'; 
        endif;

    $html .= '</section>';

    return $html;
}


function featured_post_override_get_posts($feat_ids){
    $feat_post_data = [];

    //Sort featured posts by date
    $posts = get_posts(array(
        'post_type' => 'post',
        'orderby'   => 'post_date',
        'post__in'	=> $feat_ids,
    ));

    foreach($posts as $item) {
        $pid = $item->ID;

        $author = get_field('article_authors_dropdown', $pid);
        if($author){
            $authorName = get_the_title($author);
        } else{
            $authorName = get_the_author_meta('first_name', $pid).' '.get_the_author_meta('last_name', $pid);
        }

        $feat_post_data[] = [
            'title' => get_the_title($pid),
            'link' => get_permalink($pid),
            'img' => get_post_thumbnail_id($pid),
            'author' => $authorName,
            'date' => get_the_date('F j, Y', $pid),
        ];
    }

    return $feat_post_data;
    wp_reset_postdata();
}


// conditionals for provider pages
if( !empty(get_field('enable_overview_featured_posts', $id)) && 
    !empty(get_field('overview_featured_posts', $id)) && 
    count(get_field('overview_featured_posts', $id)) == 3){

        $overview_feat_ids = get_field('overview_featured_posts', $id);
        $overview_post_data = featured_post_override_get_posts($overview_feat_ids);
        
        $featured_posts_html['overview'] = feat_post_html($overview_post_data, 'related-posts-overview', $args_container, $args_class);
}
if( !empty(get_field('enable_plans_featured_posts', $id)) &&
    !empty(get_field('plans_featured_posts', $id)) && 
    count(get_field('plans_featured_posts', $id)) == 3){

        $plans_feat_ids = get_field('plans_featured_posts', $id);
        $plans_post_data = featured_post_override_get_posts($plans_feat_ids);
        
        $featured_posts_html['plans'] = feat_post_html($plans_post_data, 'related-posts-plans', $args_container, $args_class);
}

if( !empty(get_field('featured_posts_override', $id)) && count(get_field('featured_posts_override', $id)) == 3 &&
( get_field('enable_overview_featured_posts', $id) != true || get_field('enable_plans_featured_posts', $id) != true ) ){
    
    $feat_ids = get_field('featured_posts_override', $id);
    $post_data = featured_post_override_get_posts($feat_ids);
    $feat_posts_on = true;

    $featured_posts_html['featured'] = feat_post_html($post_data, '', $args_container, $args_class );
}


if( count($featured_posts_html) < 2 ){
    // the query
    $wpb_all_query = new WP_Query(array(
        'post_type' => 'post',
        'post_status'=>'publish',
        'posts_per_page'=> 3,
        'order' => 'DESC',
        'post__not_in'=> array($id),
    ));

    if ( $wpb_all_query->have_posts() ) {

        while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post();
            $pid = get_the_ID();

            $author = get_field('article_authors_dropdown', $pid);
            if($author){
                $authorName = get_the_title($author);
            } else{
                $authorName = get_the_author_meta('first_name', $pid).' '.get_the_author_meta('last_name', $pid);
            }

            $post_data[] = [
                'title' => get_the_title($pid),
                'link' => get_permalink($pid),
                'img' => get_post_thumbnail_id($pid),
                'author' => $authorName,
                'date' => get_the_date('F j, Y', $pid),
            ];

        endwhile;
        wp_reset_postdata();
    }

    $featured_posts_html['default'] = feat_post_html($post_data, '', $args_container, $args_class);
}

if( $plan_page == 'show' ){
    if( array_key_exists('plans', $featured_posts_html) && !empty($featured_posts_html['plans']) ){
        echo $featured_posts_html['plans'];
    } elseif( array_key_exists('featured', $featured_posts_html) && !empty($featured_posts_html['featured']) ){
        echo $featured_posts_html['featured'];
    } else{
        echo $featured_posts_html['default'];
    }
} else{
    if( array_key_exists('overview', $featured_posts_html) && !empty($featured_posts_html['overview']) ){
        echo $featured_posts_html['overview'];
    } elseif( array_key_exists('featured', $featured_posts_html) && !empty($featured_posts_html['featured']) ){
        echo $featured_posts_html['featured'];
    } else{
        echo $featured_posts_html['default'];
    }
}

?>
