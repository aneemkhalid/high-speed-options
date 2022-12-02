<?php


/**
 * Providers Table for Locations Pages Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
use ZipSearch\ProvidersDBConnection as ProvidersDBConnection;
global $locations_provider_count;
$locations_settings = get_field('locations_settings', 'options');
$provider_table_columns = $locations_settings['provider_table_columns'];

global $post;
global $city;
global $state;
global $wpdb;
$table_name = $wpdb->prefix . "zip_tract";
$tracts_query = "SELECT tract FROM $table_name WHERE usps_zip_pref_city = '$city' AND usps_zip_pref_state = '$state'";
$row = $wpdb -> get_results($tracts_query);
$tract_arr = [];
foreach($row as $tract){
	$tract_arr[] = "'".$tract->tract."'";
}
$tract_arr = array_unique($tract_arr);
$tract_where = implode(', ', $tract_arr);
$provider_table_name = $wpdb->prefix . "broadband_hso";
$sql = "SELECT DISTINCT hso_provider FROM $provider_table_name WHERE census_block_fips_code_11 IN ($tract_where) AND state = '$state'";
$providers = $wpdb -> get_results($sql);

$table_name = $wpdb->prefix . "city_api_call";
$api_query = "SELECT provider_data FROM $table_name WHERE city = '$city' AND state = '$state'";
$row = $wpdb -> get_results($api_query);
$providers_arr_new = [];
if (!empty($row)){
    $api_data = json_decode($row[0]->provider_data);
    $api_arr = $api_data->internet;
    foreach($api_arr as $item){
        $providers_arr_new[] = $item->name;
    }
}
foreach($providers as $provider){
    $providers_arr_new[] = $provider->hso_provider;
}

$desktop_table = '';
$mobile_table = '';

add_filter('posts_where', 'my_posts_where');

// args
$args = array(
    'numberposts'   => -1,
    'post_type'     => 'provider',
    'suppress_filters' => false,
    'fields'        => 'ids',
    'meta_query'    => array(
        array(
            'key'       => 'possible_provider_names_$_name',
            'compare'   => 'IN',
            'value'     => $providers_arr_new,
        ),
    )
);
$Providers = get_posts($args);

$locations_provider_count = count($Providers);

// NEW CODE
$upload_icon = '<span class="material-icons max-u-speed">north</span>';
$download_icon = '<span class="material-icons max-d-speed">south</span>';
$TableColumns = $locations_settings['provider_table_columns'];
$TableStyle = 'minimal-table';
$CTAButton = '';
$ProviderFilter = '';
$Heading = '';
$TableDescription = '';

$FilterResult = 0;

// Default Columns
$Tbl_Columns = array( "Provider" );
$Tbl_Columns_Mobile = array( "Provider", "Type", "Max Download Speed", "Max Upload Speed", "" );
// User Added Columns
foreach( $TableColumns as $Column ) {
    $Tbl_Columns[] = $Column['column_name'];
    $Tbl_ColumnsVal[] = $Column['column'];
}

// Add Last Column
$Tbl_Columns[] = '';

include( "compare-provider-tbl-internet.php" );

