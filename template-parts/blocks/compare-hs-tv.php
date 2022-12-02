<style>
.compare-providers-blocks-list .compare-providers-block .cta_button-container .cta-btn {
    padding: 11px 21px 11px 22px;
    margin: 20px auto;
    border-radius: 10px;
    color: #fff;
    background-color: #0091ff;
    transition: all ease-in-out 0.2s;
}
</style>
<?php
$Heading = $CompareProvidersHs['heading'];
$SubHeading = $CompareProvidersHs['subheading'];
$TableColumns = $CompareProvidersHs['table_columns'];
$Providers = $CompareProvidersHs['providers'];
$ButtonType = $CompareProvidersHs['button_type'];
$ButtonText = $CompareProvidersHs['button_text'];
//Get Button Type

//dataLayer info
$providerScrollCounter = 0;
$providerMobileScrollCounter = 0;
$providerScrollProductClickArray = [];
$checkBtnProviderIDArray= [];

$providerScrollProductClickMobileArray = [];
$checkBtnProviderIDMobileArray= [];



if ($ButtonType == 'popup'){
    $popup_cta_text = $ButtonText;
    if ($popup_cta_text == ''){
        $popup_cta_text = 'Check Availability';
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
}

// Default Columns
$Tbl_Columns = array( "Our Picks", "Provider" );
// User Added Columns
if ( strtolower( $ProviderType ) == "tv" ){
     $DataType = "tv_data_type";
}
foreach( $TableColumns as $Column ) {
    $Tbl_Columns[] = $Column['column_name'];
}
// Add Last Column
$Tbl_Columns[] = 'How to Buy';

$Tbl_Columns_val = array();
foreach( $TableColumns as $Column ) {
    $Tbl_Columns_val[] = $Column[$DataType];
}

// Get providers list
$Detail_Table_HS  = array();
$Ind = 0;

$PrdInd = 0;
foreach( $Providers as $Provider ) {
    $Showtv = get_field( 'tv_check', $Provider );
    


    if ( $Showtv == 1 ){
        $Superlative = get_field( 'superlative', $Provider );
        $ProviderLogo = get_field( 'logo', $Provider );
        $Provider_Title = get_the_title( $Provider );

        $Detail_Table_HS['our_pick'][$Ind]['superlative'] = $Superlative;
        $Detail_Table_HS['our_pick'][$Ind]['logo'] = $ProviderLogo;
        $Detail_Table_HS['our_pick'][$Ind]['url'] = get_post_permalink( $Provider );
        $Detail_Table_HS['our_pick'][$Ind]['title'] = $Provider_Title;

        

        $Detail_Table_HS['provider_name'][$Ind]['name'] = $Provider_Title;
        $Detail_Table_HS['provider_name'][$Ind]['url'] = get_post_permalink( $Provider );

    
        $Internet = get_field('tv', $Provider );

        $SplitOut = $Internet['split_out_connection'];
    
        for ( $b = 0; $b <= (count($Tbl_Columns_val)-1); $b++ ){
            $UserCol = strtolower($Tbl_Columns_val[$b]);

            if ( strtolower($UserCol) == "connection_types" ){
                $Detail_Table_HS[$UserCol][] = $Internet[$Tbl_Columns_val[$b]];
            }else{
                $ProviderDetailsArr = array( "contracts", "acsi_rating", "fixed_price_guarentee", "credit_check_required", "contract_buyouts", "early_termination_fee" );
                $Internet2 = get_field_object('internet', $Provider );

                if ( $SplitOut == 0 || $SplitOut == "" ){
                    if( in_array( strtolower($UserCol), $ProviderDetailsArr) ){                        
                        $Detail_Table_HS[$UserCol][] = get_field( $UserCol, $Provider );
                    }else{
 
                        if( $UserCol == "starting_price" ){
                            $MinPreA = ''; $MaxPreA = '';
                            $MinValCol = "min_" . $UserCol;
                            $MaxValCol = "max_" . $UserCol;
                            $MinPreA = getPre($MinValCol, $Internet2, 'true');
                            $MaxPreA = getPre($MaxValCol, $Internet2, 'true');                           
                            $MinVal = $Internet['details'][$MinValCol];
                            $MaxVal = $Internet['details'][$MaxValCol];
                            if ( $Internet['details']['show_asterisk2'] == 1 ){
                                $ShowAsterisk0 = '*';
                            }else{
                                $ShowAsterisk0 = '';
                            }
                            if ($MinVal == $MaxVal ){
                                $Detail_Table_HS[$UserCol][] = $MaxVal . $ShowAsterisk0 ;  
                            } else if ($MinVal == ""){
                                $Detail_Table_HS[$UserCol][] = $MaxPreA . $MaxVal . $ShowAsterisk0;
                            } else if ($MaxVal == ""){
                                $Detail_Table_HS[$UserCol][] = $MinVal . $ShowAsterisk0;
                            } else {
                                $Detail_Table_HS[$UserCol][] = $MinVal . " – " . $MaxPreA . $MaxVal . $ShowAsterisk0;
                            }
                        }elseif( $UserCol == "tv_installation_fee" ){
                            //Installation Fee                            
                            $MinVal = $Internet['details']['tv_install_fee']['tv_installation_fee_min'];
                            $MaxVal = $Internet['details']['tv_install_fee']['tv_installation_fee_max'];
                            if ($MinVal == $MaxVal ){
                                $Detail_Table_HS[$UserCol][] = $MaxVal;
                            } else if ($MinVal == ""){
                                $Detail_Table_HS[$UserCol][] = $MaxVal;
                            } else if ($MaxVal == ""){
                                $Detail_Table_HS[$UserCol][] = $MinVal;
                            } else {
                                $Detail_Table_HS[$UserCol][] = $MinVal . " – " . $MaxVal;
                            }
                            
                        }else if( $UserCol == "tv_equipment_fee" ){
                            //internet_equipment_rental_fee
                            $MinVal = $Internet['details']['tv_equipment_rental_fee']['tv_equipment_rental_fee_min'];
                            $MaxVal = $Internet['details']['tv_equipment_rental_fee']['tv_equipment_rental_fee_max'];
                            if ($MinVal == $MaxVal ){
                                $Detail_Table_HS[$UserCol][] = $MaxVal;  
                            } else if ($MinVal == ""){
                                $Detail_Table_HS[$UserCol][] = $MaxVal;
                            } else if ($MaxVal == ""){
                                $Detail_Table_HS[$UserCol][] = $MinVal;
                            } else {
                                $Detail_Table_HS[$UserCol][] = $MinVal . " – " . $MaxVal;
                            }
                        }else if( $UserCol == "dvr_recordings" ){
                            $MinVal = $Internet['details']['min_dvr_recordings'];
                            $MaxVal = $Internet['details']['max_dvr_recordings'];
                            if ($MinVal == $MaxVal ){
                                $Detail_Table_HS[$UserCol][] = $MaxVal;  
                            } else if ($MinVal == ""){
                                $Detail_Table_HS[$UserCol][] = $MaxVal;
                            } else if ($MaxVal == ""){
                                $Detail_Table_HS[$UserCol][] = $MinVal;
                            } else {
                                $Detail_Table_HS[$UserCol][] = $MinVal . " – " . $MaxVal;
                            }
                        }else{
                             // channel count
                             $MinPreA = ''; $MaxPreA = '';
                             $ColVal = $UserCol;
                             $ColVal = str_replace("minimum", "", $ColVal);
                             $MinValCol = "min" . $ColVal;
                             $MaxValCol = "max" . $ColVal;
                             $MinVal = $Internet['details'][$MinValCol];
                             $MaxVal = $Internet['details'][$MaxValCol];
                             if ($MinVal == $MaxVal ){
                                $Detail_Table_HS[$UserCol][] = $MaxVal;  
                            } else if ($MinVal == ""){
                                $Detail_Table_HS[$UserCol][] = $MaxVal;
                            } else if ($MaxVal == ""){
                                $Detail_Table_HS[$UserCol][] = $MinVal;
                            } else {
                                $Detail_Table_HS[$UserCol][] = $MinVal . " – " . $MaxVal;
                            }
                        }
                    }

                }else{

                    if( in_array( strtolower($UserCol), $ProviderDetailsArr) ){
                        $Detail_Table_HS[$UserCol][] = get_field($UserCol, $Provider);
                    }else{
                        $ConectionTypes = $Internet['connection_types'];
                        $Ind2 = 0;

                        foreach ( $ConectionTypes as $ConectionType ){
                            $ConType = $Internet[$ConectionType . '_connection'];
                            $Colms = array( "starting_price", "max_upload_speed", "max_download_speed", "symmetrical_speeds", "data_caps" );

                            switch ($UserCol) {

                                case "starting_price":
                                    $Astk = $ConType[$ConectionType . '_show_asterisk'];
                                    if ( $Astk == 1 ){
                                        $ShowAsterisk = '*';
                                    }else{
                                        $ShowAsterisk = '';
                                    }
                                    $StPrices[] = $ConType[$ConectionType . '_before_' . $UserCol] . "^" . $ConType[$ConectionType . '_' . $UserCol] . "^" . $ConType[$ConectionType . '_after_' . $UserCol] . $ShowAsterisk;
                                    foreach($StPrices as $StPrice){
                                        $PriceArrayRaw[] = explode("^", $StPrice);
                                    }
                                    if ( isset($PrdInd, $Detail_Table_HS[$UserCol]) ) {
                                        if ( $Detail_Table_HS[$UserCol][$PrdInd] != "" ){
                                            $Detail_Table_HS[$UserCol][$PrdInd] = $Detail_Table_HS[$UserCol][$PrdInd] . "|" . $StPrice;
                                        }else{
                                            $Detail_Table_HS[$UserCol][$PrdInd] = $StPrice;
                                        }
                                    }else{
                                        $Detail_Table_HS[$UserCol][$PrdInd] = $StPrice;
                                    }
                                break;
                                
                                case "minimum_channel_count":
                                    $ChnCount = $ConType[$ConectionType . '_before_max_channel_count'] . "^" . $ConType[$ConectionType . '_max_channel_count'] . "^" . $ConType[$ConectionType . '_after_max_channel_count'];
                                    $Detail_Table_HS[$UserCol][$PrdInd][] = $ChnCount;
                                break;
                                case "dvr_recordings":
                                    //satellite_max_channel_count
                                    $dvr = $ConType[$ConectionType . '_before_dvr_recordings'] . "^" . $ConType[$ConectionType . '_dvr_recordings'] . "^" . $ConType[$ConectionType . '_after_dvr_recordings'];
                                    $Detail_Table_HS[$UserCol][$PrdInd][] = $dvr;
                                break;
                                case "tv_installation_fee":
                                    //satellite_max_channel_count
                                    $tvInstallFee = $ConType[$ConectionType . '_before_tv_installation_fee'] . "^" . $ConType[$ConectionType . '_tv_installation_fee'] . "^" . $ConType[$ConectionType . '_after_tv_installation_fee'];
                                    $Detail_Table_HS[$UserCol][$PrdInd][] = $tvInstallFee;
                                break;
                                case "tv_equipment_fee":
                                    //satellite_max_channel_count
                                    $tvEquipmentFee = $ConType[$ConectionType . '_before_tv_equipment_fee'] . "^" . $ConType[$ConectionType . '_tv_equipment_fee'] . "^" . $ConType[$ConectionType . '_after_tv_equipment_fee'];
                                    $Detail_Table_HS[$UserCol][$PrdInd][] = $tvEquipmentFee;
                                break;
                            }
                            
                        }                            
                    }
                }
            }
        }

        if ($ButtonType == 'link'){
            $target='';
            $partner = get_field('partner', $Provider);

            if($partner){
                $buyer_id = get_field('buyer', $Provider);
                $campaign = get_field( 'campaign', $buyer_id );
                foreach($campaign as $key => $camp) {
                    $type_of_partnership = $camp['type_of_partnership'];
                    if($camp['campaign_name'] == $Provider){
                        if($type_of_partnership == 'call_center'){
                            $cta_text = '<span class="material-icons">call</span>'.$camp['call_center'];
                            $cta_link = 'tel:'.$camp['call_center'];
                        }else{
                            $cta_text = 'Order Online';
                            $cta_link = $camp['digital_tracking_link'];
                            $target = 'target="blank"';
                        }
                    }
                }           
            }else{
                $cta_text = 'View Plans';
                $cta_link = get_field('brands_website_url',$Provider);
            }
        }
        $Detail_Table_HS['cta_button'][$Ind]['cta_link'] = $cta_link;
        $Detail_Table_HS['cta_button'][$Ind]['zip_popup_class'] = $zip_popup_class;
        $Detail_Table_HS['cta_button'][$Ind]['class'] = '';
        $Detail_Table_HS['cta_button'][$Ind]['data_att'] = $data_att;
        $Detail_Table_HS['cta_button'][$Ind]['target'] = '';
        $Detail_Table_HS['cta_button'][$Ind]['cta_text'] = $cta_text;
    }
    $Ind++;
    $PrdInd++;
}
?>

<div class="compare-providers-table-scroll-container desktop">
        <h5><?php echo $SubHeading; ?></h5>
        <h2><?php echo $Heading; ?></h2>
        <span class="material-icons scroll left-scroll">arrow_back</span>
        <span class="material-icons scroll right-scroll">arrow_forward</span>
        <div class="compare-providers-tables-scroll-container">
            <table id="compare-providers-table-scroll-fixed" class="compare-providers-table-scroll table-striped order-column">
                <tbody>
                    <?php foreach( $Tbl_Columns as $Tbl_Column ){ ?>
                        <tr>
                            <td><?php echo $Tbl_Column; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="compare-providers-table-scroll-main-container">
                <table id="compare-providers-table-scroll-main" class="compare-providers-table-scroll table-striped order-column" style="width:100%">
                    <tbody>
                        <?php
                             
                            foreach ( $Detail_Table_HS as $keys => $rows ){                             
                                echo "<tr>";
                                $Ind = 0;
                                
                                
                                foreach( $rows as $row ){
                                    echo "  <td>";
                                     //dataLayer info
                                    $variantProvider = [
                                        'text' => 'Horizontal Scroll Desktop'
                                    ]; 

                                      $providerScrollSlug = get_post_field( 'post_name', get_post() );

                                    if ($keys == "our_pick" ) {
                                        $providerScrollCounter++;
                                        
                                        $checkBtnProviderID = url_to_postid($row['url']);
                                        $providerScrollProductClick = dataLayerProdClick($checkBtnProviderID , $variantProvider, $providerScrollCounter,  $providerScrollSlug, $row['superlative']);
                                        array_push($providerScrollProductClickArray, $providerScrollProductClick);
                                         array_push($checkBtnProviderIDArray, $checkBtnProviderID);
                                    }
                                    
                                 
                                    
                                    switch ($keys) {
                                            
                                                

                                        case "our_pick":                            
                                            echo '<div class="provider-logo-wrapper" id="provider-column-256">';
                                            echo '  <div class="provider-best-for">' . $row['superlative'] . '</div>';
                                            echo '  <div class="provider-logo-container">';
                                            echo '  <a href="' . $row['url'] . '" class="provider-logo-link" onclick="'.$providerScrollProductClick.'">';
                                            echo '    <img src="' . $row['logo'] . '" class="provider-logo" alt="' . $row['title'] . '" height="50" width="150">';
                                            echo '  </a>';
                                            echo '  </div>';
                                            echo '</div>';
                                        break;

                                        case "provider_name":
                                            echo '<a href="' . $row['url'] . '" class="provider-title-link" onclick="'.$providerScrollProductClickArray[$Ind].'">' . $row['name'] . '</a>';
                                             //$providerScrollCounter2++;
                                        break;

                                        case "connection_types":
                                            $Connection_types = '';
                                            foreach ($row as $ConectionTyp ){
                                                if ( $Connection_types == "" ){
                                                    $Connection_types = ucfirst($ConectionTyp);
                                                }else{
                                                    $Connection_types .= "/" .  ucfirst($ConectionTyp);
                                                }
                                            }
                                            echo $Connection_types;
                                        break;

                                        case "starting_price":
                                            $pattern = "/(\|)/i";
                                            $pattern2 = "/(\*)/i";
                                            $ShowAst = ''; 
                                            if ( preg_match($pattern2, $row) ){
                                                $ShowAst = '*';
                                            }
                                            $StrData = str_replace("*","", $row );

                                            if ( preg_match($pattern, $StrData) ){
                                            $StrPriceArr = explode("|", $StrData);
                                            $Pind = 0;
                                            foreach($StrPriceArr as $price){
                                                $PriceArray = explode("^", $price);
                                                $valPriceArray[$Pind] = $PriceArray;
                                                $Pind++;
                                            }
                                            
                                            $MinVal = min(array_filter(array_column($valPriceArray, 1)));
                                            $key_min = array_search($MinVal, array_column($valPriceArray, 1));
                                            
                                            $MaxVal = max(array_filter(array_column($valPriceArray, 1)));
                                            $key_max = array_search($MaxVal, array_column($valPriceArray, 1));
                                            
                                            if($MinVal == $MaxVal){
                                            echo $valPriceArray[$key_max][0] . $MaxVal . $valPriceArray[$key_max][2] . $ShowAst;
                                            } else {
                                            echo $valPriceArray[$key_min][0] . $MinVal . $valPriceArray[$key_min][2] . " – " . $valPriceArray[$key_max][0] . $MaxVal . $valPriceArray[$key_max][2] . $ShowAst;
                                            }
                                            } else {
                                                $StrPrice = explode("^", $row);
                                                echo $StrPrice[0] . $StrPrice[1] . $StrPrice[2];
                                            }
                                            
                                        break;

                                        case "minimum_channel_count":
                                            
                                            if ( !is_array($row) ){
                                                echo $row;
                                            }else{
                                                foreach($row as $key => $IndVal){
                                                    $DetailVal2[$key] = explode("^", $IndVal);
                                                }
                                                $MinChannelCount = min(array_filter(array_column($DetailVal2, 1)));
                                                $keyMinChannelCount = array_search($MinChannelCount, array_column($DetailVal2, 1));

                                                $MaxChannelCount = max(array_filter(array_column($DetailVal2, 1)));
                                                $keyMaxChannelCount = array_search($MaxChannelCount, array_column($DetailVal2, 1));
                                                
                                                if($MinChannelCount == $MaxChannelCount) {
                                                    echo $DetailVal2[$keyMaxChannelCount][0] . $MaxChannelCount . $DetailVal2[$keyMaxChannelCount][2];
                                                } else {
                                                    echo $DetailVal2[$keyMinChannelCount][0] . $MinChannelCount . $DetailVal2[$keyMinChannelCount][2] . " – " . $DetailVal2[$keyMaxChannelCount][0] . $MaxChannelCount . $DetailVal2[$keyMaxChannelCount][2];
                                                }
                                            }
                                        break;
                                        case "dvr_recordings":
                                            if ( !is_array($row) ){
                                                echo $row;
                                            }else{
                                                foreach($row as $key => $IndVal){
                                                    $DetailVal3[$key] = explode("^", $IndVal); 
                                                }
                                                $MinDvrRecording = min(array_filter(array_column($DetailVal3, 1)));
                                                $keyMinDvrRecording = array_search($MinDvrRecording, array_column($DetailVal3, 1));
    
                                                $MaxDvrRecording = max(array_filter(array_column($DetailVal3, 1)));
                                                $keyMaxDvrRecording = array_search($MaxDvrRecording, array_column($DetailVal3, 1));
                                                
                                                if($MinDvrRecording == $MaxDvrRecording) {
                                                $CellData = $DetailVal3[$keyMaxDvrRecording][0] . $MaxDvrRecording . $DetailVal3[$keyMaxDvrRecording][2];
                                                } else if ($MinDvrRecording == "") {
                                                    $CellData = $DetailVal3[$keyMaxDvrRecording][0] . $MaxDvrRecording . $DetailVal3[$keyMaxDvrRecording][2];
                                                } else if ($MaxDvrRecording == "") {
                                                    $CellData = $DetailVal3[$keyMinDvrRecording][0] . $MinDvrRecording . $DetailVal3[$keyMinDvrRecording][2];
                                                } else {
                                                    $CellData = $DetailVal3[$keyMinDvrRecording][0] . $MinDvrRecording . $DetailVal3[$keyMinDvrRecording][2] . " – " . $DetailVal3[$keyMaxDvrRecording][0] . $MaxDvrRecording . $DetailVal3[$keyMaxDvrRecording][2];
                                                }
                                                echo $CellData;
                                            }
                                        break;
                                        case "tv_installation_fee":
                                            if ( !is_array($row) ){
                                                echo $row;
                                            }else{
                                                foreach($row as $key => $IndVal){
                                                    $DetailVal[$key] = explode("^", $IndVal); 
                                                }
                                                $MinInstallFee = min(array_filter(array_column($DetailVal, 1)));
                                                $keyMin = array_search($MinInstallFee, array_column($DetailVal, 1));
    
                                                $MaxInstallFee = max(array_filter(array_column($DetailVal, 1)));
                                                $keyMax = array_search($MaxInstallFee, array_column($DetailVal, 1));
                                                
                                                if($MinInstallFee == $MaxInstallFee) {
                                                $CellData = $DetailVal[$keyMax][0] . $MaxInstallFee . $DetailVal[$keyMax][2];
                                                } else if ($MinInstallFee == "") {
                                                    $CellData = $DetailVal[$keyMax][0] . $MaxInstallFee . $DetailVal[$keyMax][2];
                                                } else if ($MaxInstallFee == "") {
                                                    $CellData = $DetailVal[$keyMin][0] . $MinInstallFee . $DetailVal[$keyMin][2];
                                                } else {
                                                    $CellData = $DetailVal[$keyMin][0] . $MinInstallFee . $DetailVal[$keyMin][2] . " – " . $DetailVal[$keyMax][0] . $MaxInstallFee . $DetailVal[$keyMax][2];
                                                }
                                                echo $CellData;
                                            }
                                        break;
                                        case "tv_equipment_fee":
                                            if ( !is_array($row) ){
                                                echo $row;
                                            }else{
                                                foreach($row as $key => $IndVal){
                                                    $DetailVal1[$key] = explode("^", $IndVal); 
                                                }
                                                $MinEquipFee = min(array_filter(array_column($DetailVal1, 1)));
                                                $keyMinEquipFee = array_search($MinEquipFee, array_column($DetailVal1, 1));
    
                                                $MaxEquipFee = max(array_filter(array_column($DetailVal1, 1)));
                                                $keyMaxEquipFee = array_search($MaxEquipFee, array_column($DetailVal1, 1));
                                                
                                                if($MinEquipFee == $MaxEquipFee) {
                                                $CellData = $DetailVal1[$keyMaxEquipFee][0] . $MaxEquipFee . $DetailVal1[$keyMaxEquipFee][2];
                                                } else if ($MinEquipFee == "") {
                                                    $CellData = $DetailVal1[$keyMaxEquipFee][0] . $MaxEquipFee . $DetailVal1[$keyMaxEquipFee][2];
                                                } else if ($MaxEquipFee == "") {
                                                    $CellData = $DetailVal1[$keyMinEquipFee][0] . $MinEquipFee . $DetailVal1[$keyMinEquipFee][2];
                                                } else {
                                                    $CellData = $DetailVal1[$keyMinEquipFee][0] . $MinEquipFee . $DetailVal1[$keyMinEquipFee][2] . " – " . $DetailVal1[$keyMaxEquipFee][0] . $MaxEquipFee . $DetailVal1[$keyMaxEquipFee][2];
                                                }
                                                echo $CellData;
                                            }
                                        break;
                                        case "contracts":
                                        case "acsi_rating":
                                        case "credit_check_required":
                                        case "contract_buyouts":
                                        case "early_termination_fee":
                                        case "fixed_price_guarentee":
                                            echo $row;
                                        break;

                                        case "cta_button":

                                            //dataLayer info
                                            if ($cta_text == 'Check Availability'){
                                              
                                                $dataCheckAvailOnClick = 'onclick="'.dataLayerCheckAvailabilityClick($checkBtnProviderIDArray[$Ind], $providerScrollSlug).'"';                                         
                                           }
                                            echo '<a href="' . $cta_link . '" class="cta-btn ' . $zip_popup_class . ' ' . $class . '" ' . $data_att . ' ' . $target . ' ' . $dataCheckAvailOnClick . '>' . $cta_text . '</a>';
                                        break;

                                        default:
                                             echo $row;
                                    }
                                    echo "  </td>";
                                    $Ind++;
                                }
                                echo "</tr>";                                
                            }                            
                        ?>
                    </tbody>
                    </table>
            </div>
        </div>
        <?php $source = $CompareProvidersHs['source'];
        echo '<figcaption class="figcaption-source">'.$source.'</figcaption>'; ?>
</div>

<div class="compare-providers-blocks-list mobile">
        <h5><?php echo $SubHeading; ?></h5>
        <h2><span class="ez-toc-section" id="our-top-bundle-picks-3"></span><?php echo $Heading; ?><span class="ez-toc-section-end"></span></h2>
        
        <?php
        for ( $a = 0; $a<= (count($Providers)-1); $a++){
            $Ind = 0;
            $TotalColmns = (count($Detail_Table_HS)-1);

            echo '<div class="compare-providers-block">';
            $RowInd = 0;
            foreach( $Detail_Table_HS as $key => $row ){
                 //dataLayer info
                        $variantProvider = [
                                        'text' => 'Horizontal Scroll Mobile'
                                    ]; 

                          $providerScrollSlug = get_post_field( 'post_name', get_post() );

                        if ($Ind == 0 ) {
                            $providerMobileScrollCounter++;

                            $checkBtnProviderMobileID = url_to_postid($row[$a]['url']);
                            $providerScrollMobileProductClick = dataLayerProdClick($checkBtnProviderMobileID , $variantProvider, $providerMobileScrollCounter,  $providerScrollSlug, $row[$a]['superlative']);
                            array_push($providerScrollProductClickMobileArray, $providerScrollMobileProductClick);
                            array_push($checkBtnProviderIDMobileArray, $checkBtnProviderMobileID);
                        }
                
                if ( $Ind == 0 ){
                    echo '<div class="provider-logo-wrapper provider-data-container">';
                    echo '    <div class="provider-best-for">' . $row[$a]['superlative'] . '</div>';
                    echo '    <div class="provider-logo-container">';
                    echo '        <a href="'. $row[$a]['url'] . '" class="provider-logo-link" onclick="'.$providerScrollMobileProductClick.'">';
                    echo '            <img src="'. $row[$a]['logo'] . '" class="provider-logo" alt="'. $row[$a]['title'] .'" width="200" height="50">';
                    echo '        </a>';
                    echo '    </div>';
                    echo '</div>';
                }else{
                    if ( $TotalColmns == $Ind ){
                        //dataLayer info
                            if ($cta_text == 'Check Availability'){
                                              
                                $dataCheckAvailMobileOnClick = 'onclick="'.dataLayerCheckAvailabilityClick($checkBtnProviderIDMobileArray[$a], $providerScrollSlug).'"';                                         
                           }
                        
                        echo '<div class="cta-btn-container provider-data-container">';                        
                        echo '<a href="' . $cta_link . '" class="cta-btn ' . $zip_popup_class . ' ' . $class . '" ' . $data_att . ' ' . $target . ' ' . $dataCheckAvailMobileOnClick . '>' . $cta_text . '</a>';
                    }else{
                        echo '<div class="' . $key . '-container provider-data-container">';
                        $RowVal = '';
                        switch ($key) {
                            case "provider_name":
                                $RowVal = '';
                                $RowVal = '<a href="' . $row[$a]['url'] . '" class="provider-title-link" onclick="'.$providerScrollProductClickMobileArray[$a].'">' . $row[$a]['name'] . '</a>';
                            break;
                            
                            case "connection_types":                               
                                $RowVal = '';
                                $Connection_types = '';
                                foreach ($row[$a] as $ConectionTyp ){
                                    if ( $Connection_types == "" ){
                                        $Connection_types = ucfirst($ConectionTyp);
                                    }else{
                                        $Connection_types .= "/" . ucfirst($ConectionTyp);
                                    }
                                }
                                $RowVal = $Connection_types;                                
                            break;
                                                        
                            case "starting_price":
                                $RowVal = '';
                                $pattern = "/(\|)/i";
                                $pattern2 = "/(\*)/i";
                                $ShowAst = ''; 
                                if ( preg_match($pattern2, $row[$a]) ){
                                    $ShowAst = '*';
                                }
                                $StrData = str_replace("*","", $row[$a] );

                                if ( preg_match($pattern, $StrData) ){
                                $StrPriceArr = explode("|", $StrData);
                                $Pind = 0;
                                foreach($StrPriceArr as $price){
                                    $PriceArray = explode("^", $price);
                                    $valPriceArray[$Pind] = $PriceArray;
                                    $Pind++;
                                }
                                
                                $MinVal = min(array_filter(array_column($valPriceArray, 1)));
                                $key_min = array_search($MinVal, array_column($valPriceArray, 1));
                                
                                $MaxVal = max(array_filter(array_column($valPriceArray, 1)));
                                $key_max = array_search($MaxVal, array_column($valPriceArray, 1));
                                
                                if($MinVal == $MaxVal){
                                    $RowVal = $valPriceArray[$key_max][0] . $MaxVal . $valPriceArray[$key_max][2] . $ShowAst;
                                } else {
                                    $RowVal = $valPriceArray[$key_min][0] . $MinVal . $valPriceArray[$key_min][2] . " – " . $valPriceArray[$key_max][0] . $MaxVal . $valPriceArray[$key_max][2] . $ShowAst;
                                }
                                } else {
                                    $StrPrice = explode("^", $row[$a]);
                                    $RowVal = $StrPrice[0] . $StrPrice[1] . $StrPrice[2];
                                }
                            break;

                            case "minimum_channel_count":
                                if ( !is_array( $row[$a] ) ){
                                    $RowVal = $row[$a];
                                }else{
                                    foreach($row[$a] as $key => $IndVal){
                                        $DetailVal2[$key] = explode("^", $IndVal); 
                                    }
                                    $MinChannelCount = min(array_filter(array_column($DetailVal2, 1)));
                                    $keyMinChannelCount = array_search($MinChannelCount, array_column($DetailVal2, 1));

                                    $MaxChannelCount = max(array_filter(array_column($DetailVal2, 1)));
                                    $keyMaxChannelCount = array_search($MaxChannelCount, array_column($DetailVal2, 1));
                                    
                                    if($MinChannelCount == $MaxChannelCount) {
                                    $RowVal = $DetailVal2[$keyMaxChannelCount][0] . $MaxChannelCount . $DetailVal2[$keyMaxChannelCount][2];
                                    } else if ($MinChannelCount == "") {
                                        $RowVal = $DetailVal2[$keyMaxChannelCount][0] . $MaxChannelCount . $DetailVal2[$keyMaxChannelCount][2];
                                    } else if ($MaxChannelCount == "") {
                                        $RowVal = $DetailVal2[$keyMinChannelCount][0] . $MinChannelCount . $DetailVal2[$keyMinChannelCount][2];
                                    } else {
                                        $RowVal = $DetailVal2[$keyMinChannelCount][0] . $MinChannelCount . $DetailVal2[$keyMinChannelCount][2] . " – " . $DetailVal2[$keyMaxChannelCount][0] . $MaxChannelCount . $DetailVal2[$keyMaxChannelCount][2];
                                    }
                                }
                                
                            break;
                            case "dvr_recordings":
                                if ( !is_array( $row[$a] ) ){
                                    $RowVal = $row[$a];
                                }else{
                                    foreach($row[$a] as $key => $IndVal){
                                        $DetailVal3[$key] = explode("^", $IndVal); 
                                    }
                                    $MinDvrRecording = min(array_filter(array_column($DetailVal3, 1)));
                                    $keyMinDvrRecording = array_search($MinDvrRecording, array_column($DetailVal3, 1));

                                    $MaxDvrRecording = max(array_filter(array_column($DetailVal3, 1)));
                                    $keyMaxDvrRecording = array_search($MaxDvrRecording, array_column($DetailVal3, 1));
                                    
                                    if($MinDvrRecording == $MaxDvrRecording) {
                                    $RowVal = $DetailVal3[$keyMaxDvrRecording][0] . $MaxDvrRecording . $DetailVal3[$keyMaxDvrRecording][2];
                                    } else if ($MinDvrRecording == "") {
                                        $RowVal = $DetailVal3[$keyMaxDvrRecording][0] . $MaxDvrRecording . $DetailVal3[$keyMaxDvrRecording][2];
                                    } else if ($MaxDvrRecording == "") {
                                        $RowVal = $DetailVal3[$keyMinDvrRecording][0] . $MinDvrRecording . $DetailVal3[$keyMinDvrRecording][2];
                                    } else {
                                        $RowVal = $DetailVal3[$keyMinDvrRecording][0] . $MinDvrRecording . $DetailVal3[$keyMinDvrRecording][2] . " – " . $DetailVal3[$keyMaxDvrRecording][0] . $MaxDvrRecording . $DetailVal3[$keyMaxDvrRecording][2];
                                    }
                                }
                                
                            break;
                            case "tv_installation_fee":
                                if ( !is_array( $row[$a] ) ){
                                    $RowVal = $row[$a];
                                }else{
                                    foreach($row[$a] as $key => $IndVal){
                                        $DetailVal[$key] = explode("^", $IndVal); 
                                    }
                                    $MinInstallFee = min(array_filter(array_column($DetailVal, 1)));
                                    $keyMin = array_search($MinInstallFee, array_column($DetailVal, 1));

                                    $MaxInstallFee = max(array_filter(array_column($DetailVal, 1)));
                                    $keyMax = array_search($MaxInstallFee, array_column($DetailVal, 1));
                                    
                                    if($MinInstallFee == $MaxInstallFee) {
                                    $RowVal = $DetailVal[$keyMax][0] . $MaxInstallFee . $DetailVal[$keyMax][2];
                                    } else if ($MinInstallFee == "") {
                                        $RowVal = $DetailVal[$keyMax][0] . $MaxInstallFee . $DetailVal[$keyMax][2];
                                    } else if ($MaxInstallFee == "") {
                                        $RowVal = $DetailVal[$keyMin][0] . $MinInstallFee . $DetailVal[$keyMin][2];
                                    } else {
                                        $RowVal = $DetailVal[$keyMin][0] . $MinInstallFee . $DetailVal[$keyMin][2] . " – " . $DetailVal[$keyMax][0] . $MaxInstallFee . $DetailVal[$keyMax][2];
                                    }
                                }
                                
                            break;
                            case "tv_equipment_fee":
                                if ( !is_array( $row[$a] ) ){
                                    $RowVal = $row[$a];
                                }else{
                                    foreach($row[$a] as $key => $IndVal){
                                        $DetailVal1[$key] = explode("^", $IndVal); 
                                    }
                                    $MinEquipFee = min(array_filter(array_column($DetailVal1, 1)));
                                    $keyMinEquipFee = array_search($MinEquipFee, array_column($DetailVal1, 1));

                                    $MaxEquipFee = max(array_filter(array_column($DetailVal1, 1)));
                                    $keyMaxEquipFee = array_search($MaxEquipFee, array_column($DetailVal1, 1));
                                    
                                    if($MinEquipFee == $MaxEquipFee) {
                                    $RowVal = $DetailVal1[$keyMaxEquipFee][0] . $MaxEquipFee . $DetailVal1[$keyMaxEquipFee][2];
                                    } else if ($MinEquipFee == "") {
                                        $RowVal = $DetailVal1[$keyMaxEquipFee][0] . $MaxEquipFee . $DetailVal1[$keyMaxEquipFee][2];
                                    } else if ($MaxEquipFee == "") {
                                        $RowVal = $DetailVal1[$keyMinEquipFee][0] . $MinEquipFee . $DetailVal1[$keyMinEquipFee][2];
                                    } else {
                                        $RowVal = $DetailVal1[$keyMinEquipFee][0] . $MinEquipFee . $DetailVal1[$keyMinEquipFee][2] . " – " . $DetailVal1[$keyMaxEquipFee][0] . $MaxEquipFee . $DetailVal1[$keyMaxEquipFee][2];
                                    }
                                }
                                
                            break;

    
                            case "equipment_fee":
                            case "contracts":
                            case "acsi_rating":
                            case "credit_check_required":
                            case "contract_buyouts":
                            case "early_termination_fee":
                            case "fixed_price_guarentee":
                                $RowVal = '';
                                $RowVal = $row[$a];
                            break;
                            default:
                                echo $row;
                        }
                        echo '  <div class="data-title-container">' . $Tbl_Columns[$Ind] . '</div>';
                        echo '  <div class="data-value-container">';
                        echo $RowVal;
                        echo '  </div>';
                    }
                    
                    echo '</div>';
                }                
                $Ind++;
            }
            echo '</div>';
        }
        $source = $CompareProvidersHs['source'];
        echo '<figcaption class="figcaption-source">'.$source.'</figcaption>';
        ?>
</div>