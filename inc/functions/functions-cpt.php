<?php

/**
 * Foreach post type registration
 *
 * @link https://codex.wordpress.org/Post_Types#Custom_Post_Types
 * @link https://codex.wordpress.org/Function_Reference/register_post_type
 * @link https://codex.wordpress.org/Function_Reference/register_taxonomy
 */


add_action('init', 'post_types');
function post_types()
{
    global $wp_post_types;
    global $wp_taxonomies;

    $post_types = array(

        array(
            'slug'        => 'provider',
            'rewrite'     => 'providers',
            'single_name' => 'Provider',
            'plural_name' => 'Providers',
            'menu_name'	  => 'Provider',
            'show-in-rest'  => true,
            'description' => 'Provider that we have',
			'menu-position'  => 4,
            // https://developer.wordpress.org/resource/dashicons/#microphone
            'dashicon'    => 'dashicons-admin-page',
            'publicly_queryable' => true,
            'hierarchical' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
        ),
        array(
            'slug'        => 'build-buyer',
            'rewrite'     => 'build-buyer',
            'single_name' => 'Build Buyer',
            'plural_name' => 'Build Buyers',
            'menu_name'	  => 'Build Buyers',
            'show-in-rest'  => false,
            'description' => 'Build Buyers that we have',
			'menu-position'  => 5,
            // https://developer.wordpress.org/resource/dashicons/#microphone
            'dashicon'    => 'dashicons-admin-page',
            'publicly_queryable' => false,
            'hierarchical' => false,
            'supports' => array('title', 'editor', 'thumbnail'),
        ),
        array(
            'slug'        => 'streaming',
            'rewrite'     => 'streaming',
            'single_name' => 'Streaming Provider',
            'plural_name' => 'Streaming Providers',
            'menu_name'	  => 'Streaming',
            'show-in-rest'  => false,
            'description' => 'Streaming Provider that we have',
			'menu-position'  => 6,
            // https://developer.wordpress.org/resource/dashicons/#microphone
            'dashicon'    => 'dashicons-admin-page',
            'publicly_queryable' => false,
            'hierarchical' => false,
            'supports' => array('title', 'editor', 'thumbnail'),
        ),
        array(
            'slug'        => 'locations',
            'rewrite'     => 'locations',
            'single_name' => 'Location',
            'plural_name' => 'Locations',
            'menu_name'   => 'Locations',
            'show-in-rest'  => true,
            'has_archive' => false,
            'description' => 'Locations for Geo Pages',
            'menu-position'  => 7,
            // https://developer.wordpress.org/resource/dashicons/#microphone
            'dashicon'    => 'dashicons-location',
            'publicly_queryable' => true,
            'hierarchical' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
        ),
        array(
            'slug'        => 'authors',
            'rewrite'     => 'authors',
            'single_name' => 'Author',
            'plural_name' => 'Authors',
            'menu_name'   => 'Authors',
            'show-in-rest'  => false,
            'description' => 'Authors Bio',
            'menu-position'  => 8,
            'dashicon'    => 'dashicons-admin-page',
            'publicly_queryable' => false,
            'hierarchical' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
        ),
    );

    foreach ($post_types as $post_type) {
        $post_type_labels = array(
            'name'                  => _x($post_type["plural_name"], 'Post Type General Name', 'hso'),
            'singular_name'         => _x($post_type["single_name"], 'Post Type Singular Name', 'hso'),
            'menu_name'             => __($post_type["menu_name"], 'hso'),
            'name_admin_bar'        => __($post_type["plural_name"], 'hso'),
            'archives'              => __($post_type["single_name"] . ' Archives', 'hso'),
            'attributes'            => __($post_type["single_name"] . ' Attributes', 'hso'),
            'parent_item_colon'     => __('Parent ' . $post_type["single_name"], 'hso'),
            'all_items'             => __('All ' . $post_type["plural_name"], 'hso'),
            'add_new_item'          => __('Add New ' . $post_type["single_name"], 'hso'),
            'add_new'               => __('Add New ' . $post_type["single_name"], 'hso'),
            'new_item'              => __('New ' . $post_type["single_name"], 'hso'),
            'edit_item'             => __('Edit ' . $post_type["single_name"], 'hso'),
            'update_item'           => __('Update ' . $post_type["single_name"], 'hso'),
            'view_item'             => __('View ' . $post_type["single_name"], 'hso'),
            'view_items'            => __('View ' . $post_type["single_name"], 'hso'),
            'search_items'          => __('Search ' . $post_type["single_name"], 'hso'),
            'not_found'             => __('Not found', 'hso'),
            'not_found_in_trash'    => __('Not found in Trash', 'hso'),
            'featured_image'        => __($post_type["single_name"] . ' Image', 'hso'),
            'set_featured_image'    => __('Set ' . $post_type["single_name"] . ' image', 'hso'),
            'remove_featured_image' => __('Remove ' . $post_type["single_name"] . ' image', 'hso'),
            'use_featured_image'    => __('Use as ' . $post_type["single_name"] . ' image', 'hso'),
            'insert_into_item'      => __('Insert into ' . $post_type["single_name"], 'hso'),
            'uploaded_to_this_item' => __('Uploaded to this ' . $post_type["single_name"], 'hso'),
            'items_list'            => __($post_type["single_name"] . ' list', 'hso'),
            'items_list_navigation' => __($post_type["single_name"] . ' list navigation', 'hso'),
            'filter_items_list'     => __('Filter ' . $post_type["single_name"] . ' list', 'hso')
        );

        $post_types_args = array(
            'label'                 => __($post_type["single_name"], 'hso'),
            'description'           => __($post_type["description"], 'hso'),
            'labels'                => $post_type_labels,
            'supports'              => $post_type["supports"],
            // 'taxonomies'            => array('example', 'post_tag'),
            'hierarchical'          => $post_type['hierarchical'],
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_rest'          => $post_type["show-in-rest"],
            'menu_position'         => $post_type["menu-position"],
            'menu_icon'             => $post_type["dashicon"],
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => ($post_type["has_archive"]) ? $post_type["has_archive"] : true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => $post_type["publicly_queryable"],
            'capability_type'       => 'page',
            'rewrite' =>  array( 'with_front' => false, 'slug' => $post_type['rewrite']),
        );

        $slug = $post_type['slug'];

        /**
         * Gutenberg & Rest API Support
         */
        if (isset($wp_post_types[$slug])) {
            $wp_post_types[$slug]->show_in_rest = true;
            $wp_post_types[$slug]->rest_base = $slug;
            $wp_post_types[$slug]->rest_controller_class = 'WP_REST_Posts_Controller';
        }

        register_post_type($post_type['slug'], $post_types_args);
    }

}

add_action( 'init', 'create_taxonomy', 0 );
  
function create_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Types', 'taxonomy general name' ),
    'singular_name' => _x( 'Type', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Types' ),
    'all_items' => __( 'All Types' ),
    'parent_item' => __( 'Parent Type' ),
    'parent_item_colon' => __( 'Parent Type:' ),
    'edit_item' => __( 'Edit Type' ), 
    'update_item' => __( 'Update Type' ),
    'add_new_item' => __( 'Add New Type' ),
    'new_item_name' => __( 'New Type Name' ),
    'menu_name' => __( 'Types' ),
  );    
 
// Now register the taxonomy
  register_taxonomy('location_type',array('locations'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'public' => false,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'types' ),
  ));
 
}

// Post Types for Pages
add_action('init', 'page_types');
function page_types(){

    global $wp_page_types;

    $page_types = array(

        array(
            'slug'        => 'internet-pages',
            'single_name' => 'Internet Page',
            'plural_name' => 'Internet Pages',
            'menu_name'	  => 'Internet Pages',
            'description' => 'Internet that we Provide',
            'dashicon'    => 'dashicons-admin-page',
            'supports'    => array('title', 'editor', 'author', 'thumbnail', 'page-attributes'),
            'menu_position' => 20,
            'has_archive' => false,
        ),
        array(
            'slug'        => 'tv-pages',
            'single_name' => 'TV Page',
            'plural_name' => 'TV Pages',
            'menu_name'	  => 'TV Pages',
            'description' => 'TV that we Provide',
            'dashicon'    => 'dashicons-admin-page',
            'supports'    => array('title', 'editor', 'author', 'thumbnail', 'page-attributes'),
            'menu_position' => 21,
            'has_archive' => false,
        ),
        array(
            'slug'        => 'bundle-pages',
            'single_name' => 'Bundle Page',
            'plural_name' => 'Bundle Pages',
            'menu_name'	  => 'Bundle Pages',
            'description' => 'Bundle that we Provide',
            'dashicon'    => 'dashicons-admin-page',
            'supports'    => array('title', 'editor', 'author', 'thumbnail', 'page-attributes'),
            'menu_position' => 22,
            'has_archive' => false,
        ),
        array(
            'slug'        => 'paid-landers',
            'single_name' => 'Paid Lander',
            'plural_name' => 'Paid Landers',
            'menu_name'	  => 'Paid Landers',
            'description' => 'Paid Landers that we Provide',
            'dashicon'    => 'dashicons-admin-page',
            'supports'    => array('title', 'editor', 'thumbnail', 'page-attributes'),
            'menu_position' => 23,
            'has_archive' => false,
            'exclude_from_search' => true,
        ),
        array(
            'slug'        => 'comparisons',
            'single_name' => 'Comparison',
            'plural_name' => 'Comparisons',
            'menu_name'	  => 'Comparisons',
            'show-in-rest'  => true,
            'description' => 'Comparisons that we have',
            'menu-position'  => 9,
            'dashicon'    => 'dashicons-admin-page',
            'publicly_queryable' => true,
            'hierarchical' => true,
            'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'),
        ),
    );

    foreach ($page_types as $page_type) {
        $page_type_labels = array(
            'name'                  => _x($page_type["plural_name"], 'Post Type General Name', 'phos'),
            'singular_name'         => _x($page_type["single_name"], 'Post Type Singular Name', 'phos'),
            'menu_name'             => __($page_type["menu_name"], 'phos'),
            'name_admin_bar'        => __($page_type["plural_name"], 'phos'),
            'archives'              => __($page_type["single_name"] . ' Archives', 'phos'),
            'attributes'            => __($page_type["single_name"] . ' Attributes', 'phos'),
            'parent_item_colon'     => __('Parent ' . $page_type["single_name"], 'phos'),
            'all_items'             => __('All ' . $page_type["plural_name"], 'phos'),
            'add_new_item'          => __('Add New ' . $page_type["single_name"], 'phos'),
            'add_new'               => __('Add New ' . $page_type["single_name"], 'phos'),
            'new_item'              => __('New ' . $page_type["single_name"], 'phos'),
            'edit_item'             => __('Edit ' . $page_type["single_name"], 'phos'),
            'update_item'           => __('Update ' . $page_type["single_name"], 'phos'),
            'view_item'             => __('View ' . $page_type["single_name"], 'phos'),
            'view_items'            => __('View ' . $page_type["single_name"], 'phos'),
            'search_items'          => __('Search ' . $page_type["single_name"], 'phos'),
            'not_found'             => __('Not found', 'phos'),
            'not_found_in_trash'    => __('Not found in Trash', 'phos'),
            'featured_image'        => __($page_type["single_name"] . ' Image', 'phos'),
            'set_featured_image'    => __('Set ' . $page_type["single_name"] . ' image', 'phos'),
            'remove_featured_image' => __('Remove ' . $page_type["single_name"] . ' image', 'phos'),
            'use_featured_image'    => __('Use as ' . $page_type["single_name"] . ' image', 'phos'),
            'insert_into_item'      => __('Insert into ' . $page_type["single_name"], 'phos'),
            'uploaded_to_this_item' => __('Uploaded to this ' . $page_type["single_name"], 'phos'),
            'items_list'            => __($page_type["single_name"] . ' list', 'phos'),
            'items_list_navigation' => __($page_type["single_name"] . ' list navigation', 'phos'),
            'filter_items_list'     => __('Filter ' . $page_type["single_name"] . ' list', 'phos')
        );

        $page_types_args = array(
            'label'                 => __($page_type["single_name"], 'phos'),
            'description'           => __($page_type["description"], 'phos'),
            'labels'                => $page_type_labels,
            'supports'              => $page_type["supports"],
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_rest'          => true,
            'menu_position'         => $page_type["menu_position"],
            'menu_icon'             => $page_type["dashicon"],
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => array_key_exists("has_archive", $page_type) ? $page_type["has_archive"] : true,
            'exclude_from_search'   => array_key_exists("exclude_from_search", $page_type) ? $page_type["exclude_from_search"] : false,
            'publicly_queryable'    => array_key_exists("publicly_queryable", $page_type) ? $page_type["publicly_queryable"] : true,
            'capability_type'       => 'page'
        );

        $slug = $page_type['slug'];

        /**
         * Gutenberg & Rest API Support
         */
        if (isset($wp_page_types[$slug])) {
            $wp_page_types[$slug]->show_in_rest = true;
            $wp_page_types[$slug]->rest_base = $slug;
            $wp_page_types[$slug]->rest_controller_class = 'WP_REST_Posts_Controller';
        }

        register_post_type($page_type['slug'], $page_types_args);
    }
}