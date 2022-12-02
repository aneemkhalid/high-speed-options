<?php

/**
 * Provider Data Table Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
?>

<style>
.cta_btn{
    color: #fff !important;
}
</style>

<?php
// NEW CODE
$upload_icon = '<span class="material-icons max-u-speed">north</span>';
$download_icon = '<span class="material-icons max-d-speed">south</span>';
$CompareProviders = get_field('provider_data_table');
$TableStyle = $CompareProviders['table_style'];
$ProviderType = $CompareProviders['provider_type'];
$Heading = $CompareProviders['heading'];
$TableColumns = $CompareProviders['table_columns'];
$button_column_name = $CompareProviders['button_column_name'];
$ButtonText = $CompareProviders['button_text'];
$Providers = $CompareProviders['providers'];
$TableDescription = $CompareProviders['table_description'];

$popup_cta_text = $ButtonText;
    if ($popup_cta_text == ''){
        $popup_cta_text = 'Check In Your Area';
    }
    $popup_cta_link = '#';
    $rand = rand();
    $popup_data_att = 'data-toggle="modal" data-target="#zipPopupModal-'.$rand.'"';
    $popup_zip_popup_class = 'zip-popup-btn';
    
    if ($ProviderType == 'internet'){
        $popup_internet_checked = 'checked';
    } elseif ($ProviderType == 'tv'){
        $popup_tv_checked = 'checked';
    } elseif ($ProviderType == 'bundles'){
        $popup_bundle_checked = 'checked';
    }

    $cta_text = $popup_cta_text;
    $cta_link = $popup_cta_link;
    $data_att = $popup_data_att;
    $zip_popup_class = $popup_zip_popup_class;
    $internet_checked = $popup_internet_checked;
    $tv_checked = $popup_tv_checked;
    $bundle_checked = $popup_bundle_checked;
    require get_theme_file_path( '/template-parts/zip-search-popup.php' );

$FilterResult = $CompareProviders['filter_result'];
if ( $FilterResult == 1 ){
    $ProviderFilter = $CompareProviders['show_connection_types'];
}

// Default Columns
$Tbl_Columns = array( "Provider" );
$Tbl_Columns_Mobile = array( "Provider", "Type", "Max Download Speed", "Max Upload Speed", "" );
// User Added Columns
if($TableColumns) {
    foreach( $TableColumns as $Column ) {
        $Tbl_Columns[] = $Column['column_name'];
    }
    //internet_data_type
    foreach( $TableColumns as $Column ) {
        if ( $ProviderType == "internet" ){
            $Tbl_ColumnsVal[] = $Column['internet_data_type'];
        }else if( $ProviderType == "tv" ){
            $Tbl_ColumnsVal[] = $Column['tv_data_type'];
        }
    }
}
// Add Last Column
$Tbl_Columns[] = $button_column_name;



if ( $ProviderType == "internet") {
    include( "provider-data-table-internet.php" );
}else if ( $ProviderType == "tv"){
   include( "provider-data-table-tv.php" );
// NEW CODE

}else{
// OLD CODE */
$compare_providers = get_field('provider_data_table');
$heading = $compare_providers['heading'];
$provider_type = $compare_providers['provider_type'];
if ($provider_type == 'streaming'){
    $providers = $compare_providers['streaming_providers'];
} else {
    $providers = $compare_providers['providers'];
}
$table_description = $compare_providers['table_description'];
$table_columns = $compare_providers['table_columns'];
$table_style = $compare_providers['table_style'];

$desktop_table = '';
$mobile_table = '';
$upload_icon = '<span class="material-icons max-u-speed">north</span>';
$download_icon = '<span class="material-icons max-d-speed">south</span>';
$table_col_width = '';
if ($table_style == 'minimal-table'){
    $upload_icon = '';
    $download_icon = '';
    $table_col_count = count($table_columns);
    $table_col_width = 100/($table_col_count +1);
}
//dataLayer info
$providerCounter = 0;
?>
<style>
    .minimal-table .desktop-table td {
        width: <?php echo $table_col_width; ?>%;
    }
</style>
<section id="comparetable" class="compare-providers-wrap">
    <?php if(!empty($heading)) echo '<h2>'.$heading.'</h2>'; ?>
    <div class="compare-providers-table <?php echo $table_style ?>">
        <?php 
        $desktop_table .= '
        <table id="myTable" class="compare-providers-table-inner desktop-table">
            <thead>
                <th>Provider</th>';
                    if($table_columns){
                        foreach ($table_columns as $key => $column) {
                            $desktop_table .= '<th onclick="sortTable('.$key.');">'.$column['column_name'].'</th>';
                        }
                    }

                    $desktop_table .= '<th>'.$button_column_name.'</th>';
            $desktop_table .= '
            </thead>';
            $mobile_table .= '<table class="compare-providers-table-inner mobile-table">';
            if($table_columns){
                foreach ($providers as $key => $providerID) {
                        $logo = get_field('logo',$providerID);
                        $target = '';
                        if ($provider_type == 'streaming'){
                            $cta_text = 'View Plans';
                            $cta_link = get_field('link', $providerID);
                            $target = 'target="_blank"';

                        } else {
                            $internet = get_field('internet',$providerID);
                            $tv = get_field('tv',$providerID);
                            $bundles = get_field('bundles',$providerID);
                            // $partner = get_field('partner',$providerID);
                            // if($partner){
                            //     $buyer_id = get_field('buyer',$providerID);
                            //     $campaign = get_field( "campaign", $buyer_id );
                            //     // print_r($campaign);
                            //     foreach($campaign as $key => $camp) {
                            //         $type_of_partnership = $camp['type_of_partnership'];
                            //         if($camp['campaign_name'] == $providerID){
                            //             // echo $camp['call_center'];
                            //             if($type_of_partnership == 'call_center'){
                            //                 $cta_text = '<span class="material-icons">call</span>'.$camp['call_center'];
                            //                 $cta_link = 'tel:'.$camp['call_center'];
                            //             }else{
                            //                 $cta_text = 'View Plans';
                            //                 $cta_link = $camp['digital_tracking_link'];
                            //                 $target = 'target="_blank"';
                            //             }
                            //         }
                                    
                            //     }           
                            // }else{
                            //     $cta_text = 'View Plans';
                            //     $cta_link = get_field('brands_website_url',$providerID);
                            //     $target = 'target="_blank"';
                            // }
                        }
                    
                    //dataLayer info
       
                      $variantProvider = [
                                'text' => 'Compare Providers Table'
                        ]; 
                    
                    $providerSlug = get_post_field( 'post_name', get_post() );
                    $providerCounter++;
                    $providersListProductClick = dataLayerProdClick($providerID, $variantProvider, $providerCounter,  $providerSlug, $heading);

                    $desktop_table .= '
                    <tr>
                        <td>';
                        if ($table_style == 'minimal-table'){
                            if ($provider_type == 'streaming'){
                                $desktop_table .= '<span>'.get_the_title($providerID).'</span';
                                $mobile_table .= '<tr class="top-provider-row"><th>Provider</th><td><span>'.get_the_title($providerID).'</span></td></tr>';
                            } else {
                                $desktop_table .= '<a href="'.get_permalink($providerID).'" onclick="'.$providersListProductClick.'">'.get_the_title($providerID).'</a>';
                                $mobile_table .= '<tr class="top-provider-row"><th>Provider</th><td><a href="'.get_permalink($providerID).'" onclick="'.$providersListProductClick.'">'.get_the_title($providerID).'</a></td></tr>';
                            }


                        }else {
                            if(!empty($logo)) $desktop_table .= '<a href="'.get_permalink($providerID).'" onclick="'.$providersListProductClick.'"><img src="'.$logo.'" alt="'.get_the_title($providerID).'"></a>';
                            if(!empty($logo)) $mobile_table .= '<tr><th>Provider</th><td><a href="'.get_permalink($providerID).'" onclick="'.$providersListProductClick.'"><img src="'.$logo.'" alt="'.get_the_title($providerID).'"></a></td></tr>';

                        }
                        $desktop_table .= '
                        </td>';
                            if($table_columns){
                                foreach ($table_columns as $key => $column) {
                                    
                                    if($provider_type == 'internet'){ 
                                        $data = '';
                                        $data_type = $column['internet_data_type'];
                                        if($data_type == 'starting_price' || $data_type == 'max_upload_speed' || $data_type == 'max_download_speed' || $data_type == 'connection_types' || $data_type == 'data_caps' || $data_type == 'installation_fee' || $data_type == 'equipment_fee' || $data_type == 'symmetrical_speeds' || $data_type == 'free_wifi_hotspots'){
                                            if($data_type == 'connection_types'){
                                                if(!empty($internet[$data_type])){
                                                    foreach ($internet[$data_type] as $key => $dataset) {
                                                        if($key > 0)
                                                            $data .= '/'.$dataset;
                                                        else
                                                            $data = $dataset;
                                                    }
                                                }
                                            }elseif($data_type == 'max_upload_speed'){
                                                $data = $internet[$data_type];
                                                $data = $upload_icon.$data;
                                            }elseif($data_type == 'max_download_speed'){
                                                $data = $internet[$data_type];
                                                $data = $download_icon.$data;
                                            }elseif($data_type == 'starting_price'){
                                                if ( $internet['show_asterisk'] == 1 ){
                                                    $Fld = "*";
                                                }else{
                                                    $Fld = "";
                                                }
                                                $data = '$'. $internet[$data_type] .'/mo.' . $Fld;
                                            }else{
                                                $data = $internet[$data_type];
                                            }
                                        }else{
                                            $data = get_field($data_type,$providerID);
                                        }
                                        
                                    }elseif($provider_type == 'tv'){ 
                                        $data = '';
                                        $data_type = $column['tv_data_type'];
                                        if($data_type == 'starting_price' || $data_type == 'connection_types' || $data_type == 'minimum_channel_count' || $data_type == 'dvr_recordings'){
                                            if($data_type == 'connection_types'){
                                                if(!empty($tv[$data_type])){
                                                    foreach ($tv[$data_type] as $key => $dataset) {
                                                        if($key > 0)
                                                            $data .= '/'.$dataset;
                                                        else
                                                            $data = $dataset;
                                                    }
                                                }
                                            }elseif($data_type == 'starting_price'){
                                                if(!empty($tv[$data_type])){
                                                    if ( $tv['show_asterisk2'] == 1 ){
                                                        $Fld = "*";
                                                    }else{
                                                        $Fld = "";
                                                    }
                                                    $data = '$'. $tv[$data_type] .'/mo.' . $Fld;
                                                }
                                            }elseif($data_type == 'minimum_channel_count'){
                                                if(!empty($tv[$data_type]))
                                                    $data = $tv[$data_type].'+';
                                            }else{
                                                $data = $tv[$data_type];
                                            }
                                        }else{
                                            $data = get_field($data_type,$providerID);
                                        }
                                        
                                    }elseif($provider_type == 'bundles'){ 
                                        $data = '';
                                        $data_type = $column['bundle_data_type'];
                                        if($data_type == 'starting_price' || $data_type == 'max_download_speeds' || $data_type == 'minimum_channel_count'){
                                            if($data_type == 'starting_price'){
                                                if(!empty($bundles[$data_type])){
                                                    if ( $bundles['show_asterisk3'] == 1 ){
                                                        $Fld = "*";
                                                    }else{
                                                        $Fld = "";
                                                    }
                                                    $data = '$'. $bundles[$data_type] .'/mo.' . $Fld;
                                                }
                                            }elseif($data_type == 'minimum_channel_count'){
                                                if(!empty($bundles[$data_type]))
                                                    $data = $bundles[$data_type].'+';
                                            }else{
                                                $data = $bundles[$data_type];
                                            }
                                        }else{
                                            $data = get_field($data_type,$providerID);
                                        }
                                        
                                    }elseif($provider_type == 'streaming'){ 
                                        $data = '';
                                        $data_type = $column['streaming_data_type'];
                                        $data = get_field($data_type, $providerID);
                                        if($data_type == 'starting_price'){
                                            if(!empty($data)) 
                                                $data = '$'.$data.'/mo.';
                                        } elseif($data_type == 'number_of_days_for_free_trial' || $data_type == 'number_of_days_for_money-back_guarantee'){
                                            if (!empty($data)){
                                                $data = $data . ' days';
                                            }
                                        } elseif ($data_type == 'free_trial' || $data_type == 'money-back_guarantee' || $data_type == 'simultaneous_streams' || $data_type == 'user_profiles' || $data_type == 'dvr' || $data_type == 'mobile_app_available' || $data_type == 'contract_required'){
                                            if ($data){
                                                $data = 'Yes';
                                            } else {
                                                $data = 'No';
                                            }
                                        }
                                        if (empty($data)){
                                            $data = 'N/A';
                                        }
                                        
                                    }
                                    $desktop_table .= '<td>'.$data.'</td>';
                                    $mobile_table .= '<tr><th>'.$column['column_name'].'</th><td>'.$data.'</td></tr>';
                                }
                            }
                            $desktop_table .= '<td><a href="' . $cta_link . '" class="cta-btn ' . $zip_popup_class . ' ' . $class . '" ' . $data_att .'>' . $cta_text . '</a></td>';
                            $mobile_table .= '<tr><td  class="text-center" colspan="2"><a href="' . $cta_link . '" class="cta-btn ' . $zip_popup_class . ' ' . $class . '" ' . $data_att .'>' . $cta_text . '</a></td>';
                    $desktop_table .= '
                    </tr>';
                }
            }
            $mobile_table .= '
            </table>';  
        $desktop_table .= '
        </table>';

        echo $desktop_table;
        if ($table_style == 'minimal-table'):
            echo $mobile_table;
        endif;
        ?>
    </div>
    <div class="table_desc">
        <?php echo $table_description; ?>
    </div>
</section>
<?php // OLD CODE
}
?>