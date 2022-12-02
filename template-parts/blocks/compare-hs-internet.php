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
if ( strtolower( $ProviderType ) == "internet" ){
     $DataType = "internet_data_type";
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
    
    $ShowIntenet = get_field( 'internet_check', $Provider );
    if ( $ShowIntenet == 1 ){

        $Superlative = get_field( 'superlative', $Provider );
        $ProviderLogo = get_field( 'logo', $Provider );
        $Provider_Title = get_the_title( $Provider );

        $Detail_Table_HS['our_pick'][$Ind]['superlative'] = $Superlative;
        $Detail_Table_HS['our_pick'][$Ind]['logo'] = $ProviderLogo; 
        $Detail_Table_HS['our_pick'][$Ind]['url'] = get_post_permalink( $Provider );
        $Detail_Table_HS['our_pick'][$Ind]['title'] = $Provider_Title;


        $Detail_Table_HS['provider_name'][$Ind]['name'] = $Provider_Title;
        $Detail_Table_HS['provider_name'][$Ind]['url'] = get_post_permalink( $Provider );
   
        $Internet = get_field('internet', $Provider );
        $SplitOut = $Internet['split_out_connection'];
        for ( $b = 0; $b <= (count($Tbl_Columns_val)-1); $b++ ){
            $UserCol = strtolower($Tbl_Columns_val[$b]);

            if ( strtolower($UserCol) == "connection_types" ){
                $ConnectionArr = $Internet[$Tbl_Columns_val[$b]];
                if ( in_array("dsl", $ConnectionArr) ){
                    $ArrIndex = array_search("dsl", $ConnectionArr);
                    $Internet[$Tbl_Columns_val[$b]][$ArrIndex] = "DSL";
                }
                $Detail_Table_HS[$UserCol][] = $Internet[$Tbl_Columns_val[$b]];
            }else{
                $ProviderDetailsArr = array( "contracts", "acsi_rating", "fixed_price_guarentee", "credit_check_required", "contract_buyouts", "early_termination_fee" );
                $Internet2 = get_field_object('internet', $Provider );

                if ( $SplitOut == 0 || $SplitOut == "" ){
                    if( in_array( strtolower($UserCol), $ProviderDetailsArr) ){                        
                        $Detail_Table_HS[$UserCol][] = get_field( $UserCol, $Provider );
                    }elseif( in_array( strtolower($UserCol), array("free_wifi_hotspots")) ){
                        if($Internet[$Tbl_Columns_val[$b]] == 1){
                            $Detail_Table_HS[$UserCol][] = "Yes";
                        } else{
                            $Detail_Table_HS[$UserCol][] = "No";
                        }
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
                    }elseif( in_array( strtolower($UserCol), array("free_wifi_hotspots")) ){
                        if($Internet[$Tbl_Columns_val[$b]] == 1){
                            $Detail_Table_HS[$UserCol][] = "Yes";
                        } else{
                            $Detail_Table_HS[$UserCol][] = "No";
                        }
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
                    }else{
                        $ConectionTypes = $Internet['connection_types'];
                        $Ind2 = 0;

                        foreach ( $ConectionTypes as $ConectionType ){
                            $ConectionType = strtolower($ConectionType);
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
        $Ind++;
        $PrdInd++;
    }   
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

                                        case "max_upload_speed":
                                        case "max_download_speed":
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
                                            echo $valSpeedArrayRaw[$key_max][0] . $Max . $valSpeedArrayRaw[$key_max][2];
                                            } else {
                                            echo $valSpeedArrayRaw[$key_min][0] . $Min . $valSpeedArrayRaw[$key_min][2] . " – " . $valSpeedArrayRaw[$key_max][0] . $Max . $valSpeedArrayRaw[$key_max][2];
                                            }
                                            } else {
                                                $singleSpeed = explode("^", $row);
                                                echo $singleSpeed[0] . $singleSpeed[1] . $singleSpeed[2];
                                            }
                                        break;

                                        case "symmetrical_speeds":
                                        case "data_caps":

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
                                                    echo "Yes";
                                                }else{
                                                    echo "No";
                                                }                                                
                                            }else{
                                                echo $row;
                                            }
                                        break;

                                        case "installation_fee":                                            
                                            echo $row;
                                        break;

                                        case "equipment_fee":
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
        for ( $a = 0; $a<= ( count( $Detail_Table_HS["our_pick"] ) - 1); $a++){
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
                                        $Connection_types .= ", " . ucfirst($ConectionTyp);
                                    }
                                }
                                $RowVal = $Connection_types;                                
                            break;
                                                        
                            case "starting_price":
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
    
                            case "max_upload_speed":
                            case "max_download_speed":
                                $pattern = "/(\|)/i";

                                if ( preg_match($pattern, $row[$a]) ){
                                    $Speeds = explode("|", $row[$a]);
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
                                        $RowVal = $valSpeedArrayRaw[$key_max][0] . $Max . $valSpeedArrayRaw[$key_max][2];
                                    } else {
                                        $RowVal = $valSpeedArrayRaw[$key_min][0] . $Min . $valSpeedArrayRaw[$key_min][2] . " – " . $valSpeedArrayRaw[$key_max][0] . $Max . $valSpeedArrayRaw[$key_max][2];
                                    }
                                } else {
                                    $singleSpeed = explode("^", $row[$a]);
                                    $RowVal = $singleSpeed[0] . $singleSpeed[1] . $singleSpeed[2];
                                }
                            break;
                            
                            case "symmetrical_speeds":
                            case "data_caps":
                                $RowVal = '';
                                
                                $pattern = "/(\|)/i";
                                if ( preg_match($pattern, $row[$a]) ){
                                    $YesNoArr = explode("|", $row[$a]);                                                
                                    $YesCount  = 0;
                                    foreach( $YesNoArr as $YesNo ){
                                        if ( strtolower($YesNo) == "yes" ){
                                            $YesCount++;
                                        }
                                    }
                                    if ( $YesCount >= 1 ){
                                        $RowVal = "Yes";
                                    }else{
                                        $RowVal = "No";
                                    }                                                
                                }else{
                                    $RowVal = $row[$a];
                                }
                            break;
    
                            case "installation_fee":
                                $RowVal =  $row[$a];
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
                              
                                $RowVal = $row[$a];
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