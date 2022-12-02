<?php
//NAME
$Detail_Table[$Ind]['provider']['name'] = get_the_title($Provider);
// LOGO
$Detail_Table[$Ind]['provider']['logo'] = get_field( 'logo', $Provider );
// WEBSITE URL
$Detail_Table[$Ind]['provider']['URL'] = get_post_permalink( $Provider );

if ( $SplitOut != "" ){
    $Detail_Table[$Ind]['split_out'] = $SplitOut;
}else{
    $Detail_Table[$Ind]['split_out'] = 0;
}
for ( $b = 0; $b <= (count($Tbl_ColumnsVal)-1); $b++ ){

    if ( strtolower($Tbl_ColumnsVal[$b]) == "connection_types" ){

        $ConnectionArr = $Internet[$Tbl_ColumnsVal[$b]];

        if ( in_array( $ProviderFilter, $ConntArr ) ){
            $inna = 0;
            foreach ($Internet[$Tbl_ColumnsVal[$b]] as $ConTyp ){
                if ( strtolower($ConTyp) != strtolower($ProviderFilter) ){
                    unset( $Internet[$Tbl_ColumnsVal[$b]][$inna] );
                }
                $inna++;
            }
            $reindexed_array = array_values($Internet[$Tbl_ColumnsVal[$b]]);

            if (is_array($reindexed_array) && !empty($reindexed_array)){
                $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $reindexed_array;
            } else {
                $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = 'N/A';
            }
        }else{
            if ( $FilterResult == 0 ){
                if ($Internet[$Tbl_ColumnsVal[$b]] != ''){
                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $Internet[$Tbl_ColumnsVal[$b]];
                } else {
                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = 'N/A';
                }
            }
        }

    }else {
        $ProviderDetailsArr = array( "contracts", "acsi_rating", "fixed_price_guarentee", "credit_check_required", "contract_buyouts", "early_termination_fee");
        $Internet2 = get_field_object('internet', $Provider );
        if ($SplitOut == 0){
            if( in_array( strtolower($Tbl_ColumnsVal[$b]), $ProviderDetailsArr) ){
                $data = get_field($Tbl_ColumnsVal[$b], $Provider);
                if ($data != ''){
                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $data;   
                } else {
                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = 'N/A';
                }                     
            } elseif( in_array( strtolower($Tbl_ColumnsVal[$b]), array("free_wifi_hotspots")) ){
                if($Internet[$Tbl_ColumnsVal[$b]] == 1){
                $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = "Yes";
                } else{
                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = "No";
                }
            } else{
                if( strtolower($Tbl_ColumnsVal[$b]) == "starting_price" ){            

                    
                    $MinValCol = "min_" . $Tbl_ColumnsVal[$b];
                    $MaxValCol = "max_" . $Tbl_ColumnsVal[$b];
                    $MinVal = $Internet['details'][$MinValCol];
                    $MaxVal = $Internet['details'][$MaxValCol];
                    
                    if ( $Internet['details']['show_asterisk'] == 1 ){
                        $ShowAsterisk0 = '*';
                    }else{
                        $ShowAsterisk0 = '';
                    }
                    if ($MinVal == $MaxVal && $MinVal == ''){
                        $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = 'N/A';
                    } else {
                        if ($MinVal == $MaxVal ){
                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MaxVal . $ShowAsterisk0 ;  
                        } else if ($MinVal == ""){
                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MaxVal . $ShowAsterisk0;
                        } else if ($MaxVal == ""){
                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MinVal . $ShowAsterisk0;
                        } else {
                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MinVal . " – " . $MaxVal . $ShowAsterisk0;
                        }
                    }    
                    
                }else if( strtolower($Tbl_ColumnsVal[$b]) == "installation_fee" ){
                    $Detail_Table[$Ind]['install_fee']['before_min_install_fee'] = $Internet['install_fee']['before_min_install_fee'];
                    $Detail_Table[$Ind]['install_fee']['after_min_install_fee'] = $Internet['install_fee']['after_min_install_fee'];

                    $Detail_Table[$Ind]['install_fee']['before_max_install_fee'] = $Internet['install_fee']['before_max_install_fee'];
                    $Detail_Table[$Ind]['install_fee']['after_max_install_fee'] = $Internet['install_fee']['after_max_install_fee'];
                    
                    //self_install
                    $MinVal = $Internet['install_fee']['self_install']['min_fee'];
                    $MaxVal = $Internet['install_fee']['self_install']['max_fee'];
                    if ($MinVal == $MaxVal && $MinVal == ''){
                        $Detail_Table[$Ind]['install_fee']['self_install'] = '';
                    } else {
                        $Detail_Table[$Ind]['install_fee']['self_install'] = $MinVal . " – " . $MaxVal;
                    }
                    
                    //pro_install
                    $MinVal = $Internet['install_fee']['pro_install']['min_fee'];
                    $MaxVal = $Internet['install_fee']['pro_install']['max_fee'];
                    if ($MinVal == $MaxVal && $MinVal == ''){
                        $Detail_Table[$Ind]['install_fee']['pro_install'] = '';
                    } else {
                        $Detail_Table[$Ind]['install_fee']['pro_install'] = $MinVal . " – " . $MaxVal;
                    }
                }else if( strtolower($Tbl_ColumnsVal[$b]) == "equipment_fee" ){
                    //internet_equipment_rental_fee
                    $MinVal = $Internet['internet_equipment_rental_fee']['internet_equipment_rental_fee_min'];
                    $MaxVal = $Internet['internet_equipment_rental_fee']['internet_equipment_rental_fee_max'];
                    if ($MinVal == $MaxVal && $MinVal == ''){
                        $Detail_Table[$Ind]['internet_equipment_rental_fee'][] = 'N/A';
                    } else {
                        if ($MinVal == $MaxVal ){
                            $Detail_Table[$Ind]['internet_equipment_rental_fee'][] = $MaxVal;  
                        } else if ($MinVal == ""){
                            $Detail_Table[$Ind]['internet_equipment_rental_fee'][] = $MaxVal;
                        } else if ($MaxVal == ""){
                            $Detail_Table[$Ind]['internet_equipment_rental_fee'][] = $MinVal;
                        } else {
                            $Detail_Table[$Ind]['internet_equipment_rental_fee'][] = $MinVal . " – " . $MaxVal;
                        }
                    }    
                } else if( strtolower($Tbl_ColumnsVal[$b]) == "free_wifi_hotspots" ){
                        if ($Internet["free_wifi_hotspots"]){
                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = 'Yes';
                        } else {
                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = 'No';
                        }
                }else{
                    $pattern = "/(max)/i";
                    if ( preg_match($pattern, $Tbl_ColumnsVal[$b] ) ){
                        $MaxPreA = '';
                        $MinValCol = str_replace("max", "min", $Tbl_ColumnsVal[$b] );
                        $MaxValCol = $Tbl_ColumnsVal[$b];
                        $MaxPreA = getPre($MaxValCol, $Internet2, false);
                        
                        $MinVal = $Internet['details'][$MinValCol];                                                
                        $MaxVal = $Internet['details'][$Tbl_ColumnsVal[$b]];
                        if ($MinVal == $MaxVal && $MinVal == ''){
                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = 'N/A';
                        } else {
                            if( $MinVal == $MaxVal ) {
                                $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MinVal . " " . $MaxPreA;
                            } else if($MinVal == "") {
                                $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MaxVal . " " . $MaxPreA;
                            
                            } else if($MaxVal == "") {
                                $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MinVal . " " . $MaxPreA;
                            }else {
                                $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MinVal . " – " . $MaxVal . " " . $MaxPreA;
                            }
                        }    
                    }else{
                        if ( strtolower($Internet['details'][$Tbl_ColumnsVal[$b]]) == "yes" ){
                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = "Yes";
                        }else{
                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = "No";
                        }
                    }                               
                }
            }
        }else{

            if( in_array( strtolower($Tbl_ColumnsVal[$b]), $ProviderDetailsArr) ){
                $data = get_field($Tbl_ColumnsVal[$b], $Provider);
                if ($data != ''){
                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $data; 
                } else {
                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = 'N/A'; 
                }    
            }elseif( in_array( strtolower($Tbl_ColumnsVal[$b]), array("free_wifi_hotspots")) ){ 
                if($Internet[$Tbl_ColumnsVal[$b]] == 1){
                $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = "Yes";
                } else{
                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = "No";
                }
            }else if( strtolower($Tbl_ColumnsVal[$b]) == "installation_fee" ){
                $Detail_Table[$Ind]['install_fee']['before_min_install_fee'] = $Internet['install_fee']['before_min_install_fee'];
                $Detail_Table[$Ind]['install_fee']['after_min_install_fee'] = $Internet['install_fee']['after_min_install_fee'];

                $Detail_Table[$Ind]['install_fee']['before_max_install_fee'] = $Internet['install_fee']['before_max_install_fee'];
                $Detail_Table[$Ind]['install_fee']['after_max_install_fee'] = $Internet['install_fee']['after_max_install_fee'];
                //self_install                                
                $MinVal = $Internet['install_fee']['self_install']['min_fee'];
                $MaxVal = $Internet['install_fee']['self_install']['max_fee'];
                if ($MinVal == $MaxVal && $MinVal == ''){
                    $Detail_Table[$Ind]['install_fee']['self_install'] = '';
                } else {
                    $Detail_Table[$Ind]['install_fee']['self_install'] = $MinVal . " – " . $MaxVal;
                }
                //pro_install
                $MinVal = $Internet['install_fee']['pro_install']['min_fee'];
                $MaxVal = $Internet['install_fee']['pro_install']['max_fee'];
                if ($MinVal == $MaxVal && $MinVal == ''){
                    $Detail_Table[$Ind]['install_fee']['pro_install'] = '';
                } else {
                    $Detail_Table[$Ind]['install_fee']['pro_install'] = $MinVal . " – " . $MaxVal;
                }
                
            }else if( strtolower($Tbl_ColumnsVal[$b]) == "equipment_fee" ){
                //internet_equipment_rental_fee
                $MinVal = $Internet['internet_equipment_rental_fee']['internet_equipment_rental_fee_min'];
                $MaxVal = $Internet['internet_equipment_rental_fee']['internet_equipment_rental_fee_max'];
                if ($MinVal == $MaxVal && $MinVal == ''){
                    $Detail_Table[$Ind]['internet_equipment_rental_fee'][] = 'N/A';
                } else {
                    if ($MinVal == $MaxVal ){
                        $Detail_Table[$Ind]['internet_equipment_rental_fee'][] = $MaxVal;  
                    } else if ($MinVal == ""){
                        $Detail_Table[$Ind]['internet_equipment_rental_fee'][] = $MaxVal;
                    } else if ($MaxVal == ""){
                        $Detail_Table[$Ind]['internet_equipment_rental_fee'][] = $MinVal;
                    } else {
                        $Detail_Table[$Ind]['internet_equipment_rental_fee'][] = $MinVal . ' – ' . $MaxVal;
                    }
                }    
            }else if( strtolower($Tbl_ColumnsVal[$b]) == "free_wifi_hotspots" ){
                if ($Internet["free_wifi_hotspots"]){
                        $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = 'Yes';
                    } else {
                        $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = 'No';
                    }
            }
            else{                            
                $ConectionTypes = $Internet['connection_types'];
                $Ind2 = 0;

                foreach ( $ConectionTypes as $ConectionType ){
                    $ConType = $Internet[$ConectionType . '_connection'];

                    switch (strtolower( $Tbl_ColumnsVal[$b] )) {
                        case "starting_price":
                            $Astk = $ConType[$ConectionType . '_show_asterisk'];                                        
                            if ( $Astk == 1 ){
                                if ( $DoFiltering == 1 ){
                                    if ( strtolower($ProviderFilter) == $ConectionType ){
                                        $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $ConType[$ConectionType . '_before_' . $Tbl_ColumnsVal[$b]] . "^" . $ConType[$ConectionType . '_' . $Tbl_ColumnsVal[$b]] . "^" . $ConType[$ConectionType . '_after_' . $Tbl_ColumnsVal[$b]] . "*";
                                    }
                                }else{
                                    if ( $FilterResult == 0 ){
                                        $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $ConType[$ConectionType . '_before_' . $Tbl_ColumnsVal[$b]] . "^" . $ConType[$ConectionType . '_' . $Tbl_ColumnsVal[$b]] . "^" . $ConType[$ConectionType . '_after_' . $Tbl_ColumnsVal[$b]] . "*";
                                    }
                                }
                            }else{
                                if ( $DoFiltering == 1 ){
                                    if ( strtolower($ProviderFilter) == $ConectionType ){
                                        $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $ConType[$ConectionType . '_before_' . $Tbl_ColumnsVal[$b]] . "^" . $ConType[$ConectionType . '_' . $Tbl_ColumnsVal[$b]] . "^" . $ConType[$ConectionType . '_after_' . $Tbl_ColumnsVal[$b]] . "";
                                    }
                                }else{
                                    if ( $FilterResult == 0 ){
                                        $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $ConType[$ConectionType . '_before_' . $Tbl_ColumnsVal[$b]] . "^" . $ConType[$ConectionType . '_' . $Tbl_ColumnsVal[$b]] . "^" . $ConType[$ConectionType . '_after_' . $Tbl_ColumnsVal[$b]] . "";
                                    }
                                }
                            }
                        break;

                        case "max_upload_speed":
                        case "max_download_speed":
                            if ( $DoFiltering == 1 ){
                                if ( strtolower($ProviderFilter) == $ConectionType ){
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $ConType[$ConectionType . '_before_' . $Tbl_ColumnsVal[$b]] . "^" . $ConType[$ConectionType . '_' . $Tbl_ColumnsVal[$b]] . "^" . $ConType[$ConectionType . '_after_' . $Tbl_ColumnsVal[$b]];
                                }
                            }else{
                                if ( $FilterResult == 0 ){
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $ConType[$ConectionType . '_before_' . $Tbl_ColumnsVal[$b]] . "^" . $ConType[$ConectionType . '_' . $Tbl_ColumnsVal[$b]] . "^" . $ConType[$ConectionType . '_after_' . $Tbl_ColumnsVal[$b]];
                                }
                            }
                        break;

                        case "symmetrical_speeds":
                        case "data_caps":
                            if ( $DoFiltering == 1 ){
                                if ( strtolower($ProviderFilter) == strtolower($ConectionType) ){                                    
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $ConType[$ConectionType . '_' . $Tbl_ColumnsVal[$b]];
                                }
                            }else{
                                if ( $FilterResult == 0 ){
                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $ConType[$ConectionType . '_' . $Tbl_ColumnsVal[$b]];
                                }
                            }
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
$Ind++;
?>