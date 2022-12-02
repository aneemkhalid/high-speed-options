<section id="comparetable" class="compare-providers-wrap">
    <?php if(!empty($Heading)) echo '<h2>'.$Heading.'</h2>'; ?>
    <?php if(!empty($body_text)) echo '<p class="mb-4">'.str_replace(['<p>', '</p>'], '', $body_text).'</p>'; ?>    <div class="compare-providers-table <?php echo $TableStyle ?>">
        <table id="myTable" class="compare-providers-table-inner desktop-table">
            <thead>

                <tr><?php for( $a = 0; $a<=(count($Tbl_Columns)-1);$a++){
                    if ( $a > 0 && $a < (count($Tbl_Columns)-1) ){
                        $Click = ' onclick="sortTable(' . ($a-1) . ');" ';
                    }else{
                        $Click = '';
                    }

                    if ( $a == (count($Tbl_Columns)-1) ){
                        if ( $CTAButton != "" ){
                            echo '<th '. $Click . '>' . $Tbl_Columns[$a] . '</th>';
                        }
                    }else{
                        echo '<th '. $Click . '>' . $Tbl_Columns[$a] . '</th>';
                    }

                } ?>                
                </tr>
            </thead>
<?php
    $Ind = 0;
    //dataLayer info
    $providerCounter = 0; 
    $providerCounterMobile = 0;        
    $providerIDArrayTableHolder = [];        
            
    foreach( $Providers as $Provider ){
        
        array_push($providerIDArrayTableHolder, $Provider);
        
        $tv_check = get_field('tv_check', $Provider );

        if ( $tv_check == "1" ){
            //NAME
            $Detail_Table[$Ind]['provider']['name'] = get_the_title($Provider);
            // LOGO
            $Detail_Table[$Ind]['provider']['logo'] = get_field( 'logo', $Provider );
            // WEBSITE URL
            $Detail_Table[$Ind]['provider']['URL'] = get_field( 'brands_website_url', $Provider );
            // CONNECTION TYPES
            $Internet = get_field('tv', $Provider );

            $SplitOut = $Internet['split_out_connection'];
            if ( $SplitOut != "" ){
                $Detail_Table[$Ind]['split_out'] = $SplitOut;
            }else{
                $Detail_Table[$Ind]['split_out'] = 0;
            }

            for ( $b = 0; $b <= (count($Tbl_ColumnsVal)-1); $b++ ){
                if ( strtolower($Tbl_ColumnsVal[$b]) == "connection_types" ){
                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $Internet[$Tbl_ColumnsVal[$b]];
                }else {
                    $ProviderDetailsArr = array( "contracts", "acsi_rating", "fixed_price_guarentee", "credit_check_required", "contract_buyouts", "early_termination_fee" );
                    $Internet2 = get_field_object('tv', $Provider );
                    if ($SplitOut == 0){
                      
                        if( in_array( strtolower($Tbl_ColumnsVal[$b]), $ProviderDetailsArr) ){
                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = get_field($Tbl_ColumnsVal[$b], $Provider);                        
                        }else{
                            if( strtolower($Tbl_ColumnsVal[$b]) == "starting_price" ){
                                $MinPreA = ''; $MaxPreA = '';
                                $MinValCol = "min_" . $Tbl_ColumnsVal[$b];
                                $MaxValCol = "max_" . $Tbl_ColumnsVal[$b];
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
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MaxVal . $ShowAsterisk0 ;  
                                } else if ($MinVal == ""){
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MaxVal . $ShowAsterisk0;
                                } else if ($MaxVal == ""){
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MinVal . $ShowAsterisk0;
                                } else {
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MinVal . " – " . $MaxVal . $ShowAsterisk0;
                                }
                            }elseif( strtolower($Tbl_ColumnsVal[$b]) == "tv_installation_fee" ){
                                //Installation Fee                            
                                $MinVal = $Internet['details']['tv_install_fee']['tv_installation_fee_min'];
                                $MaxVal = $Internet['details']['tv_install_fee']['tv_installation_fee_max'];
                                if ($MinVal == $MaxVal ){
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MaxVal;
                                } else if ($MinVal == ""){
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MaxVal;
                                } else if ($MaxVal == ""){
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MinVal;
                                } else {
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MinVal . ' – ' . $MaxVal;
                                }
                                
                            }else if( strtolower($Tbl_ColumnsVal[$b]) == "tv_equipment_fee" ){
                                //internet_equipment_rental_fee
                                $MinVal = $Internet['details']['tv_equipment_rental_fee']['tv_equipment_rental_fee_min'];
                                $MaxVal = $Internet['details']['tv_equipment_rental_fee']['tv_equipment_rental_fee_max'];
                                if ($MinVal == $MaxVal ){
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MaxVal;  
                                } else if ($MinVal == ""){
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MaxVal;
                                } else if ($MaxVal == ""){
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MinVal;
                                } else {
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MinVal . ' – ' . $MaxVal;
                                }
                            }else if( strtolower($Tbl_ColumnsVal[$b]) == "dvr_recordings" ){
                                $MinVal = $Internet['details']['min_dvr_recordings'];
                                $MaxVal = $Internet['details']['max_dvr_recordings'];
                                if ($MinVal == $MaxVal ){
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MaxVal;  
                                } else if ($MinVal == ""){
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MaxVal;
                                } else if ($MaxVal == ""){
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MinVal;
                                } else {
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MinVal . ' – ' . $MaxVal;
                                }
                            }else{
                                // channel count
                                $MinPreA = ''; $MaxPreA = '';
                                $ColVal = $Tbl_ColumnsVal[$b];
                                $ColVal = str_replace("minimum", "", $ColVal);
                                $MinValCol = "min" . $ColVal;
                                $MaxValCol = "max" . $ColVal;
                                $MinVal = $Internet['details'][$MinValCol];
                                $MaxVal = $Internet['details'][$MaxValCol];
                                if ($MinVal == $MaxVal ){
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] =  $MaxVal;  
                                } else if ($MinVal == ""){
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] =  $MaxVal;
                                } else if ($MaxVal == ""){
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] =  $MinVal;
                                } else {
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] =  $MinVal . ' – ' . $MaxVal;
                                }                        
                            }
                        }

                    }else{
                        if( in_array( strtolower($Tbl_ColumnsVal[$b]), $ProviderDetailsArr) ){
                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = get_field($Tbl_ColumnsVal[$b], $Provider);
                        
                        }else{
                            $ConectionTypes = $Internet['connection_types'];
                            $Ind2 = 0;
                            foreach ( $ConectionTypes as $ConectionType ){
                                $ConType = $Internet[$ConectionType . '_connection'];
                                switch (strtolower( $Tbl_ColumnsVal[$b] )) {
                                    case "starting_price":
                                        $Astk = $ConType[$ConectionType . '_show_asterisk'];                                        
                                        if ( $Astk == 1 ){
                                        $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $ConType[$ConectionType . '_before_' . $Tbl_ColumnsVal[$b]] . "^" . $ConType[$ConectionType . '_' . $Tbl_ColumnsVal[$b]] . "^" . $ConType[$ConectionType . '_after_' . $Tbl_ColumnsVal[$b]] . "*";
                                        }else{
                                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $ConType[$ConectionType . '_before_' . $Tbl_ColumnsVal[$b]] . "^" . $ConType[$ConectionType . '_' . $Tbl_ColumnsVal[$b]] . "^" . $ConType[$ConectionType . '_after_' . $Tbl_ColumnsVal[$b]] . "";
                                        }
                                    break;

                                    case "minimum_channel_count":
                                    //satellite_max_channel_count
                                    $ChnCount = $ConType[$ConectionType . '_before_max_channel_count'] . "^" . $ConType[$ConectionType . '_max_channel_count'] . "^" . $ConType[$ConectionType . '_after_max_channel_count'];
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $ChnCount;
                                    break;

                                    case "dvr_recordings":
                                    //satellite_max_channel_count
                                    $dvr = $ConType[$ConectionType . '_before_dvr_recordings'] . "^" . $ConType[$ConectionType . '_dvr_recordings'] . "^" . $ConType[$ConectionType . '_after_dvr_recordings'];
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $dvr;
                                    break;

                                    case "tv_installation_fee":
                                    //satellite_max_channel_count
                                    $tvInstallFee = $ConType[$ConectionType . '_before_tv_installation_fee'] . "^" . $ConType[$ConectionType . '_tv_installation_fee'] . "^" . $ConType[$ConectionType . '_after_tv_installation_fee'];
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $tvInstallFee;
                                    break;

                                    case "tv_equipment_fee":
                                    //satellite_max_channel_count
                                    $tvEquipmentFee = $ConType[$ConectionType . '_before_tv_equipment_fee'] . "^" . $ConType[$ConectionType . '_tv_equipment_fee'] . "^" . $ConType[$ConectionType . '_after_tv_equipment_fee'];
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $tvEquipmentFee;
                                    break;

                                }
                            }                            
                        }
                    }
                }
            }
            //equipment_fee
            $cta_text = ''; $cta_link = ''; $target = '';
            $Partner = get_field('partner',$Provider);
            if($Partner){
                $BuyerId = get_field('buyer', $Provider);
                $Campaign = get_field( "campaign", $BuyerId );
                foreach($Campaign as $key => $camp) {                                        
                    $type_of_partnership = $camp['type_of_partnership'];
                    if($camp['campaign_name'] == $Provider){
                        if($type_of_partnership == 'call_center'){
                            $cta_text = '<span class="material-icons">call</span>'.$camp['call_center'];
                            $cta_link = 'tel:'.$camp['call_center'];
                        }else{
                            $cta_text = 'View Plans';
                            $cta_link = $camp['digital_tracking_link'];
                            $target = 'target="_blank"';
                        }
                        $Detail_Table[$Ind]['cta']['button_text'] = $cta_text;
                        $Detail_Table[$Ind]['cta']['button_link'] = $cta_link;
                        $Detail_Table[$Ind]['cta']['button_target'] = $target;
                    }
                }
            }else{
                $cta_text = 'View Plans';
                $cta_link = get_field('brands_website_url',$Provider);
                $target = 'target="_blank"';
                $Detail_Table[$Ind]['cta']['button_text'] = $cta_text;
                $Detail_Table[$Ind]['cta']['button_link'] = $cta_link;
                $Detail_Table[$Ind]['cta']['button_target'] = $target;
            }
        }
        $Ind++;
    }        
?>
            <tbody>
                <?php
                    foreach( $Detail_Table as $Detail_Col => $DetailArr ){
                        echo "<tr>";
                        $ConSlipt = $DetailArr['split_out'];
                        
                          //dataLayer info
       
                              $variantProvider = [
                                        'text' => 'Compare Providers Table Desktop'
                                ]; 

                            $providerSlug = get_post_field( 'post_name', get_post() );
                            

                            $providersListOutboundClick = dataLayerOutboundLinkClick($providerIDArrayTableHolder[$providerCounter], $providerSlug, $variantProvider['text']);
                             $providerCounter++;
                        
                        foreach( $DetailArr as $ArInd => $Detail ){
                            

                            switch( strtolower($ArInd) ){
                                case "provider":
                                   
                                    if ( $TableStyle == "minimal-table" ){
                                        echo '<td><a href="' . $Detail['URL'] . '" onclick="'.$providersListOutboundClick.'">' . $Detail['name'] . '</a></td>';
                                    }else{
                                        echo '<td><a href="' . $Detail['URL'] . '" onclick="'.$providersListOutboundClick.'"><img src="' . $Detail['logo'] . '" alt="' . $Detail['name'] . '" ></a></td>';
                                    }
                                break;

                                case "connection_types":
                                    $ConTypes = '';
                                    foreach ( $Detail[0] as $ConType => $key ){
                                        if ( $ConTypes == "" ){
                                            $ConTypes .= ucfirst($key);
                                        }else{
                                            $ConTypes .= "/" . ucfirst($key);
                                        }
                                    }
                                    echo '<td>' . $ConTypes . '</td>';
                                break;

                                default:
                                    if ( strtolower($ArInd) == "cta" ){
                                        if ( $CTAButton != "" ){
                                        echo '<td><a href="' . $Detail['button_link'] . '" class="cta_btn" ' . $Detail['button_link'] . '>' . $Detail['button_text'] . '</a></td>';
                                        }
                                    }else if ( $ArInd != "split_out" ){
                                        if ( $ConSlipt == 0 ){
                                            echo '<td>' . $Detail[0] . '</td>';
                                        }else{
                                            echo '<td>';
                                            if ( strtolower( $ArInd ) == "starting_price" ){
                                                $CellData = '';                                                
                                                $MinVal = ''; $MaxVal = '';
                                                $StPrice = array();
                                                $Askt = 0;
                                                foreach ( $Detail as $key => $IndvPrice ){

                                                    $IndvPriceRaw = explode("*", $IndvPrice);

                                                    $PriceArrayRaw[$key] = explode("^", $IndvPriceRaw[0]);

                                                    $StPrice[] = $PriceArrayRaw;
                                                    if ( count($IndvPriceRaw) > 1 ){
                                                        $Askt++;
                                                    }
                                                }
                                                
                                                $MinVal = min(array_filter(array_column($PriceArrayRaw, 1)));
                                                $key_min = array_search($MinVal, array_column($PriceArrayRaw, 1));

                                                $MaxVal = max(array_filter(array_column($PriceArrayRaw, 1)));
                                                $key_max = array_search($MaxVal, array_column($PriceArrayRaw, 1));

                                                if ( $Askt >= 1 ){
                                                    $ShowAsterisk = "*";
                                                }else{
                                                    $ShowAsterisk = "";
                                                }
                                                
                                                if ( $MinVal == $MaxVal ){ // BOTH ARE EQUAL
                                                    $CellData .= $PriceArrayRaw[$key_max][0] . $MaxVal . $PriceArrayRaw[$key_max][2] . $ShowAsterisk;
                                                }else{ // BOTH ARE DIFFERNT
                                                    $CellData .= $PriceArrayRaw[$key_min][0] . $MinVal . $PriceArrayRaw[$key_min][2] . " – " . $PriceArrayRaw[$key_max][0] . $MaxVal . $PriceArrayRaw[$key_max][2] . $ShowAsterisk;
                                                }
                                                echo $CellData;
                                            }else if ( strtolower( $ArInd ) == "tv_installation_fee"){
                                                foreach($Detail as $key => $IndVal){
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
                                            }else if ( strtolower( $ArInd ) == "tv_equipment_fee"){
                                                foreach($Detail as $key => $IndVal){
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
                                            }else if ( strtolower( $ArInd ) == "minimum_channel_count"){
                                                foreach($Detail as $key => $IndVal){
                                                    $DetailVal2[$key] = explode("^", $IndVal); 
                                                }
                                                $MinChannelCount = min(array_filter(array_column($DetailVal2, 1)));
                                                $keyMinChannelCount = array_search($MinChannelCount, array_column($DetailVal2, 1));

                                                $MaxChannelCount = max(array_filter(array_column($DetailVal2, 1)));
                                                $keyMaxChannelCount = array_search($MaxChannelCount, array_column($DetailVal2, 1));
                                                
                                                if($MinChannelCount == $MaxChannelCount) {
                                                $CellData = $DetailVal2[$keyMaxChannelCount][0] . $MaxChannelCount . $DetailVal2[$keyMaxChannelCount][2];
                                                } else if ($MinChannelCount == "") {
                                                    $CellData = $DetailVal2[$keyMaxChannelCount][0] . $MaxChannelCount . $DetailVal2[$keyMaxChannelCount][2];
                                                } else if ($MaxChannelCount == "") {
                                                    $CellData = $DetailVal2[$keyMinChannelCount][0] . $MinChannelCount . $DetailVal2[$keyMinChannelCount][2];
                                                } else {
                                                    $CellData = $DetailVal2[$keyMinChannelCount][0] . $MinChannelCount . $DetailVal2[$keyMinChannelCount][2] . " – " . $DetailVal2[$keyMaxChannelCount][0] . $MaxChannelCount . $DetailVal2[$keyMaxChannelCount][2];
                                                }
                                                echo $CellData;
                                            }else if ( strtolower( $ArInd ) == "dvr_recordings"){
                                                foreach($Detail as $key => $IndVal){
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
                                            }else{
                                                $CellData = '';
                                                $CellData = $Detail[0];
                                                echo $CellData;
                                            }
                                            echo ' </td>';
                                        }                                            
                                    }
                            }
                        }
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
<?php if ($TableStyle == 'minimal-table'){ ?>
<table class="compare-providers-table-inner mobile-table">
    <tbody>
        <?php
         
            for ( $d=0; $d<=( count($Detail_Table)-1 ); $d++){
                
                $ConSlipt = $Detail_Table[$d]['split_out'];
                $Indx = 0;
                foreach( $Tbl_Columns as $Tbl_Column ){
                    if ( $Indx == 0 ){
                        $Class = ' top-provider-row ';
                    }else{
                        $Class = '';
                    }
                   
                    if( $TblColVal == "" && $Indx != 0 ){
                        if ( $CTAButton != "" ){
                            echo '<tr class="' . $Class . '">';    
                        }
                    }else{
                        echo '<tr class="' . $Class . '">';
                    }

                    if ( $Indx == (count($Tbl_Columns)-1) ){
                        // Do Nothing
                    }else{
                        echo "  <th>" . $Tbl_Column . "</th>";
                    }

                    $TblColVal = strtolower($Tbl_ColumnsVal[$Indx-1]);
                    $CellStyle = '';
                    if ( $Indx == (count($Tbl_Columns)-1) ){
                        $CellStyle = ' class="text-center" colspan="2" ';
                    }
                    if( $TblColVal == "" && $Indx != 0 ){
                        if ( $CTAButton != "" ){
                            echo "  <td " . $CellStyle . ">";
                        }
                    }else{
                        echo "  <td " . $CellStyle . ">";
                    }
                    if ( $TblColVal == "" && $Indx == 0 ){
                        
                          //dataLayer info
       
                          $variantProviderMobile = [
                                    'text' => 'Compare Providers Table Mobile'
                            ]; 

                        $providerSlug = get_post_field( 'post_name', get_post() );


                        $providersListOutboundClickMobile = dataLayerOutboundLinkClick($providerIDArrayTableHolder[$providerCounterMobile], $providerSlug, $variantProviderMobile['text']);
                         $providerCounterMobile++;
                        
                        echo '<a href="' . $Detail_Table[$d]['provider']['URL'] . '" onclick="'.$providersListOutboundClickMobile.'">' . $Detail_Table[$d]['provider']['name'] . '</a>';
                    }else if( $TblColVal == "" && $Indx != 0 ){
                        if ( $CTAButton != "" ){
                            echo '<a href="' . $Detail_Table[$d]['cta']['button_link'] . '" class="cta_btn" ' . $Detail_Table[$d]['cta']['button_link'] . '>' . $Detail_Table[$d]['cta']['button_text'] . '</a>';
                        }
                    }

                    switch( $TblColVal ){

                        case "connection_types":
                            $ConTypes = '';
                            foreach ( $Detail_Table[$d]['connection_types'][0] as $ConType => $key ){
                                if ( $ConTypes == "" ){
                                    $ConTypes .= ucfirst($key);
                                }else{
                                    $ConTypes .= "/" . ucfirst($key);
                                }
                            }
                            echo $ConTypes;
                        break;

                        default:
                            $ArInd = $TblColVal;                           
                            $Detail = $Detail_Table[$d][$ArInd];

                            if ( $ArInd != "split_out" ){
                                if ( $ConSlipt == 0 ){
                                    echo $Detail[0];
                                }else{
                                    if ( $ArInd != "" ){
                                        $MinVal = min(array_filter($Detail));
                                        $MaxVal = max(array_filter($Detail));
                                        if ( strtolower($ArInd) == "starting_price" ) {
                                            $CellData = '';
                                            $StPrice1 = ''; $MinVal = ''; $MaxVal = ''; $ShowAsterisk = '';
                                            foreach ($Detail as $SingleDetail ){
                                                if ( $StPrice1 == "" ){
                                                    $StPrice1 = $SingleDetail;
                                                }else{
                                                    $StPrice1 .= " – " . $SingleDetail;
                                                }
                                            }                                                
                                            $pattern = "/(\*)/i";
                                            if  ( preg_match($pattern, $StPrice1) ){
                                                $ShowAsterisk = '*';
                                            }else{
                                                $ShowAsterisk = '';
                                            }                                                
                                            $StPrice1 = preg_replace($pattern, "", $StPrice1);
                                            $StPrice1Arr = explode(" – ",$StPrice1);
                                            foreach($StPrice1Arr as $key => $IndvPriceRaw){
                                                $PriceRaw[$key] = explode("^", $IndvPriceRaw);
                                            }
                                            
                                            $MinStVal = min(array_filter(array_column($PriceRaw, 1)));
                                            $keyStMin = array_search($MinStVal, array_column($PriceRaw, 1));

                                            $MaxStVal = max(array_filter(array_column($PriceRaw, 1)));
                                            $keyStMax = array_search($MaxStVal, array_column($PriceRaw, 1));
                                            if ( $MinStVal == $MaxStVal ){ // BOTH ARE EQUAL
                                                $CellData .= $PriceRaw[$keyStMax][0] . $MaxStVal . $PriceRaw[$keyStMax][2] . $ShowAsterisk;
                                            }else{ // BOTH ARE DIFFERNT
                                                $CellData .= $PriceRaw[$keyStMin][0] . $MinStVal . $PriceRaw[$keyStMin][2] . " – " . $PriceRaw[$keyStMax][0] . $MaxStVal . $PriceRaw[$keyStMax][2] . $ShowAsterisk;
                                            }                                                                                       
                                            echo $CellData;
                                        }else if ( strtolower( $ArInd ) == "minimum_channel_count"){
                                            foreach($Detail as $key => $IndVal){
                                                $DetailVal2[$key] = explode("^", $IndVal); 
                                            }
                                            $MinChannelCount = min(array_filter(array_column($DetailVal2, 1)));
                                            $keyMinChannelCount = array_search($MinChannelCount, array_column($DetailVal2, 1));

                                            $MaxChannelCount = max(array_filter(array_column($DetailVal2, 1)));
                                            $keyMaxChannelCount = array_search($MaxChannelCount, array_column($DetailVal2, 1));
                                            
                                            if($MinChannelCount == $MaxChannelCount) {
                                            $CellData = $DetailVal2[$keyMaxChannelCount][0] . $MaxChannelCount . $DetailVal2[$keyMaxChannelCount][2];
                                            } else if ($MinChannelCount == "") {
                                                $CellData = $DetailVal2[$keyMaxChannelCount][0] . $MaxChannelCount . $DetailVal2[$keyMaxChannelCount][2];
                                            } else if ($MaxChannelCount == "") {
                                                $CellData = $DetailVal2[$keyMinChannelCount][0] . $MinChannelCount . $DetailVal2[$keyMinChannelCount][2];
                                            } else {
                                                $CellData = $DetailVal2[$keyMinChannelCount][0] . $MinChannelCount . $DetailVal2[$keyMinChannelCount][2] . " – " . $DetailVal2[$keyMaxChannelCount][0] . $MaxChannelCount . $DetailVal2[$keyMaxChannelCount][2];
                                            }
                                            echo $CellData;
                                        }
                                        else if ( strtolower( $ArInd ) == "dvr_recordings"){
                                            foreach($Detail as $key => $IndVal){
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
                                        else if ( strtolower( $ArInd ) == "tv_installation_fee"){
                                            foreach($Detail as $key => $IndVal){
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
                                        else if ( strtolower( $ArInd ) == "tv_equipment_fee"){
                                            foreach($Detail as $key => $IndVal){
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
                                        }else{
                                            $CellData = '';
                                            $CellData = $Detail[0];
                                            echo $CellData;
                                        }
                                    }
                                }
                            }
                        }

                        if( $TblColVal == "" && $Indx != 0 ){
                            if ( $CTAButton != "" ){
                                echo '</td>';
                                echo '</tr>';
                            }
                        }else{
                            echo "  </td>";
                            echo "</tr>";
                        }
                    $Indx++;
                }
            }
        ?>
    </tbody>
</table>
<?php } ?>
    </div>
    <div class="table_desc">
        <?php echo $TableDescription; ?>
    </div>
</section>