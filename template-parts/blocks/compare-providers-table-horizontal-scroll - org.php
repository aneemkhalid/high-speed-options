<?php

/**
 * Comparison Table (Horizontal Scroll) Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$compare_providers = get_field('compare_providers_horizontal_scroll');
$button_type = $compare_providers['button_type'];
$zip_popup_class = '';
$data_att = '';
$provider_type = $compare_providers['provider_type'];
// echo "<pre>"; print_r($provider_type); echo "</pre>";

if ($button_type == 'popup'){
    $cta_text = $compare_providers['button_text'];
    if ($cta_text == ''){
        $cta_text = 'Check Availability';
    }
    $cta_link = '#';
    $rand = rand();
    $data_att = 'data-toggle="modal" data-target="#zipPopupModal-'.$rand.'"';
    $zip_popup_class = 'zip-popup-btn';
    if ($provider_type == 'internet'){
        $internet_checked = 'checked';
    } elseif ($provider_type == 'tv'){
        $tv_checked = 'checked';
    } elseif ($provider_type == 'bundles'){
        $bundle_checked = 'checked';
    }
    require get_theme_file_path( '/template-parts/zip-search-popup.php' );
}
$provider_type_key = $provider_type.'_data_type';
$providers = new WP_Query(array(
    'post_type'         => 'provider',
    'post_status'       => 'publish',
    'posts_per_page'    => -1,
    'post__in'          => $compare_providers['providers'],
    'orderby'           => 'post__in',
    'order'             => 'ASC',
));

// Predefine the columns that are always part of the table
$fixed_columns = array(
    array(
        'column_name'      => 'Our Picks',
        $provider_type_key => 'logo'
    ),
    array(
        'column_name'      => 'Provider',
        $provider_type_key => 'post_title'
    ),
    array(
        'column_name'      => 'How to Buy',
        $provider_type_key => 'button'
    ),
);

// Splice the dynamically-defined columns into the predefined columns
$splice_index = 2;
foreach( $compare_providers['table_columns'] as $column ) {
    array_splice( $fixed_columns, $splice_index, 0, array(
        array(
            'column_name' => $column['column_name'],
            $provider_type_key => $column[$provider_type_key]
        )
    ));
    $splice_index++;
}
// echo "<pre>"; print_r($fixed_columns); echo "</pre>";

// Because this is a two-dimensional table, this loop creates each row and
// seeds the relevant data for each row into the appropriate table cell (td)

$table_rows = array();
$link_values = array( 'logo', 'post_title', 'button' );
foreach( $providers->posts as $key => $provider ) {
    $data_type_subfields = get_field( $provider_type, $provider->ID );

    foreach( $fixed_columns as $column ) {
        $field_name = $column[$provider_type_key];
        if( !isset( $table_rows[$field_name] ) ) $table_rows[$field_name] = array();
        if( !in_array( 'row_title', $table_rows[$field_name] ) ) $table_rows[$field_name]['row_title'] = $column['column_name'];

        if( in_array( $field_name, $link_values ) ) {
            $link_target = '';
            $class = '';
            if( $field_name === 'logo' ) {
                $best_for_text = get_field( 'superlative', $provider->ID );
                if( $best_for_text ) $best_for_text = $best_for_text;
                $link_target = get_field( 'logo', $provider->ID );
                $link_target = '<img src="'.$link_target.'" class="provider-logo" alt="' . get_the_title($provider->ID) . '">';
                $class= 'provider-logo-link';
            } elseif( $field_name === 'button') {
                $target='';
                if ($button_type == 'link'){
                    $partner = get_field('partner', $provider->ID);
                    if($partner){
                        $buyer_id = get_field('buyer', $provider->ID);
                        $campaign = get_field( 'campaign', $buyer_id );
                        foreach($campaign as $key => $camp) {
                            $type_of_partnership = $camp['type_of_partnership'];
                            if($camp['campaign_name'] == $provider->ID){
                                if($type_of_partnership == 'call_center'){
                                    $cta_text = '<span class="material-icons">call</span>'.$camp['call_center'];
                                    $cta_link = 'tel:'.$camp['call_center'];
                                }else{
                                    $cta_text = 'View Plans';
                                    $cta_link = $camp['digital_tracking_link'];
                                    $target = 'target="blank"';
                                }
                            }
                        }           
                    }else{
                        $cta_text = 'View Plans';
                        $cta_link = get_field('brands_website_url',$provider->ID);
                    }
                }
            } elseif( $field_name === 'post_title') {
                $link_target = $provider->post_title;
                $class = 'provider-title-link';
            }
            $link = get_post_permalink( $provider->ID );
            $field = '<a href="'.$link.'" class="'.$class.'">'.$link_target.'</a>';
            if( $field_name === 'logo' ) $field =  '<div class="provider-logo-wrapper" id="provider-column-'.$provider->ID.'"><div class="provider-best-for">'.$best_for_text.'</div><div class="provider-logo-container">'.$field.'</div></div>';
            if( $field_name === 'button' ) $field = '<a href="'.$cta_link.'" class="cta-btn '.$zip_popup_class.' '.$class.'" '.$data_att.' '.$target.'>'.$cta_text.'</a>';
        }
        elseif( isset( $data_type_subfields[$field_name] ) ) {
            if (is_array($data_type_subfields[$field_name])){
                $field = implode(', ', $data_type_subfields[$field_name]);
            } else {
                if ($field_name == 'starting_price'){

                    if ( strtolower($provider_type) == "internet" ){
                        $ShowAsterisk  = $data_type_subfields['show_asterisk'];
                    }else if( strtolower($provider_type) == "tv" ){
                        $ShowAsterisk  = $data_type_subfields['show_asterisk2'];
                    }else if( strtolower($provider_type) == "bundles" ){
                        $ShowAsterisk  = $data_type_subfields['show_asterisk3'];
                    }else{
                        $ShowAsterisk  = 0;
                    }
                    if ( $ShowAsterisk == 1 ){
                        $Fld_Ast = " *";
                    }else{
                        $Fld_Ast = "";
                    }
                    $field = '$'. $data_type_subfields[$field_name].'/mo' . $Fld_Ast;
                } elseif ($field_name == 'minimum_channel_count') {
                    $field = $data_type_subfields[$field_name].'+';
                }else {
                    $field = $data_type_subfields[$field_name];
                }
            }
        } else {
            $field = get_field( $field_name, $provider->ID );
        }
        if( !$field ) $field = 'N/A';

        if( !isset( $table_rows[$field_name][$provider->ID] ) ) $table_rows[$field_name][$provider->ID] = $field;
    }
}
if(is_array($table_rows)):
?>
<div id="scrolltable">
    <!-- The desktop view uses the $table_rows array to create the two-dimensional table -->
    <div class="compare-providers-table-scroll-container desktop">
        <h5><?php echo $compare_providers['subheading']; ?></h5>
        <h2><?php echo $compare_providers['heading']; ?></h2>
        <span class="material-icons scroll left-scroll">arrow_back</span>
        <span class="material-icons scroll right-scroll">arrow_forward</span>
        <div class="compare-providers-tables-scroll-container">
            <table id="compare-providers-table-scroll-fixed" class="compare-providers-table-scroll table-striped order-column">
                <tbody>
                    <?php foreach( $table_rows as $key => $row ): ?>
                        <tr>
                            <td><?php echo $row['row_title']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="compare-providers-table-scroll-main-container">
                <table id="compare-providers-table-scroll-main" class="compare-providers-table-scroll table-striped order-column" style="width:100%">
                    <tbody>
                        <?php foreach( $table_rows as $row ): ?>
                            <tr>
                                <?php foreach( $row as $key => $cell ): ?>
                                    <?php if($key !== 'row_title'): ?>
                                        <td><?php  echo $cell; ?></td>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(is_object($providers)): ?>
    <!-- The mobile view is just a list of blocks -->
    <div class="compare-providers-blocks-list mobile">
        <h5><?php echo $compare_providers['subheading']; ?></h5>
        <h2><?php echo $compare_providers['heading']; ?></h2>
        <?php foreach( $providers->posts as $key => $provider ):
            $data_type_subfields = get_field( $provider_type, $provider->ID );
            $link = get_post_permalink( $provider->ID );
            $best_for_text = get_field( 'superlative', $provider->ID );
            if( $best_for_text ) $best_for_text = 'Best for '.$best_for_text;
        ?>
            <div class="compare-providers-block">
                <div class="provider-logo-wrapper provider-data-container">
                    <div class="provider-best-for">
                        <?php echo $best_for_text; ?>
                    </div>
                    <div class="provider-logo-container">
                        <a href="<?php echo $link; ?>" class="provider-logo-link">
                            <img src="<?php echo get_field( 'logo', $provider->ID ); ?>" class="provider-logo">
                        </a>
                    </div>
                </div>
                <div class="provider-title-container provider-data-container">
                    <div class="data-title-container">
                        Provider
                    </div>
                    <div class="data-value-container">
                        <a href="<?php echo $link; ?>" class="provider-title-link"><?php echo $provider->post_title; ?></a>
                    </div>
                </div>
                <?php foreach( $compare_providers['table_columns'] as $column ):
                    $field_name = $column[$provider_type_key];
            
                    if( isset( $data_type_subfields[$field_name] ) ) {
                        if (is_array($data_type_subfields[$field_name])){
                            $value = implode(', ', $data_type_subfields[$field_name]);
                        } else {
                             if ($field_name == 'starting_price'){
                                $value = '$'.$data_type_subfields[$field_name].'/mo';
                            } elseif ($field_name == 'minimum_channel_count') {
                                $value = $data_type_subfields[$field_name].'+';
                            } else {
                                $value = $data_type_subfields[$field_name];
                            }
                        }
                    } else {
                        $value= get_field( $field_name, $provider->ID );
                    }
                    if( !$value) $value= 'N/A';
                ?>
                    <div class="<?php echo $column[$provider_type_key].'-container provider-data-container'; ?>">
                        <div class="data-title-container">
                            <?php echo $column['column_name']; ?>
                        </div>
                        <div class="data-value-container">
                            <?php echo $value; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="cta-btn-container provider-data-container">
                    <?php
                    if ($button_type == 'link'){
                        $target='';
                        $partner = get_field('partner', $provider->ID);
                        if($partner){
                            $buyer_id = get_field('buyer', $provider->ID);
                            $campaign = get_field( 'campaign', $buyer_id );
                            foreach($campaign as $key => $camp) {
                                $type_of_partnership = $camp['type_of_partnership'];
                                if($camp['campaign_name'] == $provider->ID){
                                    if($type_of_partnership == 'call_center'){
                                        $cta_text = '<span class="material-icons">call</span>'.$camp['call_center'];
                                        $cta_link = 'tel:'.$camp['call_center'];
                                    }else{
                                        $cta_text = 'View Plans';
                                        $cta_link = $camp['digital_tracking_link'];
                                        $target = 'target="blank"';
                                    }
                                }
                                
                            }           
                        }else{
                            $cta_text = 'View Plans';
                            $cta_link = get_field('brands_website_url',$provider->ID);
                        }
                    }
                    echo $field = '<a href="'.$cta_link.'" class="cta-btn '.$zip_popup_class.' '.$class.'" '.$data_att.' '.$target.'>'.$cta_text.'</a>';

                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>    
