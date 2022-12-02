<?php
/**
 * HSO functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package HSO
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.2' );
}
if ( ! function_exists( 'hso_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function hso_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on HSO, use a find and replace
		 * to change 'hso' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'hso', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

        add_theme_support('align-wide');

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'hso' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'hso_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'hso_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function hso_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'hso_content_width', 640 );
}
add_action( 'after_setup_theme', 'hso_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function hso_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'hso' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'hso' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'hso_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function hso_scripts() {
	//wp_enqueue_style( 'hso-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'hso-style', 'rtl', 'replace' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'hso_scripts' );

/**
 * Enqueue admin styles.
 */
function load_admin_styles() {
    wp_enqueue_style( 'admin_css', get_template_directory_uri() . '/src/css/admin-styles.css', false, '1.0.0' );
}
add_action( 'admin_enqueue_scripts', 'load_admin_styles' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Functions which remove the Post Type Slug from URL.
 */
require get_template_directory() . '/inc/functions-remove-post-type-slug.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/hso_ajax.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';
/**
 * Classes.
 */
require get_template_directory() . '/inc/autoloader.php';
/*8
/**
 * Shortcodes.
 */
require get_template_directory() . '/inc/shortcodes.php';
/**
 * Toast.
 */
require get_template_directory() . '/inc/toast-loader.php';

if (file_exists(get_template_directory() . '/vendor/autoload.php')){
	require get_template_directory() . '/vendor/autoload.php';
}

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

// Add code to <head>
add_action( 'wp_head', 'add_header_scripts' );
function add_header_scripts() {
    if ('development' === wp_get_environment_type()){
        echo "
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-5678QMS');</script>
        <!-- End Google Tag Manager -->";
    }
    elseif( 'staging' === wp_get_environment_type() || 'production' === wp_get_environment_type()) {
        echo "
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-P2RZJHP');</script>
        <!-- End Google Tag Manager -->";
    }

    //if not elementor page set meta referrer
    if ( !Elementor\Plugin::instance()->db->is_built_with_elementor( get_the_ID()) ) {
        echo '<meta name="referrer" content="no-referrer-when-downgrade" />';
    }

}
// Add code just after opening <body> tag
add_action('wp_body_open', 'add_body_open_scripts');
function add_body_open_scripts() {
    if ('development' === wp_get_environment_type()){
        echo '<!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5678QMS"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->';
    }
	elseif( 'staging' === wp_get_environment_type() || 'production' === wp_get_environment_type()) {
	    echo '<!-- Google Tag Manager (noscript) -->
	    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P2RZJHP" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	    <!-- End Google Tag Manager (noscript) --> ';
       
	}
    
     
        echo '<script>
        window.dataLayer = window.dataLayer || [];  
        </script>';
}


//get the link info from just the link url itself
function getTrackingLinkInfo($cta_link, $partOfUrlToFind) {
  $linkParts = parse_url($cta_link);                
  $linkOutput = [];
  parse_str($linkParts['query'], $linkOutput);
  return $linkOutput[$partOfUrlToFind];    
}


function dataLayerProductDetail($dataLayerList, $VPNName, $VPNID, $dataLayerBrand, $VPNCat, $dataLayerVariant ) {   

                    return "dataLayer.push({
                              'event': 'productDetail',
                              'ecommerce': {
                                  'detail': {
                                      'actionField': {'list':'".$dataLayerList."'},
                                      'products': [{
                                          'name': '" .  $VPNName . "',
                                          'id': '" .  $VPNID . "', 
                                          'price': '00.01',
                                          'brand': '" .  $dataLayerBrand . "',
                                          'category': '" .  $VPNCat . "',
                                          'variant': '" .  $dataLayerVariant . "'
                                      }]
                                  }
                              }
                          });";
};



//datLayer info Function to create them outside of the zip search page
function dataLayerAddToCart($provider, $dataLayerVariant, $dataLayerCategory ) {

    if ($dataLayerVariant['text'] === "View Plans" && strpos($dataLayerVariant['url'], 'cswsaa') !== false) {
        
        $affLinkInfo = "'offer_id':'".getTrackingLinkInfo($dataLayerVariant['url'], 'offer_id')."',
            'aff_id':'".getTrackingLinkInfo($dataLayerVariant['url'], 'aff_id')."',";        
        
        $dataLayerVariantText = "'variant': 'View Plans' ";
        
    } else if ($dataLayerVariant['text'] === "View Plans" ) {
        
        $dataLayerVariantText = "'variant': 'View Plans' ";
    }  
    else {
       $affLinkInfo = ""; 
        
       $dataLayerVariantText = "'variant': getThisPhoneNumber(this) ";
        
        
    }

    return  "dataLayer.push({
                'event': 'addToCart',           
                ".$affLinkInfo."
                'ecommerce': {
                 'currencyCode': 'USD',
                 'add': {                            
                 'actionField': {'list': '".$dataLayerCategory." Page'},          
                  'products': [{                      
                    'name': '".get_the_title($provider)." ".$dataLayerCategory."',       
                    'id': '".$provider."',
                    'price': '00.01',
                    'brand': '".get_the_title($provider)."',
                    'category': '".$dataLayerCategory."',
                    ".$dataLayerVariantText.",
                    'quantity': 1
                     }]
                  }
                }
              });";
    
}

function dataLayerViewPlansClick( $provider, $dataLayerVariant, $dataLayerCategory ) {

    if ($dataLayerVariant['text'] === "View Plans" && strpos($dataLayerVariant['url'], 'cswsaa') !== false ) {
        
        $affLinkInfo = "'offer_id':'".getTrackingLinkInfo($dataLayerVariant['url'], 'offer_id')."',
            'aff_id':'".getTrackingLinkInfo($dataLayerVariant['url'], 'aff_id')."'";
        
    } else {
       $affLinkInfo = ""; 
    }     
    
  return "dataLayer.push({
            'event' : 'outboundLink',             
            'serviceProviderName' : '".get_the_title($provider)."',
            'serviceType' : '".$dataLayerCategory."',
            ".$affLinkInfo."

        });
        dataLayer.push({
            'event' : 'viewPlans', 
            'serviceProviderName' : '".get_the_title($provider)."',
            'serviceType' : '".$dataLayerCategory."',
            ".$affLinkInfo."
        });";

};


function dataLayerCallsClick( $provider, $dataLayerCategory ) {
    
    return "dataLayer.push({
            'event' : 'call', 
            'serviceProviderName' : '" . get_the_title($provider) ."',
            'serviceType' : '".$dataLayerCategory."',
            'tel' : getThisPhoneNumber(this)
         
        });";
};

function dataLayerOutboundLinkClick( $provider, $dataLayerCategory, $dataLayerBtn ) {
    
    return "dataLayer.push({
            'event' : 'outboundLink',             
            'serviceProviderName' : '".get_the_title($provider)."',
            'serviceType' : '".$dataLayerCategory."',
            'variant': '".$dataLayerBtn."',

        });";
};

function dataLayerProdClick($provider, $dataLayerVariant, $dataLayerCounter,  $dataLayerCategory, $dataLayerList ) {
    
    
      return "dataLayer.push({
                  'event': 'productClick',          
                  'ecommerce': {
                    'click': {
                      'actionField': {'list': '".$dataLayerList."'},     
                      'products': [{
                        'name': '". get_the_title($provider) ."',     
                        'id': '".$provider."',
                        'price': '00.01',
                        'brand':  '".get_the_title($provider)."',
                        'category': '".$dataLayerCategory."',
                        'variant': '".$dataLayerVariant['text']."', 
                        'position': ".$dataLayerCounter."
                       }]
                     }
                   }
               })";
    
}


function dataLayerCheckAvailabilityClick( $provider, $dataLayerCategory ) {
    
    return "dataLayer.push({
            'event' : 'checkAvailability', 
            'serviceProviderName' : '" . get_the_title($provider) ."',
            'serviceType' : '".$dataLayerCategory."'         
        });";
};

function getPre($fld, $Arr, $Prep = true){
    //$Internet2 = get_field_object('internet', $Provider );
    foreach( $Arr['sub_fields'] as $SubField ){
        if ( $SubField['name'] == 'details' ){
            foreach ( $SubField['sub_fields'] as $SubSubField ){
                if ( $SubSubField['name'] == $fld ){
                    if ( $Prep == true ){
                        return $SubSubField['prepend'];
                    }else{
                        return $SubSubField['append'];
                    }
                }
            }
        }
    }
}
function dataLayerProductImpressionWrapper($dataLayerInner ) {   
    
    return "dataLayer.push({
                'event': 'productImpressions',
                'ecommerce': {
                'currencyCode': 'USD',
                'impressions': [
                   " . $dataLayerInner . "
                ]
                }
            });";
    
}



function dataLayerProductImpression($provider, $dataLayerCategory, $dataLayerVariant, $dataLayerList, $dataLayerPosition ) {   
    
        return "{
         'name': '".get_the_title($provider)."',   
         'id': '".$provider."',
         'price': '00.01',
         'brand': '".get_the_title($provider)."',
         'category': '".$dataLayerCategory."',
         'variant': '".$dataLayerVariant['text']."', 
         'list': '".$dataLayerList."',
         'position': ".$dataLayerPosition."
       },";
    
}



//turn on sup sub btn for content
function my_mce_buttons_2($buttons) {	
	/**
	 * Add in a core button that's disabled by default
	 */
	$buttons[] = 'sup';
	$buttons[] = 'sub';

	return $buttons;
}
add_filter('mce_buttons_2', 'my_mce_buttons_2');

function customToolbars( $toolbars ) {
    // add to basic toolbar configuration
    array_unshift( $toolbars ['Basic'][1], 'subscript', 'superscript' );

    // add to full toolbar configuration
    array_unshift( $toolbars ['Full'][1],  'subscript', 'superscript' );
    return $toolbars;
}
add_filter( 'acf/fields/wysiwyg/toolbars' , 'customToolbars'  );


function acf_load_article_authors_field_choices( $field ) {


    $field['choices'] = array();

    // retrieve all article authors
    $args = array(  
        'post_type' => 'authors',
        'post_status' => 'publish',
        'orderby' => 'post_title', 
        'order' => 'ASC', 
    );
    $authors = new WP_Query( $args );

    // loop through article authors and set their id as the key and their
    // name/post_title as the value
    if( is_array($authors->posts) ) {
        foreach( $authors->posts as $author ) {
            $field['choices'][$author->ID] = $author->post_title;
        }
    }


    return $field;

}

// use the acf_load_article_authors_field_choices function to dynamically
// populate the options for the Article Authors Dropdown ACF

add_filter('acf/load_field/name=article_authors_dropdown', 'acf_load_article_authors_field_choices');


//bring in min stylesheet through plugin inline option
add_filter('autoptimize_filter_css_defer_inline','my_ao_css_defer_inline',10,1);
function my_ao_css_defer_inline($inlined) {
    
     //automatic file versioning base on save info
    $style_saved_css_version = filemtime( get_stylesheet_directory().'/style.min.css' );

	return $inlined.'</style><link rel="stylesheet" href="'.get_template_directory_uri().'/style.min.css?v='.$style_saved_css_version.'" media="all"><style>';
}

//for some reason preview broke with plugin update
add_filter('autoptimize_filter_noptimize','turn_on_for_preview',10,0);
function turn_on_for_preview() {
	 if ( is_preview() ) {
		return false;
	} 
}

//remove jquery migrate dont need it
function remove_jquery_migrate( $scripts ) {
   if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $script = $scripts->registered['jquery'];
       if ( $script->deps ) { 
        // Check whether the script has any dependencies

            $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
        }
    }
 }
add_action( 'wp_default_scripts', 'remove_jquery_migrate' );


//enqueues gutenburg scripts
function gutenberg_enqueue() {
    wp_enqueue_script(
        'hsoguten-script',
        get_template_directory_uri() . '/build/index-gutenberg.js',
        array('wp-blocks') // Include wp.blocks Package             
    );
}

add_action('enqueue_block_editor_assets', 'gutenberg_enqueue');

function custom_title($title_parts) {
    global $is_programmatic_city_page;

    $new_title = [];
    if ($is_programmatic_city_page){

        global $state;
        global $city; 
        $new_title['title'] = "Best Internet Providers in ".$city.", ".$state." | HighSpeedOptions";
        return $new_title;
    }
}
add_filter( 'document_title_parts', 'custom_title' );

function rel_canonical_custom_override(){

    global $is_programmatic_city_page;

    if ($is_programmatic_city_page){
        global $wp;
        global $state;
        global $city;

        $html = '';
        $html .= '<link rel="canonical" href="' . home_url( $wp->request ) . '" />';
        $html .= '<meta name="description" content="Find the best internet service providers in '.$city.', '.$state.'. Compare internet plans, speeds, coverage, and prices near you.">';

        echo $html;
    }
}

add_action( 'wp_head', 'rel_canonical_custom_override' );

// Remove dashicons in frontend for unauthenticated users
add_action( 'wp_enqueue_scripts', 'bs_dequeue_dashicons' );
function bs_dequeue_dashicons() {
    if ( ! is_user_logged_in() ) {
         wp_dequeue_style( 'dashicons' );
        wp_deregister_style( 'dashicons' );
    }
}

function create_custom_excerpt($content, $limit = 100, $clipped_text_indicator = '...') {
    $trimmed_content = wp_trim_words($content);
    $excerpt = substr(trim($trimmed_content), 0, $limit);
    $truncated_excerpt = substr($excerpt, 0, strrpos($excerpt, ' '));
    if(strlen($content) > 125) $truncated_excerpt .= ' '.$clipped_text_indicator;

    return $truncated_excerpt;
}

/* ========= LOAD CUSTOM FUNCTIONS ===================================== */
require_once get_stylesheet_directory() . '/inc/functions/functions-cpt.php';
require_once get_stylesheet_directory() . '/inc/functions/functions-acfblocks.php';
require_once get_stylesheet_directory() . '/inc/functions/functions-db-tables.php';
require_once get_stylesheet_directory() . '/inc/functions/functions-tables.php';

