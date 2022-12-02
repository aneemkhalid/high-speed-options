<?php

include( "compare-provider-tbl-tv-data-array.php"); ?>

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
                        echo '<img src="'.$detail['provider']['logo'].'" class="p-2" alt="logo" width="180" height="40">';
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

                            default:
                                if ( $Tbl_ColumnsVal[$d] != "split_out" ){
                                    if ( $ConSlipt == 0 ){
                                        echo $detail[$Tbl_ColumnsVal[$d]][0];
                                    }else{
                                        if ( strtolower( $Tbl_ColumnsVal[$d] ) == "starting_price" ){
                                            $CellData = '';                                                
                                            $MinVal = ''; $MaxVal = '';
                                            $StPrice = array();
                                            $Askt = 0;
                                            foreach ( $detail[$Tbl_ColumnsVal[$d]] as $key => $IndvPrice ){

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
                                        }else if ( strtolower( $Tbl_ColumnsVal[$d] ) == "tv_installation_fee"){
                                            foreach($detail[$Tbl_ColumnsVal[$d]] as $key => $IndVal){
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
                                        }else if ( strtolower( $Tbl_ColumnsVal[$d] ) == "tv_equipment_fee"){
                                            foreach($detail[$Tbl_ColumnsVal[$d]] as $key => $IndVal){
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
                                        }else if ( strtolower( $Tbl_ColumnsVal[$d] ) == "minimum_channel_count"){
                                            foreach($detail[$Tbl_ColumnsVal[$d]] as $key => $IndVal){
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
                                        }else if ( strtolower( $Tbl_ColumnsVal[$d] ) == "dvr_recordings"){
                                            foreach($detail[$Tbl_ColumnsVal[$d]] as $key => $IndVal){
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
                                            $CellData = $detail[$Tbl_ColumnsVal[$d]][0];
                                            echo $CellData;
                                        }
                                    }                                            
                                }


                                echo ' </td>';


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

                                default:
                                    if ( $Tbl_ColumnsVal[$d] != "split_out" ){
                                        if ( $ConSlipt == 0 ){
                                            echo $detail[$Tbl_ColumnsVal[$d]][0];
                                        }else{
                                            if ( strtolower( $Tbl_ColumnsVal[$d] ) == "starting_price" ){
                                                $CellData = '';                                                
                                                $MinVal = ''; $MaxVal = '';
                                                $StPrice = array();
                                                $Askt = 0;
                                                foreach ( $detail[$Tbl_ColumnsVal[$d]] as $key => $IndvPrice ){

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
                                            }else if ( strtolower( $Tbl_ColumnsVal[$d] ) == "tv_installation_fee"){
                                                foreach($detail[$Tbl_ColumnsVal[$d]] as $key => $IndVal){
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
                                            }else if ( strtolower( $Tbl_ColumnsVal[$d] ) == "tv_equipment_fee"){
                                                foreach($detail[$Tbl_ColumnsVal[$d]] as $key => $IndVal){
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
                                            }else if ( strtolower( $Tbl_ColumnsVal[$d] ) == "minimum_channel_count"){
                                                foreach($detail[$Tbl_ColumnsVal[$d]] as $key => $IndVal){
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
                                            }else if ( strtolower( $Tbl_ColumnsVal[$d] ) == "dvr_recordings"){
                                                foreach($detail[$Tbl_ColumnsVal[$d]] as $key => $IndVal){
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
                                                $CellData = $detail[$Tbl_ColumnsVal[$d]][0];
                                                echo $CellData;
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