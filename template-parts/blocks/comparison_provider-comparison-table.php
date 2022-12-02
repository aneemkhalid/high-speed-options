<?php

/**
 * Comparison - Provider Highlights Tiles
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

global $post;

$provider_1_id = get_field('provider_1', $post->ID);
$provider_2_id = get_field('provider_2', $post->ID);



// NEW CODE
$upload_icon = '<span class="material-icons max-u-speed">north</span>';
$download_icon = '<span class="material-icons max-d-speed">south</span>';
$TableStyle = 'minimal-table';
$ProviderType = get_field('data_type');
$Heading = get_field('title');
$TableColumns = get_field(('table_columns'));
$CTAButton = true;
$Providers = [$provider_1_id, $provider_2_id];
$TableDescription = get_field('disclaimer_text');

$FilterResult = 0;

// Default Columns
$Tbl_Columns = [];
// User Added Columns
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
// Add Last Column
$Tbl_Columns[] = '';

if ( $ProviderType == "internet") {
    include( "comparison_compare-provider-tbl-internet.php" );
}else if ( $ProviderType == "tv"){
   include( "comparison_compare-provider-tbl-tv.php" );

}else{





$desktop_table = $mobile_table = '';
$providerCounter = 0;
//add logos to beginning of arrays
array_unshift($TableColumns, ['column_name'=>'Our Picks']);
$TableColumns[]['column_name'] = '';
//Desktop Bundles Table
// OLD CODE */
?>
<section id="comparetable" class="compare-providers-wrap">
    <?php if(!empty($Heading)) echo '<h2>'.$Heading.'</h2>'; ?>
    <div class="compare-providers-table <?php echo $TableStyle ?> comparison-template">
        <?php 
        $desktop_table .= '
        <table id="myTable" class="compare-providers-table-inner desktop-table">';
            if($TableColumns){
                foreach ($TableColumns as $key => $column ){

                    if ($key === count($TableColumns)-1):
                        $desktop_table .= '<tr style="background-color:#fff;">';
                    else:    
                        $desktop_table .= '<tr>';
                    endif;
                    $desktop_table .= '<th class="border-bottom-0 pt-3 pb-3" style="width:25%;"><p class="font-weight-bold mb-0">'.$column['column_name'].'</p></th>';
                    foreach ($Providers as $key2 => $providerID) {
                        $logo = get_field('logo',$providerID);
                        $target = '';
                        $bundles = get_field('bundles',$providerID);

                        $desktop_table .= '<td class="text-center" style="width:37.5%;">';

                        if ($key === 0):
                            $desktop_table .= '<img src="'.$logo.'" alt="logo" class="p-2" width="180" height="40">';

                        elseif($key === count($TableColumns)-1):
                            $rand = rand();
                            $internet_checked = $tv_checked = $bundle_checked = '';
                            if ($ProviderType == 'internet'){
                                $internet_checked = 'checked';
                            } elseif ($ProviderType == 'tv'){
                                $tv_checked = 'checked';
                            } elseif ($ProviderType == 'bundle'){
                                $bundle_checked = 'checked';
                            }
                            require get_theme_file_path( '/template-parts/zip-search-popup.php' );
                            $desktop_table .= '<a href="#" class="cta_btn zip-popup-btn font-weight-bold pt-2 pb-2" style="width:100%;" data-toggle="modal" data-target="#zipPopupModal-'.$rand.'">Check Availability</a>';
                        else:
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
                            $desktop_table .= $data;
                        endif;    
                        $desktop_table .= '</td>';                 
                    }
                    $desktop_table .= '</tr>';
                }
            } 
        $desktop_table .= '
        </table>'; 

        //Mobile Bundles table
            $mobile_table .= '<table class="compare-providers-table-inner mobile-table" style="table-layout: fixed;">';
            if($TableColumns){
                $tbl_count = 1;
                foreach ($Providers as $key2 => $providerID) {

                    $providers_spacing = '';
                    if ($tbl_count !== count($Providers)){
                        $providers_spacing = 'pb-5';
                    }
                    $tbl_count++;
                    foreach ($TableColumns as $key => $column ){

                        if ($key === count($TableColumns)-1):
                            $mobile_table .= '<tr style="background-color:#fff;">';
                        else:    
                            $mobile_table .= '<tr>';
                            $mobile_table .= '<th class="border-bottom-0 pt-3 pb-3 font-weight-bold" style="width:50%;"><p class="font-weight-bold mb-0">'.$column['column_name'].'</p></th>';
                        endif;
                        // echo '<pre>';
                        // print_r($column);
                        // echo '</pre>';
                            $logo = get_field('logo',$providerID);
                            $target = '';
                            $bundles = get_field('bundles',$providerID);

                            if ($key === 0):
                                $mobile_table .= '<td class="text-center">';
                                $mobile_table .= '<img src="'.$logo.'" alt="logo" class="p-2" width="180" height="40">';

                            elseif($key === count($TableColumns)-1):
                                $mobile_table .= '<td class="text-center '.$providers_spacing.'" colspan=100%>';
                                $rand = rand();
                                $internet_checked = $tv_checked = $bundle_checked = '';
                                if ($ProviderType == 'internet'){
                                    $internet_checked = 'checked';
                                } elseif ($ProviderType == 'tv'){
                                    $tv_checked = 'checked';
                                } elseif ($ProviderType == 'bundle'){
                                    $bundle_checked = 'checked';
                                }
                                require get_theme_file_path( '/template-parts/zip-search-popup.php' );
                                $mobile_table .= '<a href="#" class="cta_btn zip-popup-btn font-weight-bold pt-2 pb-2" style="width:100%;" data-toggle="modal" data-target="#zipPopupModal-'.$rand.'">Check Availability</a>';
                            else:
                                $mobile_table .= '<td class="text-center">';
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
                                $mobile_table .= $data;
                            endif;    
                            $mobile_table .= '</td>';                 
                        }
                    $mobile_table .= '</tr>';
                }
            } 
        $mobile_table .= '
        </table>';

        echo $desktop_table;
        echo $mobile_table;
        ?>
    </div>
    <div class="table_desc">
        <?php echo $TableDescription; ?>
    </div>
</section>
<?php 
}
