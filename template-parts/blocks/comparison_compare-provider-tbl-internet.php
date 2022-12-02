<?php

$Ind = 0;
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

    if ( $internet_check == "1" ){
        if ( $DoFiltering == 1 ){
            include( "compare-provider-tbl-internet-data-array.php");
        }else{
            if ( $FilterResult == 0 ){
                include( "compare-provider-tbl-internet-data-array.php");
            }
        }
    }
}?>

<section id="comparetable" class="compare-providers-wrap">
    <?php if(!empty($Heading)) echo '<h2>'.$Heading.'</h2>'; ?>
    <div class="compare-providers-table <?php echo $TableStyle ?> comparison-template">

<?php

//add logos to beginning of arrays
array_unshift($Tbl_ColumnsVal , 'our_picks');
array_unshift($Tbl_Columns , 'Our Picks');

//add CTA buttons to end of arrays
$Tbl_ColumnsVal[] = '';

if ( is_array($Detail_Table) ){ ?>
<table class="compare-providers-table-inner desktop-table">
    <tbody>
        <?php
            for ( $d=0; $d<( count($Tbl_ColumnsVal) ); $d++){

                if ($d === count($Tbl_ColumnsVal)-1):
                    echo '<tr style="background-color:#fff;">';
                else:    
                    echo '<tr>';
                endif;
   
                echo '<th class="border-bottom-0 pt-3 pb-3" style="width:25%;"><p class="font-weight-bold mb-0">'.$Tbl_Columns[$d].'</p></th>';
                foreach($Detail_Table as $detail){

                    $ConSlipt = $detail['split_out'];
                    echo '<td class="text-center" style="width:37.5%;">';
                    //if it's the first row
                    if ($d === 0):
                        echo '<img src="'.$detail['provider']['logo'].'" alt="logo" class="p-2" width="180" height="40">';
                    //if it's the last row
                    elseif($d === count($Tbl_ColumnsVal)-1):
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
                        echo '<a href="#" class="cta_btn zip-popup-btn font-weight-bold pt-2 pb-2" style="width:100%;" data-toggle="modal" data-target="#zipPopupModal-'.$rand.'">Check Availability</a>';
                    else: 

                        switch( $Tbl_ColumnsVal[$d] ){

                            case "connection_types":
                                $ConTypes = '';
                                if (is_array($detail['connection_types'][0]) && !empty($detail['connection_types'][0])){
                                    foreach ( $detail['connection_types'][0]  as $ConType => $key ){
                                        if ( $ConTypes == "" ){
                                            $ConTypes .= ucfirst($key);
                                        }else{
                                            $ConTypes .= ", " . ucfirst($key);
                                        }
                                    }
                                } else {
                                    $ConTypes = 'N/A';
                                }
                            
                                echo $ConTypes;
                            break;

                            case "equipment_fee":
                                echo $detail['internet_equipment_rental_fee'][0];
                            break;

                            default:
                                $ArInd = $Tbl_ColumnsVal[$d];      
                                $Detail = '';                     
                                $RangEcols = array( "max_download_speed", "max_upload_speed", "starting_price" );
                                $YesNoCols = array( "symmetrical_speeds", "data_caps" );
                                $FeeCols = array( "installation_fee", "internet_equipment_rental_fee" );
                                if (isset($detail[$ArInd])){
                                    $Detail = $detail[$ArInd];
                                }

                                if ( strtolower($ArInd) == "installation_fee" ){
                                    $Detail = $detail['install_fee'];      
                                    if ($Detail['self_install'] != '' || $Detail['pro_install'] != ''){                          
                                        $InstallFeeRaw = $Detail['self_install'] . " – " . $Detail['pro_install'];
                                        $InstallFee = explode( " – ", $InstallFeeRaw );

                                        $beforeMinFee = $Detail['before_min_install_fee'];
                                        $minFee = min( array_filter($InstallFee) );
                                        $afterMinFee = $Detail['after_min_install_fee'];

                                        $beforeMaxFee = $Detail['before_max_install_fee'];
                                        $maxFee = max( array_filter($InstallFee) );
                                        $afterMaxFee = $Detail['after_max_install_fee'];

                                        if($minFee == $maxFee) {
                                            echo $beforeMinFee . $minFee . $afterMinFee;
                                        } else if ($minFee == "") {
                                            echo $beforeMaxFee . $maxFee . $afterMaxFee;
                                        } else if ($maxFee == "") {
                                            echo $beforeMinFee . $minFee . $afterMinFee;
                                        } else {
                                            echo $beforeMinFee . $minFee . $afterMinFee . " – " . $beforeMaxFee . $maxFee . $afterMaxFee;
                                        }
                                    } else {
                                        echo 'N/A';
                                    }     

                                } else if ( $ArInd != "split_out" ){
                                   if ( $ConSlipt == 0 ){
                                        if ( $ArInd == "max_upload_speed" && $TableStyle != 'minimal-table' ){
                                            echo $upload_icon . " ";
                                        }else if( $ArInd == "max_download_speed" && $TableStyle != 'minimal-table' ){
                                            echo $download_icon . " ";
                                        }
                                        if (isset($Detail[0])){
                                            echo $Detail[0];
                                        }
                                    }else{
                                        if ( $ArInd != "" ){
                                            $MinVal = min( array_filter($Detail) );
                                            $MaxVal = max( array_filter($Detail) );
                                            if ( in_array( $ArInd, $RangEcols ) ){
                                                $CellData = '';
                                                if ( ($ArInd == "max_download_speed") ){
                                                    foreach($Detail as $key => $IndSpeed){
                                                        $SpeedArrayMb[$key] = explode("^", $IndSpeed);
                                                    }
                                                    
                                                    $MinSpeed = min(array_filter(array_column($SpeedArrayMb, 1)));
                                                    $keyMin = array_search($MinSpeed, array_column($SpeedArrayMb, 1));

                                                    $MaxSpeed = max(array_filter(array_column($SpeedArrayMb, 1)));
                                                    $keyMax = array_search($MaxSpeed, array_column($SpeedArrayMb, 1));
                                                    if ( $MinSpeed == $MaxSpeed ){ // BOTH ARE EQUAL
                                                        $CellData .= $SpeedArrayMb[$keyMax][0] . $MaxSpeed . ' ' . $SpeedArrayMb[$keyMax][2];
                                                    }else{ // BOTH ARE DIFFERNT
                                                        $CellData .= $SpeedArrayMb[$keyMin][0] . $MinSpeed . ' ' . $SpeedArrayMb[$keyMin][2] . " – " . $SpeedArrayMb[$keyMax][0] . $MaxSpeed . ' ' . $SpeedArrayMb[$keyMax][2];
                                                    }
                                                
                                                }else if($ArInd == "max_upload_speed"){

                                                    foreach($Detail as $key => $IndSpeed1){
                                                        $SpeedArrayMb1[$key] = explode("^", $IndSpeed1);
                                                    }
                                                    
                                                    $MinSpeed = min(array_filter(array_column($SpeedArrayMb1, 1)));
                                                    $keyMin = array_search($MinSpeed, array_column($SpeedArrayMb1, 1));

                                                    $MaxSpeed = max(array_filter(array_column($SpeedArrayMb1, 1)));
                                                    $keyMax = array_search($MaxSpeed, array_column($SpeedArrayMb1, 1));
                                                    if ( $MinSpeed == $MaxSpeed ){ // BOTH ARE EQUAL
                                                        $CellData .= $SpeedArrayMb1[$keyMax][0] . $MaxSpeed . $SpeedArrayMb1[$keyMax][2];
                                                    }else{ // BOTH ARE DIFFERNT
                                                        $CellData .= $SpeedArrayMb1[$keyMin][0] . $MinSpeed . $SpeedArrayMb1[$keyMin][2] . " – " . $SpeedArrayMb1[$keyMax][0] . $MaxSpeed . $SpeedArrayMb1[$keyMax][2];
                                                    }
                                                }else{

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
                                                    foreach($StPrice1Arr as $key => $IndStPrice){
                                                        $StPriceVal[$key] = explode("^", $IndStPrice);
                                                    }
                                                    $MinVal = min(array_filter(array_column($StPriceVal, 1)));
                                                    $key_min = array_search($MinVal, array_column($StPriceVal, 1));

                                                    $MaxVal = max(array_filter(array_column($StPriceVal, 1)));
                                                    $key_max = array_search($MaxVal, array_column($StPriceVal, 1));

                                                    if ( $MinVal == $MaxVal ){ // BOTH ARE EQUAL
                                                        $CellData .= $StPriceVal[$key_max][0] . $MaxVal . $StPriceVal[$key_max][2] . $ShowAsterisk;
                                                    }else{ // BOTH ARE DIFFERNT
                                                        $CellData .= $StPriceVal[$key_min][0] . $MinVal . $StPriceVal[$key_min][2] . " – " . $StPriceVal[$key_max][0] . $MaxVal . $StPriceVal[$key_max][2] . $ShowAsterisk;
                                                    }
                                                }
                                                echo $CellData;
                                            }else if ( in_array( $ArInd, $YesNoCols ) ){
                                                
                                                $CellData = ''; $YesAns = 0; $NoAns = 0;
                                                foreach ( $Detail as $Ans ){
                                                    if ( strtolower($Ans) == "yes" ){
                                                        $YesAns++;
                                                    }else{
                                                        $NoAns++;
                                                    }
                                                    if ( $YesAns >= 1 ){
                                                        $CellData = 'Yes';
                                                    }else{
                                                        $CellData = 'No';
                                                    }
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
                                echo '</td>';
                        }
                    endif;
                }
                echo '</tr>';                   
            }
        ?>
    </tbody>
</table>

<table class="compare-providers-table-inner mobile-table" style="table-layout: fixed;">
    <tbody>
        <?php
            $tbl_count = 1;
            foreach($Detail_Table as $detail){

                $providers_spacing = '';

                if ($tbl_count !== count($Detail_Table)){
                    $providers_spacing = 'pb-5';
                }
                $tbl_count++;
                for ( $d=0; $d<( count($Tbl_ColumnsVal) ); $d++){

                    if ($d === count($Tbl_ColumnsVal)-1):
                        echo '<tr style="background-color:#fff;">';
                    else:    
                        echo '<tr>';
                        echo '<th class="border-bottom-0 pt-3 pb-3 font-weight-bold" style="width:50%;"><p class="font-weight-bold mb-0">'.$Tbl_Columns[$d].'</p></th>';
                    endif;
    

                        $ConSlipt = $detail['split_out'];
                        //if it's the first row
                        if ($d === 0):
                            echo '<td class="text-center">';
                            echo '<img src="'.$detail['provider']['logo'].'" alt="logo" class="p-2" width="180" height="40">';
                        //if it's the last row
                        elseif($d === count($Tbl_ColumnsVal)-1):
                            echo '<td class="text-center '.$providers_spacing.'" colspan=100%>';
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
                            echo '<a href="#" class="cta_btn zip-popup-btn font-weight-bold pt-2 pb-2" style="width:100%;" data-toggle="modal" data-target="#zipPopupModal-'.$rand.'">Check Availability</a>';
                        else: 
                            echo '<td class="text-center">';
                            switch( $Tbl_ColumnsVal[$d] ){

                                case "connection_types":
                                    $ConTypes = '';
                                    if (is_array($detail['connection_types'][0]) && !empty($detail['connection_types'][0])){
                                        foreach ( $detail['connection_types'][0]  as $ConType => $key ){
                                            if ( $ConTypes == "" ){
                                                $ConTypes .= ucfirst($key);
                                            }else{
                                                $ConTypes .= ", " . ucfirst($key);
                                            }
                                        }
                                    } else {
                                        $ConTypes = 'N/A';
                                    }
                                
                                    echo $ConTypes;
                                break;

                                case "equipment_fee":
                                    echo $detail['internet_equipment_rental_fee'][0];
                                break;

                                default:
                                    $ArInd = $Tbl_ColumnsVal[$d];      
                                    $Detail = '';                     
                                    $RangEcols = array( "max_download_speed", "max_upload_speed", "starting_price" );
                                    $YesNoCols = array( "symmetrical_speeds", "data_caps" );
                                    $FeeCols = array( "installation_fee", "internet_equipment_rental_fee" );
                                    if (isset($detail[$ArInd])){
                                        $Detail = $detail[$ArInd];
                                    }

                                    if ( strtolower($ArInd) == "installation_fee" ){
                                        $Detail = $detail['install_fee'];      
                                        if ($Detail['self_install'] != '' || $Detail['pro_install'] != ''){                          
                                            $InstallFeeRaw = $Detail['self_install'] . " – " . $Detail['pro_install'];
                                            $InstallFee = explode( " – ", $InstallFeeRaw );

                                            $beforeMinFee = $Detail['before_min_install_fee'];
                                            $minFee = min( array_filter($InstallFee) );
                                            $afterMinFee = $Detail['after_min_install_fee'];

                                            $beforeMaxFee = $Detail['before_max_install_fee'];
                                            $maxFee = max( array_filter($InstallFee) );
                                            $afterMaxFee = $Detail['after_max_install_fee'];

                                            if($minFee == $maxFee) {
                                                echo $beforeMinFee . $minFee . $afterMinFee;
                                            } else if ($minFee == "") {
                                                echo $beforeMaxFee . $maxFee . $afterMaxFee;
                                            } else if ($maxFee == "") {
                                                echo $beforeMinFee . $minFee . $afterMinFee;
                                            } else {
                                                echo $beforeMinFee . $minFee . $afterMinFee . " – " . $beforeMaxFee . $maxFee . $afterMaxFee;
                                            }
                                        } else {
                                            echo 'N/A';
                                        }     

                                    }else if ( $ArInd != "split_out" ){
                                        if ( $ConSlipt == 0 ){
                                            if ( $ArInd == "max_upload_speed" && $TableStyle != 'minimal-table' ){
                                                echo $upload_icon . " ";
                                            }else if( $ArInd == "max_download_speed" && $TableStyle != 'minimal-table' ){
                                                echo $download_icon . " ";
                                            }
                                            if (isset($Detail[0])){
                                                echo $Detail[0];
                                            }
                                        }else{
                                            if ( $ArInd != "" ){
                                                $MinVal = min( array_filter($Detail) );
                                                $MaxVal = max( array_filter($Detail) );
                                                if ( in_array( $ArInd, $RangEcols ) ){
                                                    $CellData = '';
                                                    if ( ($ArInd == "max_download_speed") ){
                                                        foreach($Detail as $key => $IndSpeed){
                                                            $SpeedArrayMb[$key] = explode("^", $IndSpeed);
                                                        }
                                                        
                                                        $MinSpeed = min(array_filter(array_column($SpeedArrayMb, 1)));
                                                        $keyMin = array_search($MinSpeed, array_column($SpeedArrayMb, 1));

                                                        $MaxSpeed = max(array_filter(array_column($SpeedArrayMb, 1)));
                                                        $keyMax = array_search($MaxSpeed, array_column($SpeedArrayMb, 1));
                                                        if ( $MinSpeed == $MaxSpeed ){ // BOTH ARE EQUAL
                                                            $CellData .= $SpeedArrayMb[$keyMax][0] . $MaxSpeed . ' ' . $SpeedArrayMb[$keyMax][2];
                                                        }else{ // BOTH ARE DIFFERNT
                                                            $CellData .= $SpeedArrayMb[$keyMin][0] . $MinSpeed . ' ' . $SpeedArrayMb[$keyMin][2] . " – " . $SpeedArrayMb[$keyMax][0] . $MaxSpeed . ' ' . $SpeedArrayMb[$keyMax][2];
                                                        }
                                                    
                                                    }else if($ArInd == "max_upload_speed"){

                                                        foreach($Detail as $key => $IndSpeed1){
                                                            $SpeedArrayMb1[$key] = explode("^", $IndSpeed1);
                                                        }
                                                        
                                                        $MinSpeed = min(array_filter(array_column($SpeedArrayMb1, 1)));
                                                        $keyMin = array_search($MinSpeed, array_column($SpeedArrayMb1, 1));

                                                        $MaxSpeed = max(array_filter(array_column($SpeedArrayMb1, 1)));
                                                        $keyMax = array_search($MaxSpeed, array_column($SpeedArrayMb1, 1));
                                                        if ( $MinSpeed == $MaxSpeed ){ // BOTH ARE EQUAL
                                                            $CellData .= $SpeedArrayMb1[$keyMax][0] . $MaxSpeed . $SpeedArrayMb1[$keyMax][2];
                                                        }else{ // BOTH ARE DIFFERNT
                                                            $CellData .= $SpeedArrayMb1[$keyMin][0] . $MinSpeed . $SpeedArrayMb1[$keyMin][2] . " – " . $SpeedArrayMb1[$keyMax][0] . $MaxSpeed . $SpeedArrayMb1[$keyMax][2];
                                                        }
                                                    }else{

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
                                                        foreach($StPrice1Arr as $key => $IndStPrice){
                                                            $StPriceVal[$key] = explode("^", $IndStPrice);
                                                        }
                                                        $MinVal = min(array_filter(array_column($StPriceVal, 1)));
                                                        $key_min = array_search($MinVal, array_column($StPriceVal, 1));

                                                        $MaxVal = max(array_filter(array_column($StPriceVal, 1)));
                                                        $key_max = array_search($MaxVal, array_column($StPriceVal, 1));

                                                        if ( $MinVal == $MaxVal ){ // BOTH ARE EQUAL
                                                            $CellData .= $StPriceVal[$key_max][0] . $MaxVal . $StPriceVal[$key_max][2] . $ShowAsterisk;
                                                        }else{ // BOTH ARE DIFFERNT
                                                            $CellData .= $StPriceVal[$key_min][0] . $MinVal . $StPriceVal[$key_min][2] . " – " . $StPriceVal[$key_max][0] . $MaxVal . $StPriceVal[$key_max][2] . $ShowAsterisk;
                                                        }
                                                    }
                                                    echo $CellData;
                                                }else if ( in_array( $ArInd, $YesNoCols ) ){
                                                    
                                                    $CellData = ''; $YesAns = 0; $NoAns = 0;
                                                    foreach ( $Detail as $Ans ){
                                                        if ( strtolower($Ans) == "yes" ){
                                                            $YesAns++;
                                                        }else{
                                                            $NoAns++;
                                                        }
                                                        if ( $YesAns >= 1 ){
                                                            $CellData = 'Yes';
                                                        }else{
                                                            $CellData = 'No';
                                                        }
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
                                    echo '</td>';
                            }
                        endif;
                }
                echo '</tr>';
                                
            }
        ?>
    </tbody>
</table>
<?php
} 

?>
</div>
<div class="table_desc mt-3">
    <?php echo $TableDescription; ?>
</div>
</section>