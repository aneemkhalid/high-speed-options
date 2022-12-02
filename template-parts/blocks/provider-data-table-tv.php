
<?php
//each array inside an array should have a header with header info and cell_data which is what is shown inside the table cell

    $Ind = 0;
    //dataLayer info
    $providerCounter = 0; 
    $providerCounterMobile = 0;        
    $providerIDArrayTableHolder = [];
            
    foreach( $Providers as $Provider ){
        
        array_push($providerIDArrayTableHolder, $Provider);
        
        $tv_check = get_field('tv_check', $Provider );

        if ( $tv_check == "1" ){
               $headerCounter = 0;
             $Detail_Table[$Ind]['provider']['header'] = $Tbl_Columns[$headerCounter++];
            //NAME
            $Detail_Table[$Ind]['provider']['name'] = get_the_title($Provider);
            // LOGO
            $Detail_Table[$Ind]['provider']['logo'] = get_field( 'logo', $Provider );
            // WEBSITE URL
            $Detail_Table[$Ind]['provider']['URL'] = get_field( 'brands_website_url', $Provider );
            // CONNECTION TYPES
            $Internet = get_field('tv', $Provider );

            
            //dataLayer info
       
              $variantProvider = [
                        'text' => 'Compare Providers Table Desktop'
                ]; 

            $providerSlug = get_post_field( 'post_name', get_post() );


            $providersListOutboundClick = dataLayerOutboundLinkClick($providerIDArrayTableHolder[$providerCounter], $providerSlug, $variantProvider['text']);
            $providerCounter++;
          
            if ( $TableStyle == "minimal-table" ){
               
                $Detail_Table[$Ind]['provider']['cell_data'] = '<a href="' . $Detail_Table[$Ind]['provider']['URL'] . '" onclick="'.$providersListOutboundClick.'">' . $Detail_Table[$Ind]['provider']['name'] . '</a>';
            }else{
                $Detail_Table[$Ind]['provider']['cell_data'] = '<a href="' . $Detail_Table[$Ind]['provider']['URL'] . '" onclick="'.$providersListOutboundClick.'"><img src="' . $Detail_Table[$Ind]['provider']['logo'] . '" alt="' . $Detail['name'] . '" ></a>';
            }
                           

            $SplitOut = $Internet['split_out_connection'];
            if ( $SplitOut != "" ){
                $Detail_Table[$Ind]['split_out'] = $SplitOut;
            }else{
                $Detail_Table[$Ind]['split_out'] = 0;
            }
            if($Tbl_ColumnsVal) {
                for ( $b = 0; $b <= (count($Tbl_ColumnsVal)-1); $b++ ){
                    if ( strtolower($Tbl_ColumnsVal[$b]) == "connection_types" ){
                         $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['header'] = $Tbl_Columns[$headerCounter++];
                        $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $Internet[$Tbl_ColumnsVal[$b]];
                    }else {
                        $ProviderDetailsArr = array( "contracts", "acsi_rating", "fixed_price_guarentee", "credit_check_required", "contract_buyouts", "early_termination_fee" );
                        $Internet2 = get_field_object('tv', $Provider );
                           //HEADER
                                $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['header'] = $Tbl_Columns[$headerCounter++];      
                        if ($SplitOut == 0){
                        
                            if( in_array( strtolower($Tbl_ColumnsVal[$b]), $ProviderDetailsArr) ){
                                $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['cell_data'] = get_field($Tbl_ColumnsVal[$b], $Provider);                        
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
                    
                                     $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['cell_data'] = minMaxTable($MinVal, $MaxVal) . $ShowAsterisk0;
                     
                               
                                }elseif( strtolower($Tbl_ColumnsVal[$b]) == "tv_installation_fee" ){
                                    //Installation Fee                            
                                    $MinVal = $Internet['details']['tv_install_fee']['tv_installation_fee_min'];
                                    $MaxVal = $Internet['details']['tv_install_fee']['tv_installation_fee_max'];
                                    
                         
                                     $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['cell_data'] = minMaxTable($MinVal, $MaxVal);
                   
                                }else if( strtolower($Tbl_ColumnsVal[$b]) == "tv_equipment_fee" ){
                                    //internet_equipment_rental_fee
                                    $MinVal = $Internet['details']['tv_equipment_rental_fee']['tv_equipment_rental_fee_min'];
                                    $MaxVal = $Internet['details']['tv_equipment_rental_fee']['tv_equipment_rental_fee_max'];
                    
                                     $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['cell_data'] = minMaxTable($MinVal, $MaxVal);
            
                                }else if( strtolower($Tbl_ColumnsVal[$b]) == "dvr_recordings" ){
                                    $MinVal = $Internet['details']['min_dvr_recordings'];
                                    $MaxVal = $Internet['details']['max_dvr_recordings'];
                                
                                     $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['cell_data'] = minMaxTable($MinVal, $MaxVal);
           
                                }else{
                                    // channel count
                                    $MinPreA = ''; $MaxPreA = '';
                                    $ColVal = $Tbl_ColumnsVal[$b];
                                    $ColVal = str_replace("minimum", "", $ColVal);
                                    $MinValCol = "min" . $ColVal;
                                    $MaxValCol = "max" . $ColVal;
                                    $MinVal = $Internet['details'][$MinValCol];
                                    $MaxVal = $Internet['details'][$MaxValCol];
                
                                  
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['cell_data'] = minMaxTable($MinVal, $MaxVal);
                                }
                            }

                        }else{
                            //split true
                            if( in_array( strtolower($Tbl_ColumnsVal[$b]), $ProviderDetailsArr) ){
                                $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['cell_data'] = get_field($Tbl_ColumnsVal[$b], $Provider);
                            
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
                                        $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['cell_data'] = $tvEquipmentFee;
                                        break;

                                    }
                                }                            
                            }
                            //go through the split info
                            foreach( $Detail_Table[$Ind] as $ArInd => $Detail ){
                            
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
                                                 $Detail_Table[$Ind][$ArInd]['cell_data'] = $CellData;
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
                                                $Detail_Table[$Ind][$ArInd]['cell_data'] = $CellData;
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
                                               $Detail_Table[$Ind][$ArInd]['cell_data'] = $CellData;
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
                                                $Detail_Table[$Ind][$ArInd]['cell_data'] = $CellData;
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
                                               $Detail_Table[$Ind][$ArInd]['cell_data'] = $CellData;
                                            }
                            }
                            
                            
                        }
                    }
                }
            }
            
            //connection types
                  if (array_key_exists('connection_types',$Detail_Table[$Ind])) {
            
                  
                      $ConTypes = '';
                      foreach (  $Detail_Table[$Ind]['connection_types'][0] as $ConType => $key ){
                          if ( $ConTypes == "" ){
                              $ConTypes .= ucfirst($key);
                          }else{
                              $ConTypes .= "/" . ucfirst($key);
                          }
                      }

                     $Detail_Table[$Ind]['connection_types']['cell_data'] = $ConTypes;

                  
                  }

                      //Cta Button
 
                        $Detail_Table[$Ind]['cta']['header'] = $Tbl_Columns[$headerCounter++];
                        $Detail_Table[$Ind]['cta']['cell_data'] =  '<a href="' . $cta_link . '" class="cta-btn ' . $zip_popup_class . ' ' . $class . '" ' . $data_att .'>' . $cta_text . '</a>';
        }
        unset($Detail_Table[$Ind]['split_out']);
        $Ind++;
    }   

/*echo "<pre>";
echo print_r($Tbl_Columns) ; 
echo print_r($Detail_Table) ; 
echo "</pre>";*/
?>



<section id="comparetable" class="compare-providers-wrap">
    <?php if(!empty($Heading)) echo '<h2>'.$Heading.'</h2>'; ?>
    <div class="compare-providers-table <?php echo $TableStyle ?>">
       
       <?php echo buildDesktopTable("myTable", "compare-providers-table-inner desktop-table ", $Detail_Table);?>
        
<?php if ($TableStyle == 'minimal-table'){
echo buildMobileTable("myTable-mobile", "compare-providers-table-inner mobile-table ", $Detail_Table);   
} ?>
    </div>
    <div class="table_desc">
        <?php echo $TableDescription; ?>
    </div>
</section>