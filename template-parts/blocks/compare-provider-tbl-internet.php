<section id="comparetable" class="compare-providers-wrap">
    <?php if(!empty($Heading)) echo '<h2>'.$Heading.'</h2>'; ?>
    <?php if(!empty($body_text)) echo '<p class="mb-4">'.str_replace(['<p>', '</p>'], '', $body_text).'</p>'; ?>
    <div class="compare-providers-table <?php echo $TableStyle ?>">
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
                $providerIDCounter = 0;
                $providerIDCounterMobile = 0;
                $providerCounterMobile = 0;        
                $providerIDArrayTableHolder = [];
                $providerImpLoad = '';   
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

            <tbody>
                <?php
                    if ( is_array($Detail_Table) ){
                        foreach( $Detail_Table as $Detail_Col => $DetailArr ){
                            echo "<tr>";
                            $ConSlipt = $DetailArr['split_out'];
                            
                             //dataLayer info
       
                              $variantProvider = [
                                        'text' => 'Compare Providers Table Desktop'
                                ]; 
                            $variantProviderImp = [
                                    'text' => 'Compare Providers Table'
                            ]; 

                            $providerSlug = get_post_field( 'post_name', get_post() );
                       
                            $providerCounter++;
                            
                        
                        

                            foreach( $DetailArr as $ArInd => $Detail ){
                                switch( strtolower($ArInd) ){
                                    case "provider":
                                            $checkBtnProviderID = url_to_postid($Detail['URL']);
                                            $providersListProdClick = dataLayerProdClick($checkBtnProviderID, $variantProvider, $providerCounter, $providerSlug, $Heading);
                                            $providerProdImp = dataLayerProductImpression($checkBtnProviderID,  $providerSlug, $variantProviderImp, $providerSlug . ' List', $providerCounter );
                                            $providerImpLoad .= $providerProdImp;

                                        if ( $TableStyle == "minimal-table" ){
                                            echo '<td><a href="' . $Detail['URL'] . '" onclick="'.$providersListProdClick.'">' . $Detail['name'] . '</a></td>';
                                        }else{
                                            echo '<td><a href="' . $Detail['URL'] . '" onclick="'.$providersListProdClick.'"><img src="' . $Detail['logo'] . '" alt="' . $Detail['name'] . '"></a></td>';
                                        }
                                    break;

                                    case "connection_types":
                                        $ConTypes = '';
                                        if (is_array($Detail[0]) && !empty($Detail[0])){
                                            foreach ( $Detail[0] as $ConType => $key ){
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
                                        }else {
                                            $ConTypes = 'N/A';
                                        }
                                        echo '<td>' . $ConTypes . '</td>';
                                    break;

                                    default:
                                        $RangEcols = array( "max_download_speed", "max_upload_speed", "starting_price" );
                                        $YesNoCols = array( "symmetrical_speeds", "data_caps" );
                                        $FeeCols = array( "install_fee", "internet_equipment_rental_fee" );

                                        if ( strtolower($ArInd) == "cta" ){                                            
                                            if ( $CTAButton != "" ){
                                                echo '<td><a href="' . $Detail['button_link'] . '" class="cta_btn" ' . $Detail['button_link'] . '>' . $Detail['button_text'] . '</a></td>';
                                            }                                            
                                        }else if ( strtolower($ArInd) == "install_fee" ){
                                            echo "<td>";
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
                                            
                                            echo "</td>";
                                        }else if ( $ArInd != "split_out" ){
                                            if ( $ConSlipt == 0 ){
                                                echo '<td>';
                                                if ( $ArInd == "max_upload_speed" && $TableStyle != 'minimal-table' ){
                                                    echo $upload_icon . " ";
                                                }else if( $ArInd == "max_download_speed" && $TableStyle != 'minimal-table' ){
                                                    echo $download_icon . " ";
                                                }
                                                echo $Detail[0];

                                                echo '</td>';
                                            }else{
                                                echo '<td>';

                                                if ( in_array( $ArInd, $RangEcols ) ){

                                                    $CellData = '';
                                                    if ( $ArInd == "max_upload_speed" && $TableStyle != 'minimal-table' ){
                                                        $CellData .= $upload_icon . " ";
                                                    }else if( $ArInd == "max_download_speed" && $TableStyle != 'minimal-table' ){
                                                        $CellData .= $download_icon . " ";
                                                    }

                                                        if ( ($ArInd == "max_download_speed") ){
                                                            foreach($Detail as $key => $IndSpeed){
                                                            $SpeedArray[$key] = explode("^", $IndSpeed);
                                                            }
                                                            if($FilterResult == 0){
                                                            $MinSpeed = min(array_filter(array_column($SpeedArray, 1)));
                                                            $keyMin = array_search($MinSpeed, array_column($SpeedArray, 1));
        
                                                            $MaxSpeed = max(array_filter(array_column($SpeedArray, 1)));
                                                            $keyMax = array_search($MaxSpeed, array_column($SpeedArray, 1));
                                                            } else {
                                                            $MinSpeed = array_filter(array_column($SpeedArray, 1))[0];
                                                            $keyMin = array_search($MinSpeed, array_column($SpeedArray, 1));
        
                                                            $MaxSpeed = array_filter(array_column($SpeedArray, 1))[0];
                                                            $keyMax = array_search($MaxSpeed, array_column($SpeedArray, 1));
                                                            }
                                                            if ( $MinSpeed == $MaxSpeed ){ // BOTH ARE EQUAL
                                                                $CellData .= $SpeedArray[$keyMax][0] . $MaxSpeed . $SpeedArray[$keyMax][2];
                                                            }else{ // BOTH ARE DIFFERNT
                                                                $CellData .= $SpeedArray[$keyMin][0] . $MinSpeed . $SpeedArray[$keyMin][2] . " – " . $SpeedArray[$keyMax][0] . $MaxSpeed . $SpeedArray[$keyMax][2];
                                                            }
                                                        
                                                        }else if($ArInd == "max_upload_speed"){
            
                                                            foreach($Detail as $key => $IndSpeed){
                                                            $SpeedArray1[$key] = explode("^", $IndSpeed);
                                                            }
                                                            if($FilterResult == 0){
                                                            $MinSpeed = min(array_filter(array_column($SpeedArray1, 1)));
                                                            $keyMin = array_search($MinSpeed, array_column($SpeedArray1, 1));
        
                                                            $MaxSpeed = max(array_filter(array_column($SpeedArray1, 1)));
                                                            $keyMax = array_search($MaxSpeed, array_column($SpeedArray1, 1));
                                                            } else {
                                                            $MinSpeed = array_filter(array_column($SpeedArray1, 1))[0];
                                                            $keyMin = array_search($MinSpeed, array_column($SpeedArray1, 1));
        
                                                            $MaxSpeed = array_filter(array_column($SpeedArray1, 1))[0];
                                                            $keyMax = array_search($MaxSpeed, array_column($SpeedArray1, 1));
                                                            }
                                                            if ( $MinSpeed == $MaxSpeed ){ // BOTH ARE EQUAL
                                                                $CellData .= $SpeedArray1[$keyMax][0] . $MaxSpeed . $SpeedArray1[$keyMax][2];
                                                            }else{ // BOTH ARE DIFFERNT
                                                                $CellData .= $SpeedArray1[$keyMin][0] . $MinSpeed . $SpeedArray1[$keyMin][2] . " – " . $SpeedArray1[$keyMax][0] . $MaxSpeed . $SpeedArray1[$keyMax][2];
                                                            }
                                                        }else{
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
                                                            $CellData = $PriceArrayRaw[$key_max][0] . $MaxVal . $PriceArrayRaw[$key_max][2] . $ShowAsterisk;
                                                        }else{ // BOTH ARE DIFFERNT
                                                            $CellData = $PriceArrayRaw[$key_min][0] . $MinVal . $PriceArrayRaw[$key_min][2] . " – " . $PriceArrayRaw[$key_max][0] . $MaxVal . $PriceArrayRaw[$key_max][2] . $ShowAsterisk;
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
                                                echo ' </td>';
                                            }                                            
                                        }
                                }
                            }
                            echo "</tr>";
                        }
                    }
                ?>
            </tbody>
        </table>
        
<?php 
if ( is_array($Detail_Table) ){
if ($TableStyle == 'minimal-table'){ ?>
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
                    if (isset($Tbl_ColumnsVal[$Indx-1])){
                        $TblColVal = strtolower($Tbl_ColumnsVal[$Indx-1]);
                    } else {
                        $TblColVal = '';
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
       
                              $variantProvider = [
                                        'text' => 'Compare Providers Table Mobile'
                                ]; 

                            $providerSlug = get_post_field( 'post_name', get_post() );
                       
                            $providerCounterMobile++;
                        
                             $checkBtnProviderIDMobile = url_to_postid($Detail_Table[$d]['provider']['URL']);
                            $providersListProdMobileClick = dataLayerProdClick($checkBtnProviderIDMobile, $variantProvider, $providerCounterMobile, $providerSlug, $Heading);
                        
                        echo '<a href="' . $Detail_Table[$d]['provider']['URL'] . '" onclick="'.$providersListProdMobileClick.'">' . $Detail_Table[$d]['provider']['name'] . '</a>';
                    }else if( $TblColVal == "" && $Indx != 0 ){
                        if ( $CTAButton != "" ){
                            echo '<a href="' . $Detail_Table[$d]['cta']['button_link'] . '" class="cta_btn" ' . $Detail_Table[$d]['cta']['button_link'] . '>' . $Detail_Table[$d]['cta']['button_text'] . '</a>';
                        }
                    }

                    switch( $TblColVal ){

                        case "connection_types":
                            $ConTypes = '';
                            if (is_array($Detail_Table[$d]['connection_types'][0]) && !empty($Detail_Table[$d]['connection_types'][0])){
                                foreach ( $Detail_Table[$d]['connection_types'][0]  as $ConType => $key ){
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
                            echo $Detail_Table[$d]['internet_equipment_rental_fee'][0];
                        break;

                        default:
                            $ArInd = $TblColVal;      
                            $Detail = '';                     
                            $RangEcols = array( "max_download_speed", "max_upload_speed", "starting_price" );
                            $YesNoCols = array( "symmetrical_speeds", "data_caps" );
                            $FeeCols = array( "installation_fee", "internet_equipment_rental_fee" );
                            if (isset($Detail_Table[$d][$ArInd])){
                                $Detail = $Detail_Table[$d][$ArInd];
                            }

                            if ( strtolower($ArInd) == "installation_fee" ){
                                $Detail = $Detail_Table[$d]['install_fee'];      
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
                                            if ( $ArInd == "max_upload_speed" && $TableStyle != 'minimal-table' ){
                                                $CellData .= $upload_icon . " ";
                                            }else if( $ArInd == "max_download_speed" && $TableStyle != 'minimal-table' ){
                                                $CellData .= $download_icon . " ";
                                            }
                                            if ( ($ArInd == "max_download_speed") ){
                                                foreach($Detail as $key => $IndSpeed){
                                                    $SpeedArrayMb[$key] = explode("^", $IndSpeed);
                                                }
                                                
                                                $MinSpeed = min(array_filter(array_column($SpeedArrayMb, 1)));
                                                $keyMin = array_search($MinSpeed, array_column($SpeedArrayMb, 1));

                                                $MaxSpeed = max(array_filter(array_column($SpeedArrayMb, 1)));
                                                $keyMax = array_search($MaxSpeed, array_column($SpeedArrayMb, 1));
                                                if ( $MinSpeed == $MaxSpeed ){ // BOTH ARE EQUAL
                                                    $CellData .= $SpeedArrayMb[$keyMax][0] . $MaxSpeed . $SpeedArrayMb[$keyMax][2];
                                                }else{ // BOTH ARE DIFFERNT
                                                    $CellData .= $SpeedArrayMb[$keyMin][0] . $MinSpeed . $SpeedArrayMb[$keyMin][2] . " – " . $SpeedArrayMb[$keyMax][0] . $MaxSpeed . $SpeedArrayMb[$keyMax][2];
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
<?php }
} ?>
    </div>
    <div class="table_desc">
        <?php echo $TableDescription; ?>
    </div>
</section>
<script>
    <?php echo dataLayerProductImpressionWrapper($providerImpLoad); ?>
</script>