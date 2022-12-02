<?php
/**
 * Functions for building tables
 *
 * @package HSO
 */

//Desktop table

function buildDesktopTable($tableID, $tableClasses, $tableData) {
    
    $tableHTML;
    
    $tableHTML ='<table id="'.$tableID.'" class="'.$tableClasses.'">';
    
    $headers = '<thead><tr>';
    
    //do header loop first - use the first array since all should be same
    foreach ($tableData[0] as $row => $innerArray) {
        
        if (is_array($innerArray)) {
            
            $headers .= "<th>".$innerArray['header']."</th>";  
        }    
    }
    $headers = $headers . '</tr></thead>';
 
    $innerRowHTML = '<tbody>';
    
    // than do a loop through the cell data stuff
    foreach ($tableData as $row => $innerArray) {
        
        //do the start of the row html
    
       $innerRowHTML .= "<tr>";
       foreach($innerArray as $innerRow => $value){
           //the inner
            if (is_array($value)) {
             $innerRowHTML .= "<td>".$value['cell_data']."</td>";

            }
        }
           
        //do the end row of html
         $innerRowHTML .= "</tr>";
          
        };
    //build table
    $innerRowHTML = $innerRowHTML . '</tbody>';
    
    $tableHTML .= $headers; 
    
    $tableHTML .= $innerRowHTML;
    
    $tableHTML = $tableHTML . '</table>';
    
    return $tableHTML;
}


//desktop table with left headers
function buildDesktopTableLeftHeaders($tableID, $tableClasses, $tableData) {
    
    $arrayCounter = 0;
    
    $tableHTML;
    
    $tableHTML ='<table id="'.$tableID.'" class="'.$tableClasses.'">';
    
    $headers = '<thead><tr>';

   $tableDataRework = [];    

    $innerRowHTML = '<tbody>';

    //maybe should have arrays come in just as we need them?!?!
    foreach ($tableData as $row3 => $innerArray3) {
        unset($tableData[$row3]['split_out']);    
        array_push($tableDataRework,array_values($tableData[$row3]));

    }   

    // than do a loop through the cell data stuff
    foreach ($tableDataRework as $row => $innerArray) {
        
        //do the start of the row html
        $arrayCounter=0;

        $innerRowHTML .= "<tr><th>".$innerArray[$row]['header']."</th>";

         for ($i = 0; $i < count($innerArray); $i++) {
            //create cells of table 
            $innerRowHTML .= "<td>".$tableDataRework[$arrayCounter][$row]['cell_data']."</td>";
            //counter jump since we are jumping through array 
            $arrayCounter++;
            }    
        
        
        //do the end row of html
         $innerRowHTML .= "</tr>";
          
        };
    
    //build rest of table
    $innerRowHTML = $innerRowHTML . '</tbody>';
    
    $tableHTML .= $innerRowHTML;
    
    $tableHTML = $tableHTML . '</table>';
    
    return $tableHTML;
}

//Mobile tables

function buildMobileTable($tableID, $tableClasses, $tableData) {
    
    $tableHTML;
    
    $tableHTML ='<table id="'.$tableID.'" class="'.$tableClasses.'">';
 
    $innerRowHTML = '<tbody>';
    
    // than do a loop through the cell data stuff
    foreach ($tableData as $row => $innerArray) {
        
        //do the start of the row html

       foreach($innerArray as $innerRow => $value){
           //the inner
            if (is_array($value)) {
                
          
                 if ($innerRow === array_key_first($innerArray)) {
                       $innerRowHTML .=  '<tr class="top-provider-row">';    
                 } else {
                       $innerRowHTML .= "<tr>";    
                 }


                if  ($innerRow == 'cta')   {
                   $innerRowHTML .= '<td class="text-center" colspan="2">'.$value['cell_data'].'</td>';
                } else {   

                 $innerRowHTML .= "<th>".$value['header']."</th><td>".$value['cell_data']."</td>";
                }       
             //do the end row of html
             $innerRowHTML .= "</tr>";    
            }
        }
             
       
          
        };
    //build table

    $innerRowHTML = $innerRowHTML . '</tbody>';
    
    $tableHTML .= $headers; 
    
    $tableHTML .= $innerRowHTML;
    
    $tableHTML = $tableHTML . '</table>';
    
    return $tableHTML;
}


//functions for internet table
function splitMaxDownUpload($key_array) {
    
    foreach($key_array as $key => $IndSpeed){
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
        return $SpeedArray[$keyMax][0] . $MaxSpeed . $SpeedArray[$keyMax][2];
    }else{ // BOTH ARE DIFFERNT
        return $SpeedArray[$keyMin][0] . $MinSpeed . $SpeedArray[$keyMin][2] . " – " . $SpeedArray[$keyMax][0] . $MaxSpeed . $SpeedArray[$keyMax][2];
    }

};

function yesNoDataSpeed($key_array) {
      

          $DataCapsInfo = ''; $YesAns = 0; $NoAns = 0;
          foreach ( $key_array as $Ans ){
              if ( strtolower($Ans) == "yes" ){
                  $YesAns++;
              }else{
                  $NoAns++;
              }
              if ( $YesAns >= 1 ){
                  $DataCapsInfo = 'Yes';
              }else{
                  $DataCapsInfo = 'No';
              }
          }
          return $DataCapsInfo;
    
}

function minMaxTable($MinVal, $MaxVal) {

    if ($MinVal == $MaxVal ){
       return  $MaxVal;
    } else if ($MinVal == ""){
        return $MaxVal;
    } else if ($MaxVal == ""){
        return $MinVal;
    } else {
        return $MinVal . ' – ' . $MaxVal;
    }
}

