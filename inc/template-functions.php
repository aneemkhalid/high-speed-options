<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package HSO
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function hso_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

    global $post;
    $id = $post->ID;

    $show_plans = get_field('show_plans_page', $id);
    $plan_page = isset($_GET['plans']);

    if($show_plans && $plan_page) {
        $classes[] = 'provider-plan-page';
    }

	return $classes;
}
add_filter( 'body_class', 'hso_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function hso_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'hso_pingback_header' );

add_action('init', 'hso_blocks_style');
function hso_blocks_style() {
    register_block_style('core/table', [
        'name' => 'hso-table',
        'label' => __('HSO Minimal Table', 'hso'),
    ]);
};

function custom_scripts() {
    wp_enqueue_script( 'js-functions', get_template_directory_uri() . '/src/js/js-functions.js', array('jquery') );
    wp_enqueue_script( 'main', get_template_directory_uri() . '/build/index.js', array('jquery', 'js-functions') );
    //Load AJAX scripts
    $script_url = get_template_directory_uri() . '/src/js/hso-ajax.js';

    wp_enqueue_script( 'hso-ajax', $script_url, array( 'jquery', 'main', 'js-functions' ) );
    // define ajax url
    $ajax_url = admin_url( 'admin-ajax.php' );
    $site_environment = wp_get_environment_type();
    $theme_path = get_template_directory_uri();
    $script = array('ajaxurl' => $ajax_url, 'site_environment' => $site_environment, 'theme_path' => $theme_path);
    // localize script
    wp_localize_script( 'hso-ajax', 'hso_ajax', $script );


    //switching to hotjar feedback
   // wp_enqueue_script( 'appzi', 'https://w.appzi.io/w.js?token=jA8Qg', null, null, true );

    global $is_programmatic_city_page;
    if (!$is_programmatic_city_page && !is_page('zip-search') && !is_singular('locations')):
        if ('development' === wp_get_environment_type()){
            wp_enqueue_script( 'header-scripts', get_template_directory_uri() . '/src/js/header-scripts-delayed-dev.js' );

        }
        elseif( 'staging' === wp_get_environment_type() || 'production' === wp_get_environment_type()) {
            wp_enqueue_script( 'header-scripts', get_template_directory_uri() . '/src/js/header-scripts-delayed.js' );

        }
        wp_enqueue_script( 'footer-scripts', get_template_directory_uri() . '/src/js/footer-scripts-delayed.js', array(), false, true );
    endif;    

}
add_action( 'wp_enqueue_scripts', 'custom_scripts' );

function acf_load_buyer_field_choices( $field ) {
    
    $field['choices'] = array();
    if (isset($_GET['post'])){
        $current_provider =  $_GET['post'];
        if ($current_provider){
            $buyer_ids = get_posts(array(
                'fields'          => 'ids',
                'posts_per_page'  => -1,
                'post_type' => 'build-buyer'
            ));
            $provider_campaigns = [];
            foreach($buyer_ids as $buyer_id){
                $campaigns = get_field('campaign', $buyer_id);
                foreach($campaigns as $campaign){
                    if ($campaign['campaign_name'] == $current_provider){
                        $title = get_the_title($buyer_id);
                        $title_mod = str_replace(' ', '_', strtolower($title));
                        $provider_campaigns[$buyer_id] = $title;
                    }
                }
            }
            $provider_campaigns = array_map('trim', $provider_campaigns);
            if( is_array($provider_campaigns) ) {
                foreach( $provider_campaigns as $key => $provider_campaign ) {
                    $field['choices'][ $key ] = $provider_campaign;
                }
            }
        }
    }

    // return the field
    return $field;
    
}

add_filter('acf/load_field/name=buyer', 'acf_load_buyer_field_choices');

if ( ! function_exists( 'mytheme_register_nav_menu' ) ) {
 
    function mytheme_register_nav_menu(){
        register_nav_menus( array(
            'services' => __( 'Services Menu', 'hso' ),
            'providers'  => __( 'Providers Menu', 'hso' ),
            'resources'  => __( 'Resources Menu', 'hso' ),
            'internet'  => __( 'Internet Menu', 'hso' ),
            'company'  => __( 'Company Menu', 'hso' ),
            'privacy'  => __( 'Privacy Menu', 'hso' ),
        ) );
    }
    add_action( 'after_setup_theme', 'mytheme_register_nav_menu', 0 );
}
function prepare_menu_items($menu_title) {
    // Obtain array of menu objects from the $menu_title
    $menu_items = wp_get_nav_menu_items( $menu_title );

    $prepared_menu_items = [];
    if( is_array( $menu_items ) ) {
        foreach($menu_items as $item) {
            // Values with no parent are highest level menu items
            if( !$item->menu_item_parent ) {
                $parent_obj = array();
                $parent_obj['id'] = $item->ID;
                $parent_obj['title'] = $item->title;
                $parent_obj['url'] = $item->url;
                $parent_obj['classes'] = implode( ' ', $item->classes );
                
                $prepared_menu_items[$item->ID] = $parent_obj;
            } else {
                $child_obj = array();
                $child_obj['id'] = $item->ID;
                $child_obj['title'] = $item->title;
                $child_obj['url'] = $item->url;
                $child_obj['classes'] = implode( ' ', $item->classes );
                
                $prepared_menu_items[$item->menu_item_parent]['submenu'][$item->ID] = $child_obj;
            }
        }
    }

    return $prepared_menu_items;
}
add_action('admin_menu', 'hso_add_submenu_page');
function hso_add_submenu_page(){
    add_submenu_page( 'tools.php', 'Provider CSV Import', 'Provider CSV Import', 'manage_options', 'provider-csv-import', 'provider_csv_import_page' ); 
}


function create_primary_nav_html($menu_title, $is_sidebar = false) {
    $menu = prepare_menu_items($menu_title);

    $selector = $is_sidebar ? '-sidebar' : '';
    $html = '<ul id="primary-menu'. $selector .'" class="primary-menu'. $selector .'">';
    $submenu_html = '';
    $nav_chevron = '<span class="material-icons chevron">chevron_right</span>';

    // featured resource element - configured in theme settings
    $enable_featured_resource = get_field('enable_featured_resource_nav_item', 'option');
    $featured_resource_group = get_field('featured_resource_nav_item', 'option');
    $featured_resource_image_id = null;
    $featured_resource_title = null;
    $featured_resource_description = null;
    $featured_resource_link = null;
    $featured_resource_class = null;

    if( $enable_featured_resource ){
        $featured_resource_image_id = $featured_resource_group['image']['id'];
        $featured_resource_title = $featured_resource_group['title'];
        $featured_resource_description = $featured_resource_group['description'];
        $featured_resource_link = $featured_resource_group['link'];
    }

    if( is_array($menu) ) {
        foreach($menu as $item) {
            extract($item);

            $html .= '<li id="menu-item-'. $id .'" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-'. $id . ' ' . $classes . '" data-parent-menu-title="'. strtolower($title) .'"><a href="'. $url .'" class="ripple-el">'. $title;

            if(array_key_exists('submenu', $item)) {
                $icon_text = $is_sidebar ? 'chevron_right' : 'expand_more';
                $html .= '<span class="material-icons">'. $icon_text .'</span>';
            }
            $html .= '</a>';
            if(array_key_exists('submenu', $item)) {
                // check if current menu item is using featured resource
                if( !empty($classes) && strpos($classes, 'menu-item-resource') >= 0 && $enable_featured_resource){
                    $featured_resource_class = ' featured-resource-active';
                }
                $html .= '<div class="submenu-container ' . $featured_resource_class . '">';
                $see_all_item = null;


                if($is_sidebar) {
                    // if using the featured resource submenu and it is enabled
                    if( !empty($classes) && strpos($classes, 'menu-item-resource') >= 0 && $enable_featured_resource){
                        $html .= 
                            '<div class="featured-resource-nav-item">' . 
                                '<a href="' . esc_url($featured_resource_link) . '" class="featured-resource-link">' .
                                    '<p class="featured-resource-title">' . $featured_resource_title . '</p>' . 
                                    '<div class="featured-resource-description-cont">' . $featured_resource_description . '</div>' . 
                                '</a>' . 
                            '</div>';
                    }
                    $html .= '<ul class="submenu" data-parent-menu-title="'. strtolower($title) .'">';
                } else {
                    $submenu_2_col = '';
                    if( count($item['submenu']) > 5 ){
                        $submenu_2_col = ' submenu-2-col';
                    }

                    // if using the featured resource submenu and it is enabled
                    if( !empty($classes) && strpos($classes, 'menu-item-resource') >= 0 && $enable_featured_resource){
                        $html .= 
                        '<div class="featured-resource-nav-item">' . 
                            '<a href="' . esc_url($featured_resource_link) . '" class="featured-resource-link">' .
                                wp_get_attachment_image($featured_resource_image_id, 'small') . 
                                '<p class="featured-resource-title">' . $featured_resource_title . '</p>' . 
                                '<div class="featured-resource-description-cont">' . $featured_resource_description . '</div>' . 
                            '</a>' . 
                        '</div>';
                    }
                    $html .= '<div class="submenu-inner">';
                    $html .= '<ul class="submenu'. $submenu_2_col .'">';
                }
                foreach($item['submenu'] as $k => $subitem) {
                    $subitem_id = $subitem['id'];
                    $subitem_title = $subitem['title'];
                    $subitem_url = $subitem['url'];
                    $subitem_classes = $subitem['classes'];

                    if($is_sidebar) {
                        $subitem_seeall = null;
                        if(!empty($subitem_classes) && strpos($subitem_classes, 'nav-item-see-all') >= 0){
                            $subitem_seeall = ' nav-item-see-all';
                        }
                        $html .= '<li id="submenu-item-'. $subitem_id .'" class="submenu-item submenu-item ripple-el submenu-item-'. $subitem_id . $subitem_seeall .'"><a href="'. $subitem_url .'">'. $subitem_title . '</a></li>';
                    } elseif( !empty($subitem_classes) && strpos($subitem_classes, 'nav-item-see-all') >= 0 ){
                        $see_all_item = '<a class="sub-menu-item nav-item-see-all" href="' . $subitem_url . '">' . $subitem_title . '</a>';
                    } else {
                        $html .= '<li id="submenu-item-'. $subitem_id .'" class="submenu-item submenu-item ripple-el submenu-item-'. $subitem_id . ' ' . $subitem_classes .'"><a href="'. $subitem_url .'"><span class="submenu-item-text">'. $subitem_title . '</span>' . $nav_chevron .'</a></li>';
                    }
                }
                if($is_sidebar) {
                    // $html .= '<li id="submenu-item-back-'. strtolower($title) .'" class="submenu-item submenu-item-back-link submenu-item-back-'. strtolower($title) .'" data-parent-menu-title="'. strtolower($title) .'"><a href="#"><span class="material-icons">chevron_left</span> Back</a></li></ul>';
                } else {
                    $html .= '</ul>';
                }
                // add see all button to nav dropdown
                if( !empty($see_all_item) ){
                    $html .= $see_all_item;
                }
                // close submenu inner
                if( !$is_sidebar ){
                    $html .= '</div>';
                }

                $html .= '</div>';
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
    }

    return $is_sidebar ? $html . $submenu_html : $html;

}

function convert_string_to_anchor_link($str) {
    $anchor_link = strtolower( $str );
    //Make alphanumeric (removes all other characters)
    $anchor_link = preg_replace("/[^a-z0-9_\s-]/", "", $anchor_link);
    //Clean up multiple dashes or whitespaces
    $anchor_link = preg_replace("/[\s-]+/", " ", $anchor_link);
    //Convert whitespaces and underscore to dash
    $anchor_link = preg_replace("/[\s_]/", "-", $anchor_link);

    return $anchor_link;
}

function word_count($string, $limit) {
    $words = explode(' ', $string);
    return implode(' ', array_slice($words, 0, $limit));  
}

function get_total_post_count($args=[]){
    //get total post count
    $query_args = [
        'post_type' => 'post',
        'post_status'=> 'publish',
        'posts_per_page'=> -1,
        'post__not_in' => $args['exclude']
    ];
    if (isset($args['tag']) && $args['tag']){
        $query_args['tax_query'][] = 
        [
            'taxonomy'  => 'post_tag',
            'field'     => 'slug',
            'terms'     => sanitize_title( $args['tag'] )
        ];
    }
    $posts_to_count = get_posts($query_args);
    $total_post_count = count($posts_to_count);

    return $total_post_count;
}

add_action( 'admin_footer', 'preload_content_block_fields' );

function preload_content_block_fields() { ?>
	<script type="text/javascript" >
        let $ = jQuery;
        const observer = new MutationObserver(mutations => {
            const contentBlockPage = $('div.acf-field-post-object[data-name="content_block_page"] select')

            if(contentBlockPage.length) {
                $('div.acf-field-post-object[data-name="content_block_page"] select').on('change', e => {
                    const selectedPageId = $(e.target).val();

                    $.ajax({
                        url: "/wp-admin/admin-ajax.php",
                        type: 'POST',
                        data: {
                            action: 'get_selected_page_data',
                            page_id: selectedPageId,
                        },
                        dataType: 'html',
                        success: function (post) {
                            const parsedPost = JSON.parse(post);

                            $(e.target).closest('.acf-fields').find('.acf-field-text[data-name="content_block_title"] input').val(parsedPost.post_title);
                        },
                        error: function (errorThrown) {
                            console.log(errorThrown);
                        }
                    });
                });
            }
        });

        const config = {
            attributes: true,
            childList: true,
            characterData: true
        };

        observer.observe(document.body, config);
	</script> 
<?php }

add_action('wp_ajax_get_selected_page_data', 'get_selected_page_data');
/**
 * Get selected page data
 */
function get_selected_page_data(){
    $page_id = $_POST['page_id'];
    $page = get_post($page_id);

    echo json_encode($page);
    wp_die();
}
function formatBytes($bytes, $precision = 2) {
    $kilobyte = 1024;
    $megabyte = 1024 * 1024;

    if ($bytes >= 0 && $bytes < $kilobyte) {
        return $bytes . " b";
    }

    if ($bytes >= $kilobyte && $bytes < $megabyte) {
        return round($bytes / $kilobyte, $precision) . " kb";
    }

    return round($bytes / $megabyte, $precision) . " mb";
}

/**
 * Truncate inputed content by inputed limit using inputed delimiter.
 *
 * @param type $string String.
 * @param type $length Integer.
 * @param type $append String.
 * @return type String.
 */
function truncate($string, $length = 100, $append = " &hellip;") {
    $string = trim($string);

    if(strlen($string) > $length) {
        $string = wordwrap($string, $length);
        $string = explode("\n", $string, 2);
        $string = $string[0] . $append;
    }

    return $string;
}

function custom_render_block_core_group ($block_content, $block){
    if ($block['blockName'] === 'core/table' && !is_admin() && !wp_is_json_request()){
        $custom_style = '';
        $figcaption = '';
        if (isset($block['attrs']['className'])){
            $custom_style = $block['attrs']['className'];
        }
        $table_arr = [];
        $contents = $block_content;
        $DOM = new DOMDocument('1.0', 'UTF-8');
        @$DOM->loadHTML($contents);

        $items = $DOM->getElementsByTagName('tr');
        $figcaption_obj = $DOM->getElementsByTagName('figcaption');
        if (is_object($figcaption_obj->item(0))){
            $figcaption = $figcaption_obj->item(0)->nodeValue;
        }
        foreach ($items as $node) {
            $row_arr = [];
            foreach ($node->childNodes as $element) {
                $row_arr[] = $DOM->saveXML($element);
            }
            $table_arr[] = $row_arr;
            
        }
        $header = $table_arr[0];
        array_shift($table_arr);
        $mobile_table = '';
        $mobile_table .= '<figure class="wp-block-table mobile-table '.$custom_style.'"><table>';
        foreach($table_arr as $row){
            foreach($row as $key => $cell){
                $row_class = '';
                if ($key == 0){
                    $row_class = 'top-mobile-row';
                }
                $mobile_table .= '<tr class="'.$row_class.'">'.$header[$key].''.$cell.'</tr>';
            }
        }
        $mobile_table .= '</table>';
        $mobile_table .= '<figcaption>'.$figcaption.'</figcaption></figure>';
        $block_content .= $mobile_table;
    }

    return $block_content;
}

add_filter('render_block', 'custom_render_block_core_group', null, 2);

//format library size field to have commas in frontend
add_filter('acf/format_value/name=library_size', 'format_with_commas', 20, 3);
function format_with_commas($value, $post_id, $field) {
  $value = number_format((float)$value);
  return $value;
}


//Add ACF Admin columns
function add_acf_columns ( $columns ) {
$count = 1;
$new_columns = [];
foreach ($columns as $key => $column){
    $new_columns[$key] = $column;
    if ($count == 2){
       $new_columns['order'] = __ ( 'Order' ); 
    }
    $count++;
}
return $new_columns;
}
add_filter ( 'manage_provider_posts_columns', 'add_acf_columns' );

 //Add column values for ACF columns
function provider_custom_column ( $column, $post_id ) {
    $order = get_field('order', $post_id);
    echo $order;
}
add_action ( 'manage_provider_posts_custom_column', 'provider_custom_column', 10, 2 );

//Have Webtoffee GDPR notice use their own location api so we dont get the too many requests error
add_filter('wt_cli_use_custom_geolocation_api','__return_true');

add_action( 'pre_get_posts', function($query) {
    if ( !is_admin() && $query->is_main_query() ) {
        
        // Change wp_query loop for providers archive so extra pages not generated
        if ( is_post_type_archive('provider') ) {
            $query->set( 'posts_per_page', -1 );
            $query->set( 'paged', 1);
            $query->set( 'nopaging', true );
        }
    }
    return $query;
});


// filter when searching repeater field
function my_posts_where( $where ) {
    
    $where = str_replace("meta_key = 'possible_provider_names_$", "meta_key LIKE 'possible_provider_names_%", $where);

    return $where;
}

add_filter('template_include', 'hso_include_locations_template', 1000, 1);
function hso_include_locations_template($template){
    //if it's a 404 page check if it's a programmatic city page
    if (is_404()){
        global $wp;
        $url = home_url( $wp->request );
        $parse = parse_url($url);
        $slugs = explode("/", $parse['path']);
        $slugs = array_slice($slugs, -2, 2, false);
        $state_check = strtoupper($slugs[0]);
        $city_check = ucwords(str_replace('-', ' ', $slugs[1]));
        global $wpdb;
        $table_name = $wpdb->prefix . "zip_tract";

        //check if city is in db
        $city_query = "SELECT id FROM $table_name WHERE usps_zip_pref_city='$city_check' AND usps_zip_pref_state='$state_check' LIMIT 1";
        $row = $wpdb -> get_results($city_query);

        // if it's a city page
        if (isset($row[0]) && $row[0]->id){
            global $is_programmatic_city_page;
            global $city;
            global $state;
            $city = $city_check;
            $state = $state_check;
            $is_programmatic_city_page = true;
            global $wp_query;
            $wp_query->is_404=false;
            status_header(200);
            nocache_headers();
            $new_template = WP_CONTENT_DIR.'/themes/wp-highspeedoptions/single-locations.php';
            if(file_exists($new_template))
                $template = $new_template;
        }
    }
    return $template;
}

function slugify($str){

    $str = strtolower(trim($str));
    $str = html_entity_decode($str);
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    $str = preg_replace('/-+/', "-", $str);
    return $str;
}



function programmatic_locations_sitemap_index( $sitemap_index ) {
  global $wpseo_sitemaps;

  $sitemap_url    = home_url( 'programmatic-locations-sitemap.xml' );
  $sitemap_date   = date(DATE_W3C);
  $custom_sitemap = '
    <sitemap>
      <loc>%s</loc>
      <lastmod>%s</lastmod>
    </sitemap>
    ';
  $sitemap_index .= sprintf( $custom_sitemap, $sitemap_url, $sitemap_date );

  return $sitemap_index;
}
add_filter( 'wpseo_sitemap_index', 'programmatic_locations_sitemap_index' );


function programmatic_locations_sitemap_register() {
  global $wpseo_sitemaps;

  if ( isset( $wpseo_sitemaps ) && ! empty( $wpseo_sitemaps ) ) {
    $wpseo_sitemaps->register_sitemap( 'programmatic-locations', 'programmatic_locations_sitemap_fetch' );
  }
}
add_action( 'init', 'programmatic_locations_sitemap_register' );


function programmatic_locations_sitemap_fetch() {
  global $wpseo_sitemaps;
  $sitemap = file_get_contents(get_template_directory().'/custom-sitemaps/programmatic-locations.xml');
  $wpseo_sitemaps->set_sitemap( $sitemap );
}

//add all programmatic city pages to yoast sitemap
function create_programmatic_city_sitemap() { 

    $domain = get_home_url();

    global $wpdb;
    global $wpseo_sitemaps;
    $urls = [];
    $table_name = $wpdb->prefix . "zip_tract";
    $tracts_query = "SELECT DISTINCT usps_zip_pref_city, usps_zip_pref_state FROM $table_name";
    $row = $wpdb -> get_results($tracts_query);
    $args = array(
        'numberposts'   => -1,
        'post_type'     => 'locations',
        'tax_query' => array(
            array(
                'taxonomy' => 'location_type',
                'field' => 'slug',
                'terms' => 'city',
            ),
        ),
    );
    $locations = get_posts($args);

    foreach($row as $us_city){
        $top_city_found = false;
        $city = strtolower($us_city->usps_zip_pref_city);
        $state = strtolower($us_city->usps_zip_pref_state);

        foreach($locations as $top_city){
            if (strtolower($top_city->post_title) == $city){
                $top_city_state = get_field('abbreviation',$top_city->post_parent);
                if (strtolower($top_city_state) == $state){
                    $top_city_found = true;
                }
            }
        }
        if (!$top_city_found){
            $city = slugify($city);

              $urls[] = $wpseo_sitemaps->renderer->sitemap_url([
                'mod'    => date(DATE_ATOM,time()),
                'loc'    => $domain.'/'.$state.'/'.$city,
              ]);
        }

    }
    $sitemapText .= '</urlset>';

    $sitemap_body = '
    <urlset
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd"
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    %s
    </urlset>
    ';
    $sitemapWrite = sprintf( $sitemap_body, implode( "\n", $urls ) );

    $sitemap = fopen(get_template_directory().'/custom-sitemaps/programmatic-locations.xml', "w") or die("Unable to open file!");

    fwrite($sitemap, $sitemapWrite);
    fclose($sitemap);
} 

//update site map any time a top city is created or updated
add_action( 'save_post_locations', 'save_locations_action' );
function save_locations_action() {
    create_programmatic_city_sitemap();
}

add_shortcode( 'current_year', 'sc_year' );
function sc_year(){
    return date( 'Y' );
}

add_shortcode( 'current_month', 'sc_month' );
function sc_month(){
    return date( 'F' );
}

add_filter( 'single_post_title', 'shortcode_title' );
add_filter( 'the_title', 'shortcode_title' );
add_filter( 'wpseo_title', 'shortcode_title' );
add_filter( 'wpseo_opengraph_title', 'shortcode_title' );

function shortcode_title( $title ){
    return do_shortcode( $title );
}


//Redirect taxonomy and cpt archive pages to the homepage
add_action('template_redirect', function() {
    if(is_category() || is_tag() || is_tax('location_type') || is_post_type_archive('location')) {
        $target = get_option('siteurl');
        wp_redirect($target, 301);
        die();
    }
});
add_action( 'in_admin_header', function() {
    $migration = get_field('migration_on', 'options');
    if($migration) {
        get_template_part('/template-parts/migration-banner');
    }
} );

add_filter('admin_body_class', function($classes) {
    $migration = get_field('migration_on', 'options');
    if($migration){
        return $classes . ' migration-on';
    }
    return $classes;
});

add_filter('body_class', function($classes) {
    $migration = get_field('migration_on', 'options');
    if($migration && is_user_logged_in()){
        $classes[] = 'migration-on';
        
        //return $classes . ' migration-on';
    }
    return $classes;
});

