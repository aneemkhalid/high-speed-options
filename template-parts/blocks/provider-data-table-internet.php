 <?php

//each array inside an array should have a header with header info and cell_data which is what is shown inside the table cell

                $Ind = 0;
               
                //dataLayer info
                $providerCounter = 0; 
                $providerIDCounter = 0;
                $providerIDCounterMobile = 0;
                $providerCounterMobile = 0;        
                $providerIDArrayTableHolder = [];   
                foreach( $Providers as $Provider ){
                    $internet_check = get_field('internet_check', $Provider );
                    
                    // CONNECTION TYPES        
                    $Internet = get_field('internet', $Provider );
                    
                    $SplitOut = $Internet['split_out_connection'];
                    $ConntArr = $Internet['connection_types'];

                
                    if ( $SplitOut == 1){
                        if ( in_array(strtolower($ProviderFilter), $ConntArr ) ){
                            $DoFiltering = 1;
                        }else{
                            $DoFiltering = 0;
                        }
                    }else{
                        $DoFiltering = 0;
                    }

                    
                    
                    //starts of creating the internet info array        
                    if ( $internet_check == "1" ){
                         $headerCounter = 0;
                        //HEADER
                        $Detail_Table[$Ind]['provider']['header'] = $Tbl_Columns[$headerCounter++];
                        //NAME
                        $Detail_Table[$Ind]['provider']['name'] = get_the_title($Provider);
                        // LOGO
                        $Detail_Table[$Ind]['provider']['logo'] = get_field( 'logo', $Provider );
                        // WEBSITE URL
                        $Detail_Table[$Ind]['provider']['URL'] = get_post_permalink( $Provider );
                        
                           //dataLayer info

                        $variantProvider = [
                                  'text' => 'Compare Providers Table Desktop'
                          ]; 

                      $providerSlug = get_post_field( 'post_name', get_post() );

                      $providerCounter++;
                        
                        
                        $checkBtnProviderID = url_to_postid($Detail['URL']);
                        $providersListProdClick = dataLayerProdClick($checkBtnProviderID, $variantProvider, $providerCounter, $providerSlug, $Heading);
                   
                        
                        
                        if ( $TableStyle == "minimal-table" ){
                           $Detail_Table[$Ind]['provider']['cell_data'] = '<a href="' . $Detail_Table[$Ind]['provider']['URL'] . '" onclick="'.$providersListProdClick.'">' . $Detail_Table[$Ind]['provider']['name'] . '</a>';
                        }else{
                          $Detail_Table[$Ind]['provider']['cell_data'] = '<a href="' . $Detail_Table[$Ind]['provider']['URL'] . '" onclick="'.$providersListProdClick.'"><img src="' .$Detail_Table[$Ind]['provider']['logo'] . '" alt="' . $Detail_Table[$Ind]['provider']['name'] . '"></a>';
                        }
                        
                  
                        
                       
                        if ( $SplitOut != "" ){
                            $Detail_Table[$Ind]['split_out'] = $SplitOut;
                        }else{
                            $Detail_Table[$Ind]['split_out'] = 0;
                        }
                        for ( $b = 0; $b <= (count($Tbl_ColumnsVal)-1); $b++ ){                      
                            
                            
                            
                            
                            
                            if ( strtolower($Tbl_ColumnsVal[$b]) == "connection_types" ){
                                 $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['header'] = $Tbl_Columns[$headerCounter++];
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


                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $reindexed_array;
                                }else{
                                    if ( $FilterResult == 0 ){
                                        $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $Internet[$Tbl_ColumnsVal[$b]];
                                    }
                                }

                            }else {
                                $ProviderDetailsArr = array( "contracts", "acsi_rating", "fixed_price_guarentee", "credit_check_required", "contract_buyouts", "early_termination_fee");
                                $Internet2 = get_field_object('internet', $Provider );
                                //HEADER
                                $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['header'] = $Tbl_Columns[$headerCounter++];                              
                        
                                    //split equal false
     
                                    if( in_array( strtolower($Tbl_ColumnsVal[$b]), $ProviderDetailsArr) ){
                                        $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = get_field($Tbl_ColumnsVal[$b], $Provider); 
                                        $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['cell_data'] = get_field($Tbl_ColumnsVal[$b], $Provider); 
                                    } elseif( in_array( strtolower($Tbl_ColumnsVal[$b]), array("free_wifi_hotspots")) ){
                                        if($Internet[$Tbl_ColumnsVal[$b]] == 1){
                                        $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['cell_data'] = "Yes";
                                        } else{
                                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['cell_data'] = "No";
                                        }
                                    }else{
                                      

                                        if( strtolower($Tbl_ColumnsVal[$b]) == "starting_price" ){            

                                             if ($SplitOut == 0){
                                                $MinValCol = "min_" . $Tbl_ColumnsVal[$b];
                                                $MaxValCol = "max_" . $Tbl_ColumnsVal[$b];
                                                $MinVal = $Internet['details'][$MinValCol];
                                                $MaxVal = $Internet['details'][$MaxValCol];

                                                if ( $Internet['details']['show_asterisk'] == 1 ){
                                                    $ShowAsterisk0 = '*';
                                                }else{
                                                    $ShowAsterisk0 = '';
                                                }
                                               $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['cell_data'] = minMaxTable($MinVal, $MaxVal) . $ShowAsterisk0;
                                             }
                                                

                                        }else if( strtolower($Tbl_ColumnsVal[$b]) == "installation_fee" ){
                                            $Detail_Table[$Ind]['installation_fee']['before_min_install_fee'] = $Internet['install_fee']['before_min_install_fee'];
                                            $Detail_Table[$Ind]['installation_fee']['after_min_install_fee'] = $Internet['install_fee']['after_min_install_fee'];

                                            $Detail_Table[$Ind]['installation_fee']['before_max_install_fee'] = $Internet['install_fee']['before_max_install_fee'];
                                            $Detail_Table[$Ind]['installation_fee']['after_max_install_fee'] = $Internet['install_fee']['after_max_install_fee'];
                                            
                                            //self_install
                                            $MinVal = $Internet['install_fee']['self_install']['min_fee'];
                                            $MaxVal = $Internet['install_fee']['self_install']['max_fee'];
                                         
                                            $Detail_Table[$Ind]['installation_fee']['self_install'] = minMaxTable($MinVal, $MaxVal);
                                            //pro_install
                                            $MinVal = $Internet['install_fee']['pro_install']['min_fee'];
                                            $MaxVal = $Internet['install_fee']['pro_install']['max_fee'];
                                        
                                             $Detail_Table[$Ind]['installation_fee']['pro_install'] = minMaxTable($MinVal, $MaxVal);
                                        }else if( strtolower($Tbl_ColumnsVal[$b]) == "equipment_fee" ){
                                            //internet_equipment_rental_fee
                                            $MinVal = $Internet['internet_equipment_rental_fee']['internet_equipment_rental_fee_min'];
                                            $MaxVal = $Internet['internet_equipment_rental_fee']['internet_equipment_rental_fee_max'];
                                    
                                            
                                            $Detail_Table[$Ind]['equipment_fee']['cell_data'] = minMaxTable($MinVal, $MaxVal);
                                        } else if( strtolower($Tbl_ColumnsVal[$b]) == "free_wifi_hotspots" ){
                                                if ($Internet["free_wifi_hotspots"]){
                                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['cell_data'] = 'Yes';
                                                } else {
                                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['cell_data'] = 'No';
                                                }
                                        }
                                    
                                        
                                        
                                      
                                        
                                        else{
                                             if ($SplitOut == 0){
                                              //if split out equal 0 start
                                                    $pattern = "/(max)/i";
                                                    if ( preg_match($pattern, $Tbl_ColumnsVal[$b] ) ){
                                                        $MaxPreA = '';
                                                        $MinValCol = str_replace("max", "min", $Tbl_ColumnsVal[$b] );
                                                        $MaxValCol = $Tbl_ColumnsVal[$b];
                                                        $MaxPreA = getPre($MaxValCol, $Internet2, false);

                                                        $MinVal = $Internet['details'][$MinValCol];                                                
                                                        $MaxVal = $Internet['details'][$Tbl_ColumnsVal[$b]];

                                                        if( $MinVal == $MaxVal ) {
                                                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MinVal . " " . $MaxPreA;
                                                        } else if($MinVal == "") {
                                                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MaxVal . " " . $MaxPreA;

                                                        } else if($MaxVal == "") {
                                                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MinVal . " " . $MaxPreA;
                                                        }else {
                                                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $MinVal . " – " . $MaxVal . " " . $MaxPreA;
                                                        }
                                                  
                                                    }else{
                                                        if ( strtolower($Internet['details'][$Tbl_ColumnsVal[$b]]) == "yes" ){
                                                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['cell_data'] = "Yes";
                                                        }else{
                                                            $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]]['cell_data'] = "No";
                                                        }
                                                    }      
                                                  //if split out equal 0 end
                                        }
                                            
                                            
                                            
                                            
                                            
                                        }
                                  
                                        
                                    }
                                        if ($SplitOut == 1){
                                    //split equal true start
                           
                                                            
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

                                                case "max_upload_speed":
                                                case "max_download_speed":

                                                    $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $ConType[$ConectionType . '_before_' . $Tbl_ColumnsVal[$b]] . "^" . $ConType[$ConectionType . '_' . $Tbl_ColumnsVal[$b]] . "^" . $ConType[$ConectionType . '_after_' . $Tbl_ColumnsVal[$b]];

                                                    
                                                break;

                                                case "symmetrical_speeds":
                                                case "data_caps":    
                                                   $Detail_Table[$Ind][$Tbl_ColumnsVal[$b]][] = $ConType[$ConectionType . '_' . $Tbl_ColumnsVal[$b]];
            
                                                break;
                                  
                                            }
                                        }                            
                                
                                    //split equal true end
                               
                                    
                                }
                                
                            }
                            
                          
                             //last spot 
                       
                        }
                        

                         if (array_key_exists('installation_fee',$Detail_Table[$Ind])) {
                        
                        
                                $InstallFeeRaw = $Detail_Table[$Ind]['installation_fee']['self_install'] . " – " . $Detail_Table[$Ind]['installation_fee']['pro_install'];
                                $InstallFee = explode( " – ", $InstallFeeRaw );

                                $beforeMinFee = $Detail_Table[$Ind]['installation_fee']['before_min_install_fee'];
                                $minFee = min( array_filter($InstallFee) );
                                $afterMinFee = $Detail_Table[$Ind]['installation_fee']['after_min_install_fee'];

                                $beforeMaxFee = $Detail_Table[$Ind]['installation_fee']['before_max_install_fee'];
                                $maxFee = max( array_filter($InstallFee) );
                                $afterMaxFee = $Detail_Table[$Ind]['installation_fee']['after_max_install_fee'];

                                if($minFee == $maxFee) {
                                    $Detail_Table[$Ind]['installation_fee']['cell_data'] = $beforeMinFee . $minFee . $afterMinFee;
                                } else if ($minFee == "") {
                                    $Detail_Table[$Ind]['installation_fee']['cell_data'] = $beforeMaxFee . $maxFee . $afterMaxFee;
                                } else if ($maxFee == "") {
                                    $Detail_Table[$Ind]['installation_fee']['cell_data'] = $beforeMinFee . $minFee . $afterMinFee;
                                } else {
                                    $Detail_Table[$Ind]['installation_fee']['cell_data'] = $beforeMinFee . $minFee . $afterMinFee . " – " . $beforeMaxFee . $maxFee . $afterMaxFee;
                                }
                        
                        }
                        
                        
                            //data_caps 
                        if (array_key_exists('data_caps',$Detail_Table[$Ind]) && count($Detail_Table[$Ind]['data_caps']) > 2) {

                            $Detail_Table[$Ind]['data_caps']['cell_data'] = yesNoDataSpeed($Detail_Table[$Ind]['data_caps']);
                        }
                        
                        //symmetrical_speeds 
                        if (array_key_exists('symmetrical_speeds',$Detail_Table[$Ind]) && count($Detail_Table[$Ind]['symmetrical_speeds']) > 2) {
                           
                            $Detail_Table[$Ind]['symmetrical_speeds']['cell_data'] = yesNoDataSpeed($Detail_Table[$Ind]['symmetrical_speeds']);
                        }
                        
                        //connection types
                             if (array_key_exists('connection_types',$Detail_Table[$Ind])) {    
                          $ConTypes = '';
                          foreach ( $Detail_Table[$Ind]['connection_types'][0] as $ConType => $key ){
                              $KeyRaw = "";

                              if ( $key  == "dsl" ){
                                  $KeyRaw = strtoupper( $key );
                              }else{
                                  $KeyRaw = ucfirst($key);
                              }


                              if ( $ConTypes == "" ){
                                  $ConTypes .= $KeyRaw;
                              }else{
                                  $ConTypes .= ", " . $KeyRaw;
                              }
                          }
                        $Detail_Table[$Ind]['connection_types']['cell_data'] =  $ConTypes;
                  
                             }

                         if (array_key_exists('max_upload_speed',$Detail_Table[$Ind]) || array_key_exists('max_download_speed',$Detail_Table[$Ind])) {
                            if ( $Detail_Table[$Ind]['split_out'] == 0 ){
                                  
                                  if ( array_key_exists('max_upload_speed',$Detail_Table[$Ind]) && !$TableStyle = 'minimal-table' ){
                                      $Detail_Table[$Ind]['max_upload_speed']['cell_data'] = $upload_icon . " " . $Detail_Table[$Ind]['max_upload_speed'][0] ;
                                      
                                  } else if ( array_key_exists('max_upload_speed',$Detail_Table[$Ind]) && $TableStyle = 'minimal-table' ) {
                                      $Detail_Table[$Ind]['max_upload_speed']['cell_data'] = $Detail_Table[$Ind]['max_upload_speed'][0] ;
                                  }
                                
                                
                                  if( array_key_exists('max_download_speed',$Detail_Table[$Ind]) && !$TableStyle = 'minimal-table' ){
                                    
                                        $Detail_Table[$Ind]['max_download_speed']['cell_data'] = $download_icon . " " . $Detail_Table[$Ind]['max_download_speed'][0] ;
                                      
                                      
                                  } else if( array_key_exists('max_download_speed',$Detail_Table[$Ind]) && $TableStyle = 'minimal-table' ) {
                                
                                      $Detail_Table[$Ind]['max_download_speed']['cell_data'] = $Detail_Table[$Ind]['max_download_speed'][0];
                                        
                                  }
                          

                       

                              }else{
                                
                                
                   
                                      if ( array_key_exists('max_download_speed',$Detail_Table[$Ind]) ){
                        
                                           $Detail_Table[$Ind]['max_download_speed']['cell_data'] = splitMaxDownUpload($Detail_Table[$Ind]['max_download_speed']);

                                      }
                                      if(array_key_exists('max_upload_speed',$Detail_Table[$Ind])){

                                          $Detail_Table[$Ind]['max_upload_speed']['cell_data'] = splitMaxDownUpload($Detail_Table[$Ind]['max_upload_speed']);
                                      }
                                
                                 if(array_key_exists('starting_price',$Detail_Table[$Ind])){
                                          //starting_price
                                      $MinVal = ''; $MaxVal = '';
                                      $StPrice = array();
                                      $Askt = 0;
                                      foreach ($Detail_Table[$Ind]['starting_price'] as $key => $IndvPrice ){
                                          $IndvPriceRaw = explode("*", $IndvPrice);
                                          $PriceArrayRaw[$key] = explode("^", $IndvPriceRaw[0]);

                                          $StPrice[] = $PriceArrayRaw;
                                          if ( count($IndvPriceRaw) > 1 ){
                                              $Askt++;
                                          }
                                      }

                                      if($FilterResult == 0){
                                          $MinVal = min(array_filter(array_column($PriceArrayRaw, 1)));
                                          $key_min = array_search($MinVal, array_column($PriceArrayRaw, 1));

                                          $MaxVal = max(array_filter(array_column($PriceArrayRaw, 1)));
                                          $key_max = array_search($MaxVal, array_column($PriceArrayRaw, 1));
                                      } else {
                                          $MinVal = array_filter(array_column($PriceArrayRaw, 1))[0];
                                          $key_min = array_search($MinVal, array_column($PriceArrayRaw, 1));

                                          $MaxVal = array_filter(array_column($PriceArrayRaw, 1))[0];
                                          $key_max = array_search($MaxVal, array_column($PriceArrayRaw, 1));
                                      }
                                      if ( $Askt >= 1 ){
                                          $ShowAsterisk = "*";
                                      }else{
                                          $ShowAsterisk = "";
                                      }

                                      if ( $MinVal == $MaxVal ){ // BOTH ARE EQUAL
                                          $Detail_Table[$Ind]['starting_price']['cell_data'] = $PriceArrayRaw[$key_max][0] . $MaxVal . $PriceArrayRaw[$key_max][2] . $ShowAsterisk;
                                      }else{ // BOTH ARE DIFFERNT
                                          $Detail_Table[$Ind]['starting_price']['cell_data'] = $PriceArrayRaw[$key_min][0] . $MinVal . $PriceArrayRaw[$key_min][2] . " – " . $PriceArrayRaw[$key_max][0] . $MaxVal . $PriceArrayRaw[$key_max][2] . $ShowAsterisk;
                                      }

                                  }
                           
                                
                                 if ( array_key_exists('max_upload_speed',$Detail_Table[$Ind]) && !$TableStyle = 'minimal-table' ){
                                      $Detail_Table[$Ind]['max_upload_speed']['cell_data'] = $upload_icon . " " . $Detail_Table[$Ind]['max_upload_speed'][0] ;
                                      
                                  }                                 
                                
                                  if( array_key_exists('max_download_speed',$Detail_Table[$Ind]) && !$TableStyle = 'minimal-table' ){
                                    
                                        $Detail_Table[$Ind]['max_download_speed']['cell_data'] = $download_icon . " " . $Detail_Table[$Ind]['max_download_speed'][0] ;
                                      
                                      
                                  } 
                                
                                
                                
                                
                            }
                        
                         }

                        //Cta Button
 
                        $Detail_Table[$Ind]['cta']['header'] = $Tbl_Columns[$headerCounter++];
                        $Detail_Table[$Ind]['cta']['cell_data'] =  '<a href="' . $cta_link . '" class="cta-btn ' . $zip_popup_class . ' ' . $class . '" ' . $data_att .'>' . $cta_text . '</a>';
                    
                       
                        unset($Detail_Table[$Ind]['split_out']);
                        $Ind++;
                    }

                }


/*echo "<pre>";
echo print_r($Tbl_Columns) ; 
echo print_r($Detail_Table) ; 
echo "</pre>";
*/
?>

<section id="comparetable" class="compare-providers-wrap">
    <?php if(!empty($Heading)) echo '<h2>'.$Heading.'</h2>'; ?>
    <div class="compare-providers-table <?php echo $TableStyle ?>">     
        
        
        
     <?php echo buildDesktopTable("myTable", "compare-providers-table-inner desktop-table ", $Detail_Table);?>

<?php 
if ( is_array($Detail_Table) ){
if ($TableStyle == 'minimal-table'){ 

    echo buildMobileTable("myTable-mobile", "compare-providers-table-inner mobile-table ", $Detail_Table);   

        
        ?>

<?php }
} ?>
    </div>
    <div class="table_desc">
        <?php echo $TableDescription; ?>
    </div>
</section>