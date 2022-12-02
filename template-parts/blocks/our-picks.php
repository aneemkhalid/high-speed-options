<?php

/**
 * Our Picks Block.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

//echo "<h3>PROVIDER TYPT</h3><pre>"; print_r( $ProviderType ); echo "</pre><hr />";
$TableColumns = get_field('table_columns');
$Providers = get_field('providers');
$ButtonType = get_field('button_type');
$ButtonText = get_field('button_text');
$ProviderType = get_field('provider_type');
$disclaimer = get_field('disclaimer');

//echo 'GOT HERE';

$providerCount = count($Providers);

//Get Button Type
//echo "<pre>"; print_r( $TableColumns ); echo "</pre>";

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

    //$popup_internet_checked = 'checked';

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
$Tbl_Columns = array( "Our Picks" );
// User Added Columns
if ( strtolower( $ProviderType ) == "internet" ){
     $DataType = "internet_data_type";
}
foreach( $TableColumns as $Column ) {
    $Tbl_Columns[] = $Column['column_name'];
}
// Add Last Column for CTA Btn no text
$Tbl_Columns[] = '';
//echo "<pre><hr>"; print_r($Tbl_Columns); echo "<hr></pre>";

$Tbl_Columns_val = array();
foreach( $TableColumns as $Column ) {
    $Tbl_Columns_val[] = $Column[$DataType];
}
//echo "<pre> USER SELECTED COLUMNS : "; print_r( $Tbl_Columns_val ); echo "</pre><hr />";

// Get providers list
$Detail_Table_HS  = array();
$Ind = 0;
//echo "<pre> Providers : "; print_r( $Providers ); echo "</pre><hr />";

$PrdInd = 0;
foreach( $Providers as $Provider ) {
    
    $ShowIntenet = get_field( 'internet_check', $Provider );
    if ( $ShowIntenet == 1 ){

        $ProviderLogo = get_field( 'logo', $Provider );
        $Provider_Title = get_the_title( $Provider );

        $Detail_Table_HS['our_pick'][$Ind]['logo'] = $ProviderLogo; 
        $Detail_Table_HS['our_pick'][$Ind]['url'] = get_post_permalink( $Provider );
        $Detail_Table_HS['our_pick'][$Ind]['id'] = $Provider;
        $Detail_Table_HS['our_pick'][$Ind]['title'] = $Provider_Title;

        //Build datalayer product click for clicking on logos

        global $post;
        $picksPageSlug = $post->post_name;
        $picksPageSlug = ucwords(str_replace("-", " ", $picksPageSlug));

        $picksLayerList = 'Our Picks - ' . $picksPageSlug;

        $variantProvider = [
            'text' => $picksLayerList
        ];

        $picksLogoClick = dataLayerProdClick($Provider, $variantProvider, $PrdInd + 1, $picksPageSlug, $picksLayerList);

        $Detail_Table_HS['our_pick'][$Ind]['datalayer'] = $picksLogoClick;
   
        $Internet = get_field('internet', $Provider );
        //echo "<hr style='border: solid #000 3px;'><pre>INTERNET : "; print_r($Internet); echo "</pre><hr style='border: solid #000 3px;'>";

        $SplitOut = $Internet['split_out_connection'];
        for ( $b = 0; $b <= (count($Tbl_Columns_val)-1); $b++ ){
            $UserCol = strtolower($Tbl_Columns_val[$b]);
            //echo "<pre> TBL_COLS : "; print_r( $b . " " . $UserCol ); echo "</pre><hr />";

            if ( strtolower($UserCol) == "connection_types" ){
                //echo "<pre> INTERNET : "; print_r( $Internet[$Tbl_Columns_val[$b]] ); echo "</pre><hr />";
                $ConnectionArr = $Internet[$Tbl_Columns_val[$b]];

                //Uppercase string DSL
                if ( in_array("dsl", $ConnectionArr) ){
                    $ArrIndex = array_search("dsl", $ConnectionArr);
                    $Internet[$Tbl_Columns_val[$b]][$ArrIndex] = "DSL";
                }
                $Detail_Table_HS[$UserCol][] = $Internet[$Tbl_Columns_val[$b]];
            } else {
                $ProviderDetailsArr = array( "contracts", "acsi_rating", "fixed_price_guarentee", "credit_check_required", "contract_buyouts", "early_termination_fee" );
                $Internet2 = get_field_object('internet', $Provider );
                //print_r($Internet2);
                if ( $SplitOut == 0 || $SplitOut == "" ){
                    //echo "<pre> SPLT : "; print_r( $SplitOut ); echo "</pre><hr />";
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
                            if ( $Internet['details']['show_asterisk'] == 1 ){
                                $ShowAsterisk0 = '*';
                            }else{
                                $ShowAsterisk0 = '';
                            }
                            if ($MinVal == $MaxVal ){
                                $Detail_Table_HS[$UserCol][] = $MaxVal . $ShowAsterisk0 ;  
                            } else if ($MinVal == ""){
                                $Detail_Table_HS[$UserCol][] = $MaxVal . $ShowAsterisk0;
                            } else if ($MaxVal == ""){
                                $Detail_Table_HS[$UserCol][] = $MinVal . $ShowAsterisk0;
                            } else {
                                $Detail_Table_HS[$UserCol][] = $MinVal . " – " . $MaxVal . $ShowAsterisk0;
                            }
                            //echo "<pre>"; print_r( $MinVal . "-" . $MaxVal . "" . $ShowAsterisk0 ) ; echo "</pre>";
                            // $Detail_Table_HS[$UserCol][] = $MinVal . "-" . $MaxVal . "" . $ShowAsterisk0;
                        }else if( strtolower($UserCol) == "installation_fee" ){

                            $MinValSelf = ''; $MaxValSelf = ''; $MinValPro = ''; $MaxValPro = '';
                            $before_min_install_fee = $Internet['install_fee']['before_min_install_fee'];
                            $after_min_install_fee = $Internet['install_fee']['after_min_install_fee'];
            
                            $before_max_install_fee = $Internet['install_fee']['before_max_install_fee'];
                            $after_max_install_fee = $Internet['install_fee']['after_max_install_fee'];
    
                            $MinValSelf = $Internet['install_fee']['self_install']['min_fee'];
                            $MaxValSelf = $Internet['install_fee']['self_install']['max_fee'];
                            $MinValPro = $Internet['install_fee']['pro_install']['min_fee'];
                            $MaxValPro = $Internet['install_fee']['pro_install']['max_fee'];
                            $InstallFee = $MinValSelf . " – " . $MaxValSelf . " – " . $MinValPro . " – " . $MaxValPro;
                            $InstallFeeArray = explode(" – ", $InstallFee);
                            $InstallFeeMin = min(array_filter($InstallFeeArray));
                            $InstallFeeMax = max(array_filter($InstallFeeArray));
                            if($InstallFeeMin == $InstallFeeMax){
                                $InstallFeeRaw = $before_max_install_fee . $InstallFeeMax . $after_max_install_fee;
                            } else {
                            $InstallFeeRaw = $before_min_install_fee . $InstallFeeMin . $after_min_install_fee . ' – ' . $before_max_install_fee . $InstallFeeMax . $after_max_install_fee;
                            }
                            $Detail_Table_HS['installation_fee'][] = $InstallFeeRaw;

                        }else if( strtolower( $UserCol ) == "equipment_fee" ){
                            //internet_equipment_rental_fee
                            $MinVal = $Internet['internet_equipment_rental_fee']['internet_equipment_rental_fee_min'];
                            $MaxVal = $Internet['internet_equipment_rental_fee']['internet_equipment_rental_fee_max'];
                            if ($MinVal == $MaxVal ){
                                $Detail_Table_HS[$UserCol][] = $MaxVal;
                            } else if ($MinVal == ""){
                                $Detail_Table_HS[$UserCol][] = $MaxVal;
                            } else if ($MaxVal == ""){
                                $Detail_Table_HS[$UserCol][] = $MinVal;
                            } else {
                                $Detail_Table_HS[$UserCol][] = $MinVal . ' – ' . $MaxVal;
                            }

                        }else if( strtolower($UserCol) == "custom" ){

                            $value = $TableColumns[$b]['custom_values'][$PrdInd]['value'];
                            $Detail_Table_HS[ $UserCol ][$PrdInd] = $value;
                            
                        }else{
                            $pattern = "/(max)/i";
                            if ( preg_match($pattern, $UserCol) ){
                                $MaxPreA = '';
                                $MinValCol = str_replace("max", "min", $UserCol );
                                $MaxValCol = $UserCol;
                                $MaxPreA = getPre($MaxValCol, $Internet2, false);
                                $MinVal = $Internet['details'][$MinValCol];
                                $MaxVal = $Internet['details'][ $UserCol ];
                                if( $MinVal == $MaxVal ) {
                                    $Detail_Table_HS[ $UserCol ][] = $MinVal;
                                } else if($MinVal == "") {
                                    $Detail_Table_HS[ $UserCol ][] = $MaxVal;
                                
                                } else if($MaxVal == "") {
                                    $Detail_Table_HS[ $UserCol ][] = $MinVal;
                                }else {
                                    $Detail_Table_HS[ $UserCol ][] = $MinVal . " – " . $MaxVal;
                                }
                            }else{

                                if ( $Internet['details'][ $UserCol ] == 1 || strtolower($Internet['details'][ $UserCol ]) == "yes" ){
                                    $Detail_Table_HS[ $UserCol ][] = "Yes";
                                }else{
                                    $Detail_Table_HS[ $UserCol ][] = "No";
                                }
                            }
                        }
                    }

                }else{

                    if( in_array( strtolower($UserCol), $ProviderDetailsArr) ){
                        $Detail_Table_HS[$UserCol][] = get_field($UserCol, $Provider);
                    }else if( strtolower($UserCol) == "installation_fee" ){

                        $MinValSelf = ''; $MaxValSelf = ''; $MinValPro = ''; $MaxValPro = '';
                        $before_min_install_fee = $Internet['install_fee']['before_min_install_fee'];
                        $after_min_install_fee = $Internet['install_fee']['after_min_install_fee'];
        
                        $before_max_install_fee = $Internet['install_fee']['before_max_install_fee'];
                        $after_max_install_fee = $Internet['install_fee']['after_max_install_fee'];

                        $MinValSelf = $Internet['install_fee']['self_install']['min_fee'];
                        $MaxValSelf = $Internet['install_fee']['self_install']['max_fee'];
                        $MinValPro = $Internet['install_fee']['pro_install']['min_fee'];
                        $MaxValPro = $Internet['install_fee']['pro_install']['max_fee'];
                        $InstallFee = $MinValSelf . " – " . $MaxValSelf . " – " . $MinValPro . " – " . $MaxValPro;
                        $InstallFeeArray = explode(" – ", $InstallFee);
                        $InstallFeeMin = min(array_filter($InstallFeeArray));
                        $InstallFeeMax = max(array_filter($InstallFeeArray));
                        if($InstallFeeMin == $InstallFeeMax){
                            $InstallFeeRaw = $before_max_install_fee . $InstallFeeMax . $after_max_install_fee;
                        } else {
                        $InstallFeeRaw = $before_min_install_fee . $InstallFeeMin . $after_min_install_fee . ' – ' . $before_max_install_fee . $InstallFeeMax . $after_max_install_fee;
                        }
                        $Detail_Table_HS['installation_fee'][] = $InstallFeeRaw;
                    }else if( strtolower($UserCol) == "equipment_fee" ){
                        //internet_equipment_rental_fee
                        $MinVal = $Internet['internet_equipment_rental_fee']['internet_equipment_rental_fee_min'];
                        $MaxVal = $Internet['internet_equipment_rental_fee']['internet_equipment_rental_fee_max'];
                        if ($MinVal == $MaxVal ){
                            $Detail_Table_HS[$UserCol][] = $MaxVal;  
                        } else if ($MinVal == ""){
                            $Detail_Table_HS[$UserCol][] = $MaxVal;
                        } else if ($MaxVal == ""){
                            $Detail_Table_HS[$UserCol][] = $MinVal;
                        } else {
                            $Detail_Table_HS[$UserCol][] = $MinVal . ' – ' . $MaxVal;
                        }

                    }else if( strtolower($UserCol) == "custom" ){

                        $value = $TableColumns[$b]['custom_values'][$PrdInd]['value'];
                        $Detail_Table_HS[ $UserCol ][$PrdInd] = $value; 
                    
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
                                
                                case "max_upload_speed":
                                case "max_download_speed":
                                        
                                    $DownSpeeds[] = $ConType[$ConectionType . '_before_' . $UserCol] . "^" . $ConType[$ConectionType . '_' . $UserCol] . "^" . $ConType[$ConectionType . '_after_' . $UserCol];
                                    foreach($DownSpeeds as $DownSpeed){
                                        $DownSpeedArrayRaw[] = explode("^", $DownSpeed);
                                    }
                                    if ( isset($PrdInd, $Detail_Table_HS[$UserCol]) ) {
                                        if ( $Detail_Table_HS[$UserCol][$PrdInd] != "" ){
                                            $Detail_Table_HS[$UserCol][$PrdInd] = $Detail_Table_HS[$UserCol][$PrdInd] . "|" . $DownSpeed;
                                        }else{
                                            $Detail_Table_HS[$UserCol][$PrdInd] = $DownSpeed;
                                        }
                                    }else{
                                        $Detail_Table_HS[$UserCol][$PrdInd] = $DownSpeed;
                                    }
                                break;
                                case "symmetrical_speeds":
                                case "data_caps":
                                    $radioButton = $ConType[$ConectionType . '_' . $UserCol];
                                    if ( isset($PrdInd, $Detail_Table_HS[$UserCol]) ) {
                                        if ( $Detail_Table_HS[$UserCol][$PrdInd] != "" ){
                                            $Detail_Table_HS[$UserCol][$PrdInd] = $Detail_Table_HS[$UserCol][$PrdInd] . "|" . $radioButton;
                                            }else{
                                                $Detail_Table_HS[$UserCol][$PrdInd] = $radioButton;
                                            }
                                        }else{
                                            $Detail_Table_HS[$UserCol][$PrdInd] = $radioButton;
                                        }
                                break;
                            }
                            
                        }                            
                    }
                }
            }
        }

        if ($ButtonType == 'link'){
            $target=$target2=$cta_text2=$cta_link2='';
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
                    
                        }elseif($type_of_partnership == 'digital_link'){
                            $cta_text = 'Order Online';
                            $cta_link = $camp['digital_tracking_link'];
                            $target='target="_blank"';
                        } else {

                            if ($camp['primary_conversion_method'] == 'call_center'){
                                $cta_text = '<span class="material-icons">call</span>'.$camp['call_center'];
                                $cta_text2 = '<p class="mt-2 mb-0 tel-link font-weight-bold">Order Online</p>';
                                $cta_link = 'tel:'.$camp['call_center'];
                                $cta_link2 = $camp['digital_tracking_link'];
                                $target2='target="_blank"';
                            } else {
                                $cta_text = 'Order Online';
                                $cta_text2 = '<p class="mt-2 mb-0"><span class="small-text">Call to order:</span><span class="tel-link font-weight-bold"> '.$camp['call_center'].'</span></p>';
                                $cta_link = $camp['digital_tracking_link'];
                                $cta_link2 = 'tel:'.$camp['call_center'];
                                $target='target="_blank"';
                            }
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
        $Detail_Table_HS['cta_button'][$Ind]['target'] = $target;
        $Detail_Table_HS['cta_button'][$Ind]['target2'] = $target2;
        $Detail_Table_HS['cta_button'][$Ind]['cta_text'] = $cta_text;
        $Detail_Table_HS['cta_button'][$Ind]['cta_text2'] = $cta_text2;
        $Detail_Table_HS['cta_button'][$Ind]['cta_link2'] = $cta_link2;
        $Ind++;
        $PrdInd++;
    }

    //Build datalayer product impressions from providers
    $providerPageVariant = [
        'text' => 'Our Picks - ' . $picksPageSlug 
    ];
    $provPageImp = dataLayerProductImpression($Provider, $picksPageSlug, $providerPageVariant, $picksPageSlug . ' List', $PrdInd );
    $picksProvImp .= $provPageImp;
}

//Do string transformations to get values in desired format

$tableFormatted = [];
if($Detail_Table_HS) {

    $provIndex = 0;
    foreach ( $Detail_Table_HS as $keys => $rows ){

        $typeIndex = 0;
        foreach( $rows as $row ){ 
            switch($keys) :
                
                case "our_pick":
                    $pick = '';
                    $pick .= '<div class="provider-logo-wrapper" id="provider-column-256">';
                    $pick .= '  <div class="provider-logo-container">';
                    $pick .= '  <a href="' . $row['url'] . '" class="provider-logo-link" onclick="' . $row['datalayer'] . '">';
                    $pick .= '    <img src="' . $row['logo'] . '" class="provider-logo" alt="'. $row['title'] .'" width="150" height="39">';
                    $pick .= '  </a>';
                    $pick .= '  </div>';
                    $pick .= '</div>';
                    $tableFormatted[$provIndex][$typeIndex] = $pick;
                break;

                case "connection_types":
                    $Connection_types = '';
                    foreach ($row as $ConectionTyp ){
                        if ( $Connection_types == "" ){
                            $Connection_types = ucfirst($ConectionTyp);
                        }else{
                            $Connection_types .= ", " . ucfirst($ConectionTyp);
                        }
                    }
                    $tableFormatted[$provIndex][$typeIndex] = $Connection_types;
                break;

                case "starting_price":
                    $pattern = "/(\|)/i";
                    $pattern2 = "/(\*)/i";
                    $ShowAst = ''; 
                    $finalPrice = '';
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
                            $finalPrice = $valPriceArray[$key_max][0] . $MaxVal . $valPriceArray[$key_max][2] . $ShowAst;
                        } else {
                            $finalPrice = $valPriceArray[$key_min][0] . $MinVal . $valPriceArray[$key_min][2] . " – " . $valPriceArray[$key_max][0] . $MaxVal . $valPriceArray[$key_max][2] . $ShowAst;
                        }
                    } else {
                        $StrPrice = explode("^", $row);
                        $finalPrice = $StrPrice[0] . $StrPrice[1] . $StrPrice[2];
                    }
                    $tableFormatted[$provIndex][$typeIndex] = $finalPrice;
                    break;

                case "max_upload_speed":
                case "max_download_speed":
                    $finalSpeed = '';
                    $pattern = "/(\|)/i";
                    if ( preg_match($pattern, $row) ){
                    $Speeds = explode("|", $row);
                    $Pind = 0;
                    foreach($Speeds as $Speed){
                        $SpeedArray = explode("^", $Speed);
                        $valSpeedArrayRaw[$Pind] = $SpeedArray;
                        $Pind++;
                    }
                    
                    $Min = min(array_filter(array_column($valSpeedArrayRaw, 1)));
                    $key_min = array_search($Min, array_column($valSpeedArrayRaw, 1));
                    
                    $Max = max(array_filter(array_column($valSpeedArrayRaw, 1)));
                    $key_max = array_search($Max, array_column($valSpeedArrayRaw, 1));
                    
                    if($Min == $Max){
                        $finalSpeed = $valSpeedArrayRaw[$key_max][0] . $Max . $valSpeedArrayRaw[$key_max][2];
                    } else {
                        $finalSpeed = $valSpeedArrayRaw[$key_min][0] . $Min . $valSpeedArrayRaw[$key_min][2] . " – " . $valSpeedArrayRaw[$key_max][0] . $Max . $valSpeedArrayRaw[$key_max][2];
                    }
                    } else {
                        $singleSpeed = explode("^", $row);
                        $finalSpeed = $singleSpeed[0] . $singleSpeed[1] . $singleSpeed[2];
                    }
                    $tableFormatted[$provIndex][$typeIndex] = $finalSpeed;
                    break;

                case "symmetrical_speeds":
                case "data_caps":
                    $sd = '';
                    $pattern = "/(\|)/i";
                    if ( preg_match($pattern, $row) ){
                        $YesNoArr = explode("|", $row);                                                
                        $YesCount  = 0;
                        foreach( $YesNoArr as $YesNo ){
                            if ( strtolower($YesNo) == "yes" ){
                                $YesCount++;
                            }
                        }
                        if ( $YesCount >= 1 ){
                            $sd = "Yes";
                        }else{
                            $sd = "No";
                        }                                                
                    }else{
                        $sd = $row;
                    }
                    $tableFormatted[$provIndex][$typeIndex] = $sd;
                    break;
                
                case "installation_fee":
                    $fee = '';
                    $tableFormatted[$provIndex][$typeIndex] = $row;
                    break;
                
                case "equipment_fee":
                case "contracts":
                case "acsi_rating":
                case "credit_check_required":
                case "contract_buyouts":
                case "early_termination_fee":
                case "fixed_price_guarentee":
                    $tableFormatted[$provIndex][$typeIndex] = $row;
                    break;

                case "cta_button":

                    $provId = $Detail_Table_HS['our_pick'][$typeIndex]['id'];
                    $cta_btn = $rows[$typeIndex];
                    
                    //$providersViewPlansClick = dataLayerViewPlansClick($provId, $variantProvider, $picksPageSlug );

                    if ($cta_btn['cta_text'] == 'Check Availability'){
                        //echo $typeIndex;                 
                        $dataCheckAvailOnClick = 'onclick="'. dataLayerCheckAvailabilityClick($provId, $ProviderType).'"';                                         
                    }
                    if (substr($cta_btn['cta_link'], 0, 3) == 'tel') {
                        $datalayer = dataLayerOutboundLinkClick( $provId, $picksPageSlug, $cta_btn['cta_link'] );
                        $dataCheckAvailOnClick = 'onclick="'. $datalayer . '"';  
                    }

                    if (str_contains($cta_btn['cta_link2'], 'tel:')) {
                        $datalayer2 = dataLayerOutboundLinkClick( $provId, $picksPageSlug, $cta_btn['cta_link2'] );
                        $dataCheckAvailOnClick2 = 'onclick="'. $datalayer2 . '"';  
                    }
                    //echo substr($cta_btn['cta_link'], 0, 3);

                    if($cta_btn['cta_text'] == 'View Plans') {
                        $variantProvider = [
                            'text' => 'View Plans',
                            'url' => $cta_link
                        ];
                        $datalayer = dataLayerOutboundLinkClick( $provId, $picksPageSlug, $cta_btn['cta_link'] );
                        $dataCheckAvailOnClick = 'onclick="'. $datalayer . '"';  

                    }

                    if( str_contains($cta_btn['cta_text2'], 'View Plans')) {
                        $datalayer2 = dataLayerOutboundLinkClick( $provId, $picksPageSlug, $cta_btn['cta_link2'] );
                        $dataCheckAvailOnClick2 = 'onclick="'. $datalayer2 . '"';  

                    }

                    $cta_btn = $rows[$typeIndex];
                    $tableFormatted[$provIndex][$typeIndex] = '<a href="' . $cta_btn['cta_link'] . '" class="cta-btn ' . $cta_btn['zip_popup_class'] . ' ' . $cta_btn['class'] . '" ' . $cta_btn['data_att'] . ' ' . $cta_btn['target'] . $dataCheckAvailOnClick .'>' . $cta_btn['cta_text'] . '</a>';
                    if ($cta_btn['cta_text2']){
                        $tableFormatted[$provIndex][$typeIndex] .= '<a href="' . $cta_btn['cta_link2'] . '" ' . $cta_btn['target2'] . $dataCheckAvailOnClick2 .'>' . $cta_btn['cta_text2'] . '</a>';
                    }
                    break;

                //case "custom":
                    // $item = $TableColumns['custom_values'][$typeIndex];
                    // $tableFormatted[$provIndex][$typeIndex] = $item;

                default:
                    $tableFormatted[$provIndex][$typeIndex] = $row;
                    //echo "<pre>"; print_r( $keys ); echo "</pre>";
                    //echo "<pre>"; print_r( $row ); echo "</pre>";
            

            endswitch;
            $typeIndex++;
        }
        $provIndex++;
    }
}

//Get number of items in $tableFormatted
$rowCount = count($tableFormatted)

?>

<?php
// echo "<pre>"; print_r( $TableColumns ); echo "</pre><hr />";
// echo "<pre>"; print_r( $Tbl_Columns_val ); echo "</pre><hr />";
//echo "<pre>"; print_r( $Detail_Table_HS ); echo "</pre><hr />";
//echo "<pre>"; print_r( $tableFormatted ); echo "</pre><hr />";
?>

<section class="our-picks-block">

    <div class="desktop-table providers-<?php echo $providerCount ?>">
        <div class="">
            
            <table class="table-striped order-column" style="width:100%">
                <tbody>
                    <?php if($tableFormatted && $Tbl_Columns) :
                            $colIndex = 0;
                            foreach ( $tableFormatted as $rows) : ?>
                            <tr>
                                <td>
                                    <div class="tbl-column"><?php echo $Tbl_Columns[$colIndex] ?></div>
                                </td>

                            <?php foreach($rows as $row) : ?>
                                <td><?php echo $row ?></td>
                            <?php endforeach; $colIndex++;?>
                            </tr>
                    <?php endforeach; endif; ?>

                </tbody>
            </table>
            
        </div>
    </div>

    <div class="tablet-table providers-<?php echo $providerCount ?>">
        <div class="">
            <?php if($providerCount == 4) : ?>

                            
                <table class="table-striped order-column table-1" style="width:100%">
                    <tbody>
                        <?php if($tableFormatted && $Tbl_Columns) :
                                $colIndex = 0;
                                foreach ( $tableFormatted as $rows) : ?>
                                <tr>
                                    <td>
                                        <div class="tbl-column"><?php echo $Tbl_Columns[$colIndex] ?></div>
                                    </td>

                                    <?php for($i = 0; $i < 2; $i++) : ?>
                                        <td><?php echo $tableFormatted[$colIndex][$i] ?></td>
                                    <?php endfor; ?>
                                </tr>
                        <?php $colIndex++; endforeach; endif; ?>
                    </tbody>
                </table>

                <table class="table-striped order-column table-2" style="width:100%">
                    <tbody>
                        <?php if($tableFormatted && $Tbl_Columns) :
                                $colIndex = 0;
                                foreach ( $tableFormatted as $rows) : ?>
                                <tr>
                                    <td>
                                        <div class="tbl-column"><?php echo $Tbl_Columns[$colIndex] ?></div>
                                    </td>

                                    <?php for($i = 2; $i < 4; $i++) : ?>
                                        <td><?php echo $tableFormatted[$colIndex][$i] ?></td>
                                    <?php endfor; ?>
                                </tr>
                        <?php $colIndex++; endforeach; endif; ?>
                    </tbody>
                </table>

            <?php elseif($providerCount == 5) : ?>

                <table class="table-striped order-column table-1" style="width:100%">
                    <tbody>
                        <?php if($tableFormatted && $Tbl_Columns) :
                                $colIndex = 0;
                                foreach ( $tableFormatted as $rows) : ?>
                                <tr>
                                    <td>
                                        <div class="tbl-column"><?php echo $Tbl_Columns[$colIndex] ?></div>
                                    </td>

                                    <?php for($i = 0; $i < 3; $i++) : ?>
                                        <td><?php echo $tableFormatted[$colIndex][$i] ?></td>
                                    <?php endfor; ?>
                                </tr>
                        <?php $colIndex++; endforeach; endif; ?>
                    </tbody>
                </table>

                <table class="table-striped order-column table-2" style="width:100%">
                    <tbody>
                        <?php if($tableFormatted && $Tbl_Columns) :
                                $colIndex = 0;
                                foreach ( $tableFormatted as $rows) : ?>
                                <tr>
                                    <td>
                                        <div class="tbl-column"><?php echo $Tbl_Columns[$colIndex] ?></div>
                                    </td>

                                    <?php for($i = 3; $i < 5; $i++) : ?>
                                        <td><?php echo $tableFormatted[$colIndex][$i] ?></td>
                                    <?php endfor; ?>
                                </tr>
                        <?php $colIndex++; endforeach; endif; ?>
                    </tbody>
                </table>

            <?php else : ?>

                <table class="table-striped order-column" style="width:100%">
                    <tbody>
                        <?php if($tableFormatted && $Tbl_Columns) :
                                $colIndex = 0;
                                foreach ( $tableFormatted as $rows) : ?>
                                <tr>
                                    <td>
                                        <div class="tbl-column"><?php echo $Tbl_Columns[$colIndex] ?></div>
                                    </td>

                                <?php foreach($rows as $row) : ?>
                                    <td><?php echo $row ?></td>
                                <?php endforeach; $colIndex++;?>
                                </tr>
                        <?php endforeach; endif; ?>

                    </tbody>
                </table>

            <?php endif; ?>
            
        </div>
    </div>

    <div class="mobile-table providers-<?php echo $providerCount ?>">
        <div class="mobile-container">
            <?php for($i = 0; $i < $providerCount; $i++) : ?>
                <table class="table-striped order-column" style="width:100%">
                    <tbody>
                    <?php if($tableFormatted && $Tbl_Columns) :
                        $colIndex = 0;
                        //print_r($tableFormatted[1]);
                        //$rowCount is the number of subarrays in $tableFormatted
                        for ( $f = 0; $f < $rowCount; $f++) : 
                            if($colIndex == $rowCount -1) : ?>
                                <tr>
                                    <td colspan="2">
                                        <div class="btn-container"><?php echo $tableFormatted[$colIndex][$i] ?></div>
                                    </td>
                                </tr>
                            <?php else : ?>
                            <tr>
                                <td>
                                    <div class="tbl-column"><?php echo $Tbl_Columns[$colIndex] ?></div>
                                </td>

                                <td><?php echo $tableFormatted[$colIndex][$i] ?></td>
                            </tr>
                            
                    <?php endif; $colIndex++; endfor; endif; ?>
                    </tbody>
                </table>
            <?php endfor; ?>
            
        </div>
    </div>

    <script>
    <?php 
      //dataLayer info    
     echo dataLayerProductImpressionWrapper($picksProvImp);
    ?>
    </script>
    
    <!-- Discliamer Content -->
    <div class="disclaim-container">

        <?php echo $disclaimer ?>

    </div>
</section>
