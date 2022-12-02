<?php

/**
 * Create custom theme options menu
 */

if( function_exists('acf_add_options_page') ) {
    
  acf_add_options_page(array(
      'page_title'    => 'General Settings',
      'menu_title'    => 'Theme Settings',
      'menu_slug'     => 'theme-settings',
      'capability'    => 'edit_posts',
      'redirect'      => false
  ));
  
  acf_add_options_sub_page(array(
      'page_title'    => 'Locations Settings',
      'menu_title'    => 'Locations',
      'parent_slug'   => 'theme-settings',
  ));
  
}

add_action('acf/init', 'my_acf_blocks_init');
function my_acf_blocks_init() {

    // Check function exists.
    if( function_exists('acf_register_block_type') ) {

        // Register a Pros & Cons block.
        acf_register_block_type(array(
            'name'              => 'pros-cons',
            'title'             => __('Pros & Cons'),
            'description'       => __('A custom Pros & Con block.'),
            'render_template'   => 'template-parts/blocks/pros-cons.php',
            'category'          => 'formatting',
        ));

        // Register a Gray CTA Box block.
        acf_register_block_type(array(
            'name'              => 'gray-background-cta',
            'title'             => __('Gray Background CTA'),
            'description'       => __('A custom Gray Background CTA block.'),
            'render_template'   => 'template-parts/blocks/gray-background-cta.php',
            'category'          => 'formatting',
            'post_types'        => array('provider'),
        ));

        // Register a ZIP Search Box block.
        acf_register_block_type(array(
            'name'              => 'zip-search',
            'title'             => __('ZIP Search Box'),
            'description'       => __('A custom ZIP Search Box block.'),
            'render_template'   => 'template-parts/blocks/zip-search.php',
            'category'          => 'formatting',
        ));

        // Register a Comparison Table Box block.
        acf_register_block_type(array(
            'name'              => 'comparison-table',
            'title'             => __('Comparison Table'),
            'description'       => __('A custom Comparison Table block.'),
            'render_template'   => 'template-parts/blocks/comparison-table.php',
            'category'          => 'formatting',
            'post_types'        => array('provider'),
        ));

         // Register a Streaming Networks Box block.
         acf_register_block_type(array(
            'name'              => 'streaming-networks',
            'title'             => __('Top 5 Streaming Networks'),
            'description'       => __('A custom Streaming Networks block.'),
            'render_template'   => 'template-parts/blocks/streaming-networks.php',
            'category'          => 'formatting',
        ));

        // Register a Compare Providers Box block.
        acf_register_block_type(array(
            'name'              => 'compare-providers',
            'title'             => __('Compare Providers'),
            'description'       => __('A custom Compare Providers block.'),
            'render_template'   => 'template-parts/blocks/compare-providers.php',
        ));
        // Register an FAQ Box block.
        acf_register_block_type(array(
            'name'              => 'faq-list',
            'title'             => __('FAQ List'),
            'description'       => __('A custom FAQ list block.'),
            'render_template'   => 'template-parts/blocks/faq-list.php',
            'category'          => 'formatting',
        ));

        // Register a Contact Us Box block.
        acf_register_block_type(array(
            'name'              => 'contact-us',
            'title'             => __('Contact Us'),
            'description'       => __('A custom Contact Us block.'),
            'render_template'   => 'template-parts/blocks/contact-us.php',
            'category'          => 'formatting',
        ));
        
        // Register a Rotating Logos Box block.
        acf_register_block_type(array(
            'name'              => 'rotating-logos',
            'title'             => __('Rotating Logos'),
            'description'       => __('A custom Rotating Logos block.'),
            'render_template'   => 'template-parts/blocks/rotating-logos.php',
            'category'          => 'formatting',
        ));

        // Register a Vertical Page Grey Box block.
        acf_register_block_type(array(
            'name'              => 'vertical-grey-box',
            'title'             => __('Grey Background'),
            'description'       => __('A custom Vertical Page Grey Box block.'),
            'render_template'   => 'template-parts/blocks/vertical-grey-box.php',
            'category'          => 'formatting',
        ));

        // Register a Product Options Box block.
        acf_register_block_type(array(
            'name'              => 'product-options',
            'title'             => __('Product Options'),
            'description'       => __('A custom Product Options Box block.'),
            'render_template'   => 'template-parts/blocks/product-options.php',
            'category'          => 'formatting',
        ));
        
        // Register a Browse Providers By Type Box block.
        acf_register_block_type(array(
            'name'              => 'browse-providers-by-type',
            'title'             => __('Browse Providers By Type'),
            'description'       => __('A custom Browse Providers By Type Box block.'),
            'render_template'   => 'template-parts/blocks/browse-providers-by-type.php',
            'category'          => 'formatting',
        ));
        // Register a Phone Button block.
        acf_register_block_type(array(
            'name'              => 'phone-button',
            'title'             => __('Phone Button'),
            'description'       => __('A custom Phone Button block.'),
            'render_template'   => 'template-parts/blocks/phone-button.php',
            'category'          => 'formatting',
        ));
        // Register a HSO Button block.
        acf_register_block_type(array(
            'name'              => 'hso-button',
            'title'             => __('HSO Button'),
            'description'       => __('A custom HSO Button block.'),
            'render_template'   => 'template-parts/blocks/hso-button.php',
            'category'          => 'formatting',
        ));
        // Register a Features card block.
        acf_register_block_type(array(
            'name'              => 'features-card',
            'title'             => __('Features Card'),
            'description'       => __('A custom Features Card block.'),
            'render_template'   => 'template-parts/blocks/features-card.php',
            'category'          => 'formatting',
        ));
        // Register a privider card block.
        acf_register_block_type(array(
            'name'              => 'provider-card',
            'title'             => __('Provider Card'),
            'description'       => __('A custom Provider Card block.'),
            'render_template'   => 'template-parts/blocks/provider-card.php',
            'category'          => 'formatting',
        ));
        // Register a quote block.
        acf_register_block_type(array(
            'name'              => 'quote',
            'title'             => __('Featured Quote'),
            'description'       => __('A custom Featured Quote block.'),
            'render_template'   => 'template-parts/blocks/quote.php',
            'category'          => 'formatting',
        ));
        // Register a text callout block.
        acf_register_block_type(array(
            'name'              => 'text-callout',
            'title'             => __('Text Callout'),
            'description'       => __('A custom Text Callout block.'),
            'render_template'   => 'template-parts/blocks/text-callout.php',
            'category'          => 'formatting',
        ));
        // Register a featured CTA block.
        acf_register_block_type(array(
            'name'              => 'featured-cta',
            'title'             => __('Featured CTA'),
            'description'       => __('A custom Featured CTA block.'),
            'render_template'   => 'template-parts/blocks/featured-cta.php',
            'category'          => 'formatting',
        ));
        // Register a Compare Providers Table (Horizontal Scroll) block.
        acf_register_block_type(array(
            'name'              => 'compare-providers-horizontal-scroll',
            'title'             => __('Compare Providers Table (Horizontal Scroll)'),
            'description'       => __('A custom Compare Providers table (horizontal scroll) block.'),
            'render_template'   => 'template-parts/blocks/compare-providers-table-horizontal-scroll.php',
            'category'          => 'formatting',
        ));
        // Register a Types of {Service} Technology block.
        acf_register_block_type(array(
            'name'              => 'types-of-technology',
            'title'             => __('Types of Technology'),
            'description'       => __('A custom Types of {Service} Technology block.'),
            'render_template'   => 'template-parts/blocks/types-of-technology.php',
            'category'          => 'formatting',
        ));
        // Register a Flexible Page Tiles block.
        acf_register_block_type(array(
            'name'              => 'flexible-page-tiles',
            'title'             => __('Flexible Page Tiles'),
            'description'       => __('A custom Flexible Page Tiles block.'),
            'render_template'   => 'template-parts/blocks/flexible-page-tiles.php',
            'category'          => 'formatting',
        ));
        // Register a Flexible Page Tiles block.
        acf_register_block_type(array(
            'name'              => 'flexible-content',
            'title'             => __('Flexible Content'),
            'description'       => __('A custom Flexible Content block.'),
            'render_template'   => 'template-parts/blocks/flexible-content.php',
            'category'          => 'formatting',
        ));

        // Register a Top Providers block.
        acf_register_block_type(array(
            'name'              => 'top-providers',
            'title'             => __('Top Providers'),
            'description'       => __('A custom Top Providers block.'),
            'render_template'   => 'template-parts/blocks/top-providers.php',
            'category'          => 'formatting',
        ));

        // Register a Callout Element block.
        acf_register_block_type(array(
            'name'              => 'callout-element',
            'title'             => __('Callout Element'),
            'description'       => __('A custom Callout Element block.'),
            'render_template'   => 'template-parts/blocks/callout-element.php',
            'category'          => 'formatting',
        ));

        // Register a Deals Tile block.
        acf_register_block_type(array(
            'name'              => 'deals-tile',
            'title'             => __('Deals Tile'),
            'description'       => __('A custom Deals Tile block.'),
            'render_template'   => 'template-parts/blocks/deals-tile.php',
            'category'          => 'formatting',
        ));

        // Register a Providers Comprison block.
        acf_register_block_type(array(
            'name'              => 'providers-comparion',
            'title'             => __('Providers Comprison'),
            'description'       => __('A custom Providers Comprison block.'),
            'render_template'   => 'template-parts/blocks/providers-comparion.php',
            'category'          => 'formatting',
        ));
        // Provider Plan Details
        acf_register_block_type(array(
            'name'              => 'provider-plan-details',
            'title'             => __('Provider Plan Details'),
            'description'       => __('Provider Plan Details'),
            'render_template'   => 'template-parts/blocks/provider-plan-details.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        // Connection Type Details Card
        acf_register_block_type(array(
            'name'              => 'connection-type-details-card',
            'title'             => __('Connection Type Details Card'),
            'description'       => __('Connection Type Details Card'),
            'render_template'   => 'template-parts/blocks/connection-type-details-card.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
         // Speed Usage Tiles
        acf_register_block_type(array(
            'name'              => 'speed-usage-tiles',
            'title'             => __('Speed Usage Tiles'),
            'description'       => __('Speed Usage Tiles'),
            'render_template'   => 'template-parts/blocks/speed-usage-tiles.php',
            'category'          => 'formatting',
        ));    
        // register a gray text block.
        acf_register_block_type(array(
            'name'              => 'disclaimer-box',
            'title'             => __('Disclaimer Box'),
            'description'       => __('A custom Disclaimer block.'),
            'render_template'   => 'template-parts/blocks/disclaimer-box.php',
            'category'          => 'formatting',
            'mode'              => 'auto',
        ));
        acf_register_block_type(array(
            'name'              => 'icon-callout',
            'title'             => __('Icon Callout'),
            'description'       => __('Icon Callout'),
            'render_template'   => 'template-parts/blocks/icon-callout.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        acf_register_block_type(array(
            'name'              => 'commercial-provider',
            'title'             => __('Commercial Provider'),
            'description'       => __('Commercial Provider'),
            'render_template'   => 'template-parts/blocks/commercial-provider.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        acf_register_block_type(array(
            'name'              => 'well-consider',
            'title'             => __('Well/Consider'),
            'description'       => __('Well/Consider'),
            'render_template'   => 'template-parts/blocks/well-consider.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        acf_register_block_type(array(
            'name'              => 'blue-deals',
            'title'             => __('Blue Deals'),
            'description'       => __('Blue Deals'),
            'render_template'   => 'template-parts/blocks/blue-deals.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        acf_register_block_type(array(
            'name'              => 'our-thoughts',
            'title'             => __('Our Thoughts'),
            'description'       => __('Our Thoughts'),
            'render_template'   => 'template-parts/blocks/our-thoughts.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        acf_register_block_type(array(
            'name'              => 'blue-zip',
            'title'             => __('Blue Zip Bar'),
            'description'       => __('Blue Zip Bar'),
            'render_template'   => 'template-parts/blocks/blue-zip.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        acf_register_block_type(array(
            'name'              => 'satellite-info',
            'title'             => __('Satellite Info'),
            'description'       => __('Satellite Info'),
            'render_template'   => 'template-parts/blocks/satellite-info.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        acf_register_block_type(array(
            'name'              => 'our-picks',
            'title'             => __('Our Picks Table'),
            'description'       => __('Our Picks Table'),
            'render_template'   => 'template-parts/blocks/our-picks.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        acf_register_block_type(array(
            'name'              => 'blue-overview',
            'title'             => __('Blue Overview'),
            'description'       => __('Blue Overview'),
            'render_template'   => 'template-parts/blocks/blue-overview.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        acf_register_block_type(array(
            'name'              => 'hmsdin-tool-elem',
            'title'             => __('How Much Speed Do I Need Tool'),
            'description'       => __('How Much Speed Do I Need Tool'),
            'render_template'   => 'template-parts/blocks/hmsdin-tool-element.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        acf_register_block_type(array(
            'name'              => 'service-summary',
            'title'             => __('Service Summary'),
            'description'       => __('Service Summary'),
            'render_template'   => 'template-parts/blocks/service-summary.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        acf_register_block_type(array(
            'name'              => 'home-hero',
            'title'             => __('Home Hero'),
            'description'       => __('Home Hero'),
            'render_template'   => 'template-parts/blocks/home-hero.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        acf_register_block_type(array(
            'name'              => 'icon-zip-embed',
            'title'             => __('Icon Zip Embed'),
            'description'       => __('Icon Zip Embed'),
            'render_template'   => 'template-parts/blocks/icon-zip-embed.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        acf_register_block_type(array(
            'name'              => 'home-providers',
            'title'             => __('Home Providers'),
            'description'       => __('Home Providers'),
            'render_template'   => 'template-parts/blocks/home-providers.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        acf_register_block_type(array(
            'name'              => 'customer-service',
            'title'             => __('Customer Service'),
            'description'       => __('Customer Service'),
            'render_template'   => 'template-parts/blocks/customer-service.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        acf_register_block_type(array(
            'name'              => 'home-cities',
            'title'             => __('Home Cities'),
            'description'       => __('Home Cities'),
            'render_template'   => 'template-parts/blocks/home-cities.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        acf_register_block_type(array(
            'name'              => 'provider-plan',
            'title'             => __('Provider Plan'),
            'description'       => __('Provider Plan'),
            'render_template'   => 'template-parts/blocks/provider-plan.php',
            'category'          => 'formatting',
            'mode'              =>  'preview',
            'align' => 'wide',
            'supports'		=> [
                'align' => true,
                'mode' => false,
                'jsx' => true,
            ]
        ));
        acf_register_block_type(array(
            'name'              => 'provider-overview',
            'title'             => __('Provider Overview'),
            'description'       => __('Provider Overview'),
            'render_template'   => 'template-parts/blocks/provider-overview.php',
            'category'          => 'formatting',
            'mode'              =>  'preview',
            'align' => 'wide',
            'supports'		=> [
                'align' => true,
                'mode' => false,
                'jsx' => true,
            ]
        ));
        acf_register_block_type(array(
            'name'              => 'provider-plan-connections',
            'title'             => __('Provider Plan Connections'),
            'description'       => __('Provider Plan Connections'),
            'render_template'   => 'template-parts/blocks/provider-plan-connections.php',
            'category'          => 'formatting',
            'mode'              => 'edit',
            'align'             => 'wide',
        ));
        acf_register_block_type(array(
            'name'              => 'basic-zip-embed',
            'title'             => __('Basic Zip Embed'),
            'description'       => __('Basic Zip Embed'),
            'render_template'   => 'template-parts/blocks/basic-zip-embed.php',
            'category'          => 'formatting',
            'mode'              => 'edit',
        ));
        acf_register_block_type(array(
            'name'              => 'home-compare',
            'title'             => __('Home Compare'),
            'description'       => __('Home Compare'),
            'render_template'   => 'template-parts/blocks/home-compare.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        acf_register_block_type(array(
            'name'              => 'locations-provider-table',
            'title'             => __('Locations Provider Table'),
            'description'       => __('Locations Provider Table'),
            'render_template'   => 'template-parts/blocks/locations-provider-table.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
            'post_types'        => array('locations'),
        ));
        // Register At A Glance Block
        acf_register_block_type(array(
            'name'              => 'at-a-glance',
            'title'             => __('At A Glance'),
            'description'       => __('At A Glance'),
            'render_template'   => 'template-parts/blocks/at-a-glance.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        // Provider By Connection Block
        acf_register_block_type(array(
            'name'              => 'provider-by-connection',
            'title'             => __('Provider By Connection'),
            'description'       => __('Provider By Connection'),
            'render_template'   => 'template-parts/blocks/provider-by-connection.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
         // Things to Consider Block
         acf_register_block_type(array(
            'name'              => 'things-to-consider',
            'title'             => __('Things to Consider'),
            'description'       => __('Things to Consider'),
            'render_template'   => 'template-parts/blocks/things-to-consider.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
         // Provider Data Table Block
         acf_register_block_type(array(
            'name'              => 'provider-data-table',
            'title'             => __('Provider Data Table'),
            'description'       => __('Provider Data Table'),
            'render_template'   => 'template-parts/blocks/provider-data-table.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));

        // Zip Qualifier Block
        acf_register_block_type(array(
            'name'              => 'zip-qualifier',
            'title'             => __('Zip Qualifier'),
            'description'       => __('Zip Qualifier'),
            'render_template'   => 'template-parts/blocks/zip-qualifier.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        // Top Providers TOC
        acf_register_block_type(array(
            'name'              => 'top-providers-toc',
            'title'             => __('Top Providers TOC'),
            'description'       => __('Top Providers TOC'),
            'render_template'   => 'template-parts/blocks/top-providers-toc.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        // Top Providers List
        acf_register_block_type(array(
            'name'              => 'top-providers-list',
            'title'             => __('Top Providers List'),
            'description'       => __('Top Providers List'),
            'render_template'   => 'template-parts/blocks/top-providers-list.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        // Register image/text row block
         acf_register_block_type(array(
            'name'              => 'image-text-row',
            'title'             => __('Image and Text Row Block'),
            'description'       => __('A custom block that shows an image next to text'),
            'render_template'   => 'template-parts/blocks/image-text-row.php',
            'category'          => 'formatting',
        ));
        // Register image/text row block
        acf_register_block_type(array(
            'name'              => 'icon-header-block',
            'title'             => __('Icon Header Block'),
            'description'       => __('Icon Header Block'),
            'render_template'   => 'template-parts/blocks/icon-header-block.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
        /* 
        * Comparison Template Blocks
        */
        // Register comparison template - provider highlights tiles blocks
         acf_register_block_type(array(
            'name'              => 'comparison-provider-highlights-tiles',
            'title'             => __('Comparison - Provider Highlights Tiles'),
            'description'       => __('A custom provider highlights tiles block for use on the comparisons template only.'),
            'render_template'   => 'template-parts/blocks/comparison_provider-highlights-tiles.php',
            'category'          => 'formatting',
            'post_types'        => array('post'),
        ));
         // Register comparison template - provider highlights tiles blocks
         acf_register_block_type(array(
            'name'              => 'comparison-provider-comparison-table',
            'title'             => __('Comparison - Provider Comparison Table'),
            'description'       => __('A custom provider comparison table block for use on the comparisons template only.'),
            'render_template'   => 'template-parts/blocks/comparison_provider-comparison-table.php',
            'category'          => 'formatting',
            'post_types'        => array('post'),
        ));
         // Register comparison template - features blocks
         acf_register_block_type(array(
            'name'              => 'comparison-features',
            'title'             => __('Comparison - Features'),
            'description'       => __('A custom features block for use on the comparisons template only.'),
            'render_template'   => 'template-parts/blocks/comparison_features.php',
            'category'          => 'formatting',
            'post_types'        => array('post'),
        ));
        // Register comparison template - features blocks
        acf_register_block_type(array(
            'name'              => 'comparison-main-differences',
            'title'             => __('Comparison - Main Differences'),
            'description'       => __('A custom main differences between two providers block for use on the comparisons template only.'),
            'render_template'   => 'template-parts/blocks/comparison_main-differences.php',
            'category'          => 'formatting',
            'post_types'        => array('post'),
        ));
        
        acf_register_block_type(array(
            'name'              => 'creative-cta',
            'title'             => __('Creative CTA'),
            'description'       => __('Creative CTA Block.'),
            'render_template'   => 'template-parts/blocks/creative-cta.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));

        acf_register_block_type(array(
            'name'              => 'speed-test',
            'title'             => __('Speed Test Block'),
            'description'       => __('Speed Test Block'),
            'render_template'   => 'template-parts/blocks/speed-test.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));

        acf_register_block_type(array(
            'name'              => 'blue-banner',
            'title'             => __('Blue Banner Block'),
            'description'       => __('Blue Banner Block'),
            'render_template'   => 'template-parts/blocks/blue-banner.php',
            'category'          => 'formatting',
            'mode'              =>  'edit',
        ));
    }
}

add_action( 'init', 'provider_block_template' );
function provider_block_template() {
    $post_type_object = get_post_type_object( 'provider' );
    $post_type_object->template = array(
        array('acf/provider-overview'),
        array('acf/provider-plan'),
    );
}