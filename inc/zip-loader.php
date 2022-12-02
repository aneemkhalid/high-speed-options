<?php
use ZipSearch\ProviderSearchController as ProviderSearchController;
use ZipSearch\BDAPIConnection as BDAPIConnection;
use ZipSearch\ProvidersDBConnection as ProvidersDBConnection;


//get params for display
$url = $_SERVER["HTTP_REFERER"];
$parts = parse_url($url);
//error_log(print_r($parts, TRUE));

if(!$zip_qual) {
	parse_str($parts['query'], $query);
	(isset($query['zip'])) ? $zipcode = $query['zip'] : $zipcode = false;
	(isset($query['type']) && $query['type']) ? $type = $query['type'] : $type = 'internet';
}

$internet_active='';$tv_active='';$bundle_active='';$internet_show='';$tv_show='';$bundle_show='';
if ($type == 'internet'){
	$internet_active = 'active dataLayer-sent';
	$internet_show = 'show';
} elseif($type == 'tv'){
	$tv_active = 'active dataLayer-sent';
	$tv_show = 'show';
} elseif ($type == 'bundle'){
	$bundle_active = 'active dataLayer-sent';
	$bundle_show = 'show';
}
$zip_arr=[];
if ($is_city){
	$zip_arr = (new ProvidersDBConnection())->getZipsByCity($city, $state);
	if ($is_programmatic_city_page && !$zipcode){
		//grab middle zipcode in the zip array if programmatic city page doesnt have one in the url
		if (count($zip_arr) == 1){
			$zipcode = $zip_arr[0];
		} else {
			$zip_arr_index = round(count($zip_arr)/2);
			$zipcode = $zip_arr[$zip_arr_index];
		}
		$zipcode = trim($zipcode, '\'');
	}
}
$args = [
	'zipcode' => $zipcode,
	'is_city' => $is_city,
	'zip_arr' => $zip_arr,
	'provider_id' => $provider_id,
	'city' => $city,
	'state' => $state,
	'is_programmatic_city_page' => $is_programmatic_city_page
];

$results_arr = (new ProviderSearchController())->getAllProviders($args);
//error_log(print_r($results_arr, TRUE));
// if ($is_city){
// 	$zip_arr = (new ProvidersDBConnection())->getZipsByCity($city, $state);
// }

//$results_arr = [];
$counter = 1;

//dataLayer info
$counterInternet = 0;
$counterTV = 0;
$counterBundle = 0;

$zipSearchInternet;
$zipSearchTV;
$zipSearchBundle;

//return different none found text depending on whether it's a programmatic city or top city
if ($is_programmatic_city_page){
	$zipcode = str_pad($zipcode, 5, '0', STR_PAD_LEFT);
	$no_results = $zipcode; 
} elseif ($is_city) {
	$no_results = $city.', '.$state;
} elseif ($state) {
	$no_results = $state;
} else {
	$zipcode = str_pad($zipcode, 5, '0', STR_PAD_LEFT);
	$no_results = $zipcode;
}

//Get ZIP options from Theme Settings
$zip_settings = get_field('zip_search', 'options');
$zip_pricing = $zip_settings['show_pricing'];

$check = get_template_directory_uri() . '/images/check.svg';
//return different none found text if zip qualifier
if($zip_qual) {
	$city_decode = json_decode($city_data);
	//error_log(print_r($city_decode, TRUE));
	$no_results = ucwords($city_decode->city).', '. $city_decode->state;
}

function dataLayerProductImpressionWrapperWZipCode($dataLayerZipCode, $dataLayerInner ) {   
    
    return "dataLayer.push({
                'event': 'productImpressions',
                'zipCode' : '".$dataLayerZipCode."',  
                'ecommerce': {
                'currencyCode': 'USD',
                'impressions': [
                   " . $dataLayerInner . "
                ]
                }
            });";
    
}

function zipSearchLoadIndv($provider, $zipSearchVariant, $zipSearchCounter, $zipSearchCategory) {
    
   return "{
         'name': '".$provider['name']." ".$zipSearchCategory."',   
         'id': '".$provider['id']."',
         'price': '00.01',
         'brand': '".$provider['name']."',
         'category': '".$zipSearchCategory."',
         'variant': '".$zipSearchVariant['text']."', 
         'list': 'Search Results',
         'position': ".$zipSearchCounter."
       },";
};

function zipSearchProdClick($zipcode, $provider, $zipSearchVariant, $zipSearchCounter,  $zipSearchCategory ) {
      return "dataLayer.push({
                  'event': 'productClick',
                  'zipCode' : '".$zipcode."',
                  'ecommerce': {
                    'click': {
                      'actionField': {'list': 'Search Results'},     
                      'products': [{
                        'name': '".$provider['name']." ".$zipSearchCategory."',     
                        'id': '".$provider['id']."',
                        'price': '00.01',
                        'brand':  '".$provider['name']."',
                        'category': '".$zipSearchCategory."',
                        'variant': '".$zipSearchVariant['text']."', 
                        'position': ".$zipSearchCounter."
                       }]
                     }
                   }
               })";
    
}

//Check if zip qual and has results otherwise return null
if(($zip_qual && !empty($results_arr['internet'])) || !$zip_qual):
//Start return object
ob_start();

	if($zip_pricing) : 
		include get_template_directory() . '/template-parts/zip-search.php'; 
	else : 
	//NEW ZIP SEARCH STYLING WITHOUT PRICING
		include get_template_directory() . '/template-parts/new-zip-search.php';
	endif;

	$html = ob_get_contents();

else :
	$html = null;
endif;

ob_end_clean();

?>
