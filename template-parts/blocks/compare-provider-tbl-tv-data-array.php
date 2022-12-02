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