<?php

use ZipSearch\ProviderSearchController as ProviderSearchController;
use ZipSearch\PostgreSQLConnection as PostgreSQLConnection;
use ZipSearch\BDAPIConnection as BDAPIConnection;
use ZipSearch\ProvidersDBConnection as ProvidersDBConnection;
use ZipSearch\VaultTask as VaultTask;
use Dotenv\Dotenv as DotEnv;

/**
* Shortcodes for City Pages
*/

function city_population_shortcode() { 
 	
	global $city;
	global $state;
	global $wpdb;
	global $city_population;
	if ($city_population){
		return $city_population;
	}
	$use_population_table = false;
    //get geoid based on city
    $table_name = $wpdb->prefix . "city_to_geoid";
    $place_name = $city.', '.$state;
    $geoid_query = "SELECT geoid FROM $table_name WHERE name = '$place_name' AND type='place' LIMIT 1";
    $row = $wpdb -> get_results($geoid_query);
    if (!empty($row)){

    	$geoid = $row[0]->geoid;
    	$geoid = str_pad($geoid, 7, '0', STR_PAD_LEFT);
		$app_token = 'aK7RjsSGrARQEmw9UHhhilmG5';
		$api_endpoint = "https://opendata.fcc.gov/resource/ktav-pdj7.json?type=place&id=$geoid&speed=25&tribal_non=N&tech=s";
	    $curl = curl_init($api_endpoint);

	    $curl_data = [
	        '$limit' => 5000,
	    ]; 
	    $curl_data = json_encode($curl_data);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	            'Content-Type: application/json', 
	            'X-App-Token: '.$app_token,
	            'Accept: application/json',
	        )                                                                               
	    );                                                                    
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                            
	    $return_json = curl_exec($curl);
	    $return_array = json_decode($return_json, true);

	    $population = 0;

	    foreach($return_array as $item){
	    	
	    	if ($item['tech'] == 's'){
	    		$population = $population + $item['has_0'] + $item['has_1'] + $item['has_2'] + $item['has_3more'];
	    	}
	    }
	    if (!$population){
	    	$use_population_table = true;
	    }
    	
    } else {
    	$use_population_table = true;
    }
    if ($use_population_table){
    	$db = new ProvidersDBConnection();
    	global $post;
    	$args = array(
    		'numberposts'   => 1,
	    	'post_type' => 'locations',
			'meta_query' => array(
			   array(
			       'key' => 'abbreviation',
			       'value' => $state,
			       'compare' => '=',
			   )
			)
		);
		$state_post = get_posts($args);
		$state_long = get_the_title($state_post[0]->ID);
		$population = $db->getCityPopulation($city, $state_long);
    }
    //format population
    if ($population != 'N/A'){
    	if (strlen($population) >= 7){
	    	$population = round(($population/1000000), 1).' million';
	    } else {
	    	$population = number_format((int)$population, 0, '', ',');
	    }
    }
    $city_population = $population;
    return $population;
} 
// register shortcode
add_shortcode('city_population_speeds', 'city_population_shortcode');

function state_population_speeds_shortcode($attrs) { 
 	
	global $state;
	global $state_long;
	global $wpdb;
	//global $city_population;
	// if ($city_population){
	// 	return $city_population;
	// }
	//get geoid based on city
	$table_name = $wpdb->prefix . "city_to_geoid";
	$geoid_query = "SELECT geoid FROM $table_name WHERE name = '$state_long' AND type='state' LIMIT 1";
	$row = $wpdb -> get_results($geoid_query);

	if (!empty($row)){

		$geoid = $row[0]->geoid;
		$geoid = sprintf("%02d", $geoid);
		$speed = $attrs['speed'];

		//echo $speed;

		$app_token = 'aK7RjsSGrARQEmw9UHhhilmG5';
		$api_endpoint = 'https://opendata.fcc.gov/resource/ktav-pdj7.json?type=state&id='.$geoid.'&tribal_non=N&$where=speed='.$speed.'&tech=acfosw';
		$curl = curl_init($api_endpoint);

		$curl_data = [
				'$limit' => 5000,
		]; 
		$curl_data = json_encode($curl_data);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json', 
						'X-App-Token: '.$app_token,
						'Accept: application/json',
				)                                                                               
		);                                                                    
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                            
		$return_json = curl_exec($curl);
		$return_array = json_decode($return_json, true);

		// echo '<pre>';
		// print_r($return_array);
		// echo '</pre>';

		$population = 0;
		$total_population = 0;

		foreach($return_array as $item){
			$population = $population + $item['has_1'] + $item['has_2'] + $item['has_3more'];
			$total_population = $total_population + $item['has_0'] + $item['has_1'] + $item['has_2'] + $item['has_3more'];
		}

		//echo $population . '<br/>';
		//echo $total_population . '<br/>';
		//return $population;

		//$db = new ProvidersDBConnection();
		//return $state_long;
		//$total_population = $db->getCityPopulation($state_long, $state_long);

		//return $total_population;
	}

	//Calculate Percentage
	$percent = ($population/$total_population)*100;
	return round($percent,2) . '%';
} 
// register shortcode
add_shortcode('state_population_speeds', 'state_population_speeds_shortcode');

function state_tech_access_shortcode($attrs) { 
 	
	global $state;
	global $state_long;
	global $wpdb;
	//global $city_population;
	// if ($city_population){
	// 	return $city_population;
	// }
	//get geoid based on city
	$table_name = $wpdb->prefix . "city_to_geoid";
	$geoid_query = "SELECT geoid FROM $table_name WHERE name = '$state_long' AND type='state' LIMIT 1";
	$row = $wpdb -> get_results($geoid_query);

	if (!empty($row)){

		$geoid = $row[0]->geoid;
		$geoid = sprintf("%02d", $geoid);
		$tech = $attrs['tech'];

		//echo $speed;

		$app_token = 'aK7RjsSGrARQEmw9UHhhilmG5';
		$api_endpoint = 'https://opendata.fcc.gov/resource/ktav-pdj7.json?type=state&id='.$geoid.'&tribal_non=N&speed=0.2&tech='.$tech;
		$curl = curl_init($api_endpoint);

		$curl_data = [
				'$limit' => 5000,
		]; 
		$curl_data = json_encode($curl_data);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json', 
						'X-App-Token: '.$app_token,
						'Accept: application/json',
				)                                                                               
		);                                                                    
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                            
		$return_json = curl_exec($curl);
		$return_array = json_decode($return_json, true);

		// echo '<pre>';
		// print_r($return_array);
		// echo '</pre>';

		$population = 0;
		$total_population = 0;
		foreach($return_array as $item){
			$population = $population + $item['has_1'] + $item['has_2'] + $item['has_3more'];
			$total_population = $total_population + $item['has_0'] + $item['has_1'] + $item['has_2'] + $item['has_3more'];
		}

	}

	//Calculate Percentage
	$percent = ($population/$total_population)*100;
	return round($percent, 2) . '%';
} 
// register shortcode
add_shortcode('state_tech_access', 'state_tech_access_shortcode');

//top two internet providers for a given city
function top_two_internet_providers_shortcode() { 

	if (is_singular('locations')):

		global $city;
		global $state;
		global $wpdb;
		global $top_internet_provider_in_city;

		$db = new ProvidersDBConnection();
		$tract_arr = $db->getTractsByCity($city, $state);
	    $tract_where = implode(', ', $tract_arr);
	    $provider_table_name = $wpdb->prefix . "broadband_hso";
	    $sql = "SELECT COUNT(hso_provider) as hso_provider_count, hso_provider FROM $provider_table_name WHERE census_block_fips_code_11 IN ($tract_where) AND state = '$state' GROUP BY hso_provider ORDER BY hso_provider_count DESC";
	    $fcc_provider_return = $wpdb -> get_results($sql);

    	$providers_arr_new = [];
    
    	add_filter('posts_where', 'my_posts_where');

    	for($i=0;$i<count($fcc_provider_return);$i++){
		    $providers_arr_new[] = $fcc_provider_return[$i]->hso_provider;
		}
		// args
		$args = array(
		    'numberposts'   => -1,
		    'post_type'     => 'provider',
		    'suppress_filters' => false,
		    'fields'        => 'ids',
		    'meta_query'    => array(
		        array(
		            'key'       => 'possible_provider_names_$_name',
		            'compare'   => 'IN',
		            'value'     => $providers_arr_new,
		        ),
		    )
		);
		$wp_backend_providers = get_posts($args);
		$all_possible_providers = [];
		$wp_backend_providers_count = count($wp_backend_providers);
		foreach($wp_backend_providers as $wp_backend_provider){
			$single_prov_arr = get_field('possible_provider_names', $wp_backend_provider);
			$all_possible_providers = array_merge($all_possible_providers, $single_prov_arr);
		}
		$all_possible_providers = array_column($all_possible_providers, 'name');
		$provider1 = $provider2 = '';
		//remove all providers from fcc prov return that arent in wp backend
		foreach($fcc_provider_return as $key => $fcc_provider){
			if (!in_array($fcc_provider->hso_provider, $all_possible_providers)){
				unset($fcc_provider_return[$key]);
			}
		}
		$fcc_provider_return = array_values($fcc_provider_return);

		foreach($wp_backend_providers as $wp_backend_provider){
			$provider_arr = get_field('possible_provider_names', $wp_backend_provider);
			foreach($provider_arr as $prov){
				if ($prov['name'] == $fcc_provider_return[0]->hso_provider){
					$provider1 = $provider_arr[0]['name'];
				}
				if (isset($fcc_provider_return[1])){
					if ($prov['name'] == $fcc_provider_return[1]->hso_provider){
						$provider2 = $provider_arr[0]['name'];
					}
				}
			}
		}

	    if ($wp_backend_providers_count < 2){
	    	$return = $provider1.' is the largest provider in '. do_shortcode('[city_name]');
	    } else {
	    	$return = $provider1.' and '. $provider2.' are the largest providers in the area, however, there are a total of '. do_shortcode('[provider_count]').' <a href="/internet">internet providers</a> in '. do_shortcode('[city_name]');
	
	    }
	    $top_internet_provider_in_city = $provider1;
	    return $return;
	endif;     
} 
add_shortcode('top_two_internet_providers', 'top_two_internet_providers_shortcode');

function top_five_internet_providers_shortcode() { 

	if (is_singular('locations')):

		global $state;
		global $wpdb;

		$db = new ProvidersDBConnection();
		$provider_table_name = $wpdb->prefix . "state_provider";
		//$sql = "SELECT COUNT(hso_provider) as hso_provider_count, hso_provider, AVG(max_advertised_downstream_speed_mbps) as avg_dl FROM $provider_table_name WHERE census_block_fips_code_11 IN ($tract_where) AND state = '$state' GROUP BY hso_provider ORDER BY hso_provider_count DESC";
		$sql = "SELECT top_five FROM $provider_table_name WHERE stateabbr = '$state'";
		$top = $wpdb -> get_results($sql);

		$five = json_decode($top[0]->top_five);
		//var_dump($five);
		$return = '<pre>'
		       . print_r($five, true)
					 . '</pre>';

		return $return;

	endif;     
} 
add_shortcode('top_five_internet_providers', 'top_five_internet_providers_shortcode'); 


//top internet provider for a given city
function top_internet_provider_shortcode() { 

	if (is_singular('locations')):

		global $top_internet_provider_in_city;
		if ($top_internet_provider_in_city != ''){
			return $top_internet_provider_in_city;
		}
		global $city;
		global $state;
		global $wpdb;

		$db = new ProvidersDBConnection();
		$tract_arr = $db->getTractsByCity($city, $state);
	    $tract_where = implode(', ', $tract_arr);
	    $provider_table_name = $wpdb->prefix . "broadband_hso";
	    $sql = "SELECT COUNT(hso_provider) as hso_provider_count, hso_provider FROM $provider_table_name WHERE census_block_fips_code_11 IN ($tract_where) AND state = '$state' GROUP BY hso_provider ORDER BY hso_provider_count DESC";
	    $fcc_provider_return = $wpdb -> get_results($sql);

    	$providers_arr_new = [];
    
    	add_filter('posts_where', 'my_posts_where');

    	for($i=0;$i<count($fcc_provider_return);$i++){
		    $providers_arr_new[] = $fcc_provider_return[$i]->hso_provider;
		}

		// args
		$args = array(
		    'numberposts'   => -1,
		    'post_type'     => 'provider',
		    'suppress_filters' => false,
		    'fields'        => 'ids',
		    'meta_query'    => array(
		        array(
		            'key'       => 'possible_provider_names_$_name',
		            'compare'   => 'IN',
		            'value'     => $providers_arr_new,
		        ),
		    )
		);
		$wp_backend_providers = get_posts($args);
		$all_possible_providers = [];
		foreach($wp_backend_providers as $wp_backend_provider){
			$single_prov_arr = get_field('possible_provider_names', $wp_backend_provider);
			$all_possible_providers = array_merge($all_possible_providers, $single_prov_arr);
		}
		$all_possible_providers = array_column($all_possible_providers, 'name');
		//remove all providers from fcc prov return that arent in wp backend
		foreach($fcc_provider_return as $key => $fcc_provider){
			if (!in_array($fcc_provider->hso_provider, $all_possible_providers)){
				unset($fcc_provider_return[$key]);
			}
		}
		$fcc_provider_return = array_values($fcc_provider_return);

		foreach($wp_backend_providers as $wp_backend_provider){
			$provider_arr = get_field('possible_provider_names', $wp_backend_provider);
			foreach($provider_arr as $prov){
				if ($prov['name'] == $fcc_provider_return[0]->hso_provider){
					$top_internet_provider_in_city = $provider_arr[0]['name'];
					return $top_internet_provider_in_city;
				}
			}
		}
	    
	endif;     
} 
add_shortcode('top_internet_provider', 'top_internet_provider_shortcode'); 

function internet_types_availability_shortcode() { 
 	
	global $city;
	global $state;
	global $wpdb;
	global $city_population;
	$return_text = '';
	$generic_text = false;

    //get geoid based on city
    $table_name = $wpdb->prefix . "city_to_geoid";
    $place_name = $city.', '.$state;
    $geoid_query = "SELECT geoid FROM $table_name WHERE name = '$place_name' AND type='place' LIMIT 1";
    $row = $wpdb -> get_results($geoid_query);
    //if we cant find coverage info for this city just return generic text
    if (empty($row)){
    	$generic_text = true;
    } else {
    	$geoid = $row[0]->geoid;
	    $geoid = str_pad($geoid, 7, '0', STR_PAD_LEFT);
		$app_token = 'aK7RjsSGrARQEmw9UHhhilmG5';
		$api_endpoint = "https://opendata.fcc.gov/resource/ktav-pdj7.json?type=place&id=$geoid&speed=25&tribal_non=N";
	    $curl = curl_init($api_endpoint);

	    $curl_data = [
	        '$limit' => 5000,
	    ]; 
	    $curl_data = json_encode($curl_data);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	            'Content-Type: application/json', 
	            'X-App-Token: '.$app_token,
	            'Accept: application/json',
	        )                                                                               
	    );                                                                    
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                            
	    $return_json = curl_exec($curl);
	    $return_array = json_decode($return_json, true);

	    $dsl_covered = $dsl_not_covered = $cable_covered = $cable_not_covered = $satellite_covered = $satellite_not_covered = $wireless_covered = $wireless_not_covered = $fiber_covered = $fiber_not_covered = 0;
	    $type_avail_arr = [];
	    foreach($return_array as $item){
	    	if ($item['tech'] == 'a'){
	    		$dsl_covered = $dsl_covered + $item['has_1'] + $item['has_2'] + $item['has_3more'];
	    		$dsl_not_covered = $dsl_not_covered + $item['has_0'];
	    		if ($dsl_covered >=1){
	    			$type_avail_arr['dsl'] = 'dsl';
	    		}
	    	}
	    	if ($item['tech'] == 'c'){
	    		$cable_covered = $cable_covered + $item['has_1'] + $item['has_2'] + $item['has_3more'];
	    		$cable_not_covered = $cable_not_covered + $item['has_0'];
	    		if ($cable_covered >=1){
	    			$type_avail_arr['cable'] = 'cable';
	    		}
	    	}
	    	if ($item['tech'] == 's'){
	    		$satellite_covered = $satellite_covered + $item['has_1'] + $item['has_2'] + $item['has_3more'];
	    		$satellite_not_covered = $satellite_not_covered + $item['has_0'];
	    		if ($satellite_covered >=1){
	    			$type_avail_arr['satellite'] = 'satellite';
	    		}
	    	}
	    	if ($item['tech'] == 'w'){
	    		$wireless_covered = $wireless_covered + $item['has_1'] + $item['has_2'] + $item['has_3more'];
	    		$wireless_not_covered = $wireless_not_covered + $item['has_0'];
	    		if ($wireless_covered >=1){
	    			$type_avail_arr['wireless'] = 'wireless';
	    		}
	    	}
	    	if ($item['tech'] == 'f'){
	    		$fiber_covered = $fiber_covered + $item['has_1'] + $item['has_2'] + $item['has_3more'];
	    		$fiber_not_covered = $fiber_not_covered + $item['has_0'];
	    		if ($fiber_covered >=1){
	    			$type_avail_arr['fiber'] = 'fiber';
	    		}
	    	}
	    }
	    if (count($type_avail_arr) < 2){
	    	$generic_text = true;
	    } else {
	    	$coverage_compare_arr = [];
		    $total = $satellite_covered + $satellite_not_covered;
		    $population = $total/2;

		    //format population
		    if (strlen($population) >= 7){
		    	$population = round(($population/1000000), 1).' million';
		    } else {
		    	$population = number_format($population, 0, '', ',');
		    }
		    $city_population = $population;

		    $avail_list = '';
		    foreach ($type_avail_arr as $type){
		    	$coverage_compare_arr[$type] = ${"$type"."_covered"} / $total;
		    	//check which are present in avail array to display in list later
		    	switch ($type) {
				    case 'fiber':
				        $avail_list .= '<li><b>Fiber:</b> Fiber internet offers users symmetrical speeds at affordable prices. While a few providers deliver up to 10 Gbps, most fiber plans come with speeds up to 1000 Mbps.</li>';
				        break;
				    case 'cable':
				        $avail_list .= '<li><b>Cable:</b> A better alternative to DSL, cable internet delivers service over existing TV lines. This provides wide availability and potential download speeds up to 1000 Mbps.</li>';
				        break;
				    case 'dsl':
				        $avail_list .= '<li><b>DSL:</b> DSL internet runs on existing telephone lines. It’s more available than cable or fiber internet and can deliver download speeds up to 150 Mbps in select areas.</li>';
				        break;
				    case 'satellite':
				        $avail_list .= '<li><b>Satellite: </b>Satellite internet is available virtually anywhere in the US, with speeds ranging from 25 to 150 Mbps. This makes it a great option for those living in more remote areas.</li>';
				        break;
				    case 'wireless':
				        $avail_list .= '<li><b>Fixed-Wireless: </b>Fixed-wireless internet delivers service using radio towers. It typically offers lower latency than satellite internet but often comes with a data cap.</li>';
				        break;
				}
		    }
		    arsort($coverage_compare_arr);

		    $unique_conn_arr = array_keys($coverage_compare_arr);

		    $spos = array_search('satellite', $unique_conn_arr);

		    $unique_conn_arr_count = count($unique_conn_arr);

		    for ($i=0;$i<$unique_conn_arr_count;$i++){
		    	if ($unique_conn_arr[$i] == 'dsl'){
		    		$unique_conn_arr[$i] = 'DSL';
		    	}
		    }

		    $unique_conn_list = join(' and ', array_filter(array_merge(array(join(', ', array_slice($unique_conn_arr, 0, -1))), array_slice($unique_conn_arr, -1)), 'strlen'));
		    if (isset($unique_conn_arr[$spos])){
		    	unset($unique_conn_arr[$spos]);
		    }
		    $unique_conn_arr = array_values($unique_conn_arr);
		    if (isset($coverage_compare_arr['satellite'])){
		    	unset($coverage_compare_arr['satellite']);
		    }
		    		  
		    $coverage_compare_arr = array_values($coverage_compare_arr);

		    if (count($unique_conn_arr) < 2){
		    	$return_text .= '<p>A wide variety of internet connections are available in '.$city.', '.$state.' including '.$unique_conn_list.'. Overall, '.$unique_conn_arr[0].' service is the primary type of internet in '.$city.', with nearly '.round((float)($coverage_compare_arr[0]) * 100 ) . '% coverage.</p>';
		    } else {
		    	$return_text .= '<p>A wide variety of internet connections are available in '.$city.', '.$state.' including '.$unique_conn_list.'. Overall, '.$unique_conn_arr[0].' service is the primary type of internet in '.$city.', with nearly '.round((float)($coverage_compare_arr[0]) * 100 ) . '% coverage. Yet, '.$unique_conn_arr[1].' is starting to grow, with '.round((float)($coverage_compare_arr[1]) * 100 ) . '% coverage across the city.</p>';
		    }
	    }
	    
    }

    if ($generic_text){
    	$return_text .= '<p>Depending on where you live, you might have more options than you think. There are five main types of internet.</p>';
		$avail_list = '
		<li><b>Fiber:</b> Fiber internet offers users symmetrical speeds at affordable prices. While a few providers deliver up to 10 Gbps, most fiber plans come with speeds up to 1000 Mbps.</li>
        <li><b>Cable:</b> A better alternative to DSL, cable internet delivers service over existing TV lines. This provides wide availability and potential download speeds up to 1000 Mbps.</li>
        <li><b>DSL:</b> DSL internet runs on existing telephone lines. It’s more available than cable or fiber internet and can deliver download speeds up to 150 Mbps in select areas.</li>
        <li><b>Satellite: </b>Satellite internet is available virtually anywhere in the US, with speeds ranging from 25 to 150 Mbps. This makes it a great option for those living in more remote areas.</li>
        <li><b>Fixed-Wireless: </b>Fixed-wireless internet delivers service using radio towers. It typically offers lower latency than satellite internet but often comes with a data cap.</li>';
    }
    
    $return_text .= '<ul>';
    if ($avail_list){
    	$return_text .= $avail_list;
    }
    $return_text .= '</ul>';

    return $return_text;
} 
// register shortcode
add_shortcode('internet_types_availability', 'internet_types_availability_shortcode');


//shortcode to display provider count for a given city
function provider_count_shortcode() { 

	if (is_singular('locations')):
		global $locations_provider_count;
		if ($locations_provider_count != ''){
			return $locations_provider_count;
		}

		global $city;
		global $state;
		global $wpdb;
		$db = new ProvidersDBConnection();
		$tract_arr = $db->getTractsByCity($city, $state);
	    $tract_where = implode(', ', $tract_arr);
	    $provider_table_name = $wpdb->prefix . "broadband_hso";
	    $sql = "SELECT COUNT(DISTINCT hso_provider) as provider_count FROM $provider_table_name WHERE census_block_fips_code_11 IN ($tract_where) AND state = '$state'";
	    $provider_count = $wpdb -> get_results($sql);
	    $locations_provider_count = $provider_count[0]->provider_count;
	    return $locations_provider_count;
	endif;     
} 
add_shortcode('provider_count', 'provider_count_shortcode');

//shortcode to display provider count for a given state
function state_provider_count_shortcode() { 

	if (is_singular('locations')):
		global $locations_provider_count;
		if ($locations_provider_count != ''){
			return $locations_provider_count;
		}

		global $state;
		global $wpdb;
		$db = new ProvidersDBConnection();
		//return $state;
		//$tract_arr = $db->getTractsByState($state);

		//return $tracts_arr;
		//$tract_where = implode(', ', $tract_arr);
		$provider_table_name = $wpdb->prefix . "state_provider";
		//$sql = "SELECT COUNT(DISTINCT hso_provider) as provider_count FROM $provider_table_name WHERE census_block_fips_code_11 IN ($tract_where) AND state = '$state'";
		$sql = "SELECT provider_count FROM $provider_table_name WHERE stateabbr = '$state'";
		$provider_count = $wpdb -> get_results($sql);
		// echo '<pre>';
		// print_r($provider_count);
		// echo '</pre>';
		// return $provider_count;
		$locations_provider_count = $provider_count[0]->provider_count;
		return $locations_provider_count;
	endif;     
} 
add_shortcode('state_provider_count', 'state_provider_count_shortcode'); 

//shortcode to display the max residential download speed for a city
function max_residential_download_speed_in_city_shortcode() { 
	$highest_download_speed_html = '<span class="highest_download_speed_shortcode"></span>';
	return $highest_download_speed_html;
} 
add_shortcode('max_residential_download_speed_in_city', 'max_residential_download_speed_in_city_shortcode'); 

//shortcode to display the max residential download speed for a city
function max_residential_download_speed_provider_in_city_shortcode() { 
	$highest_download_speed_provider_html = '<span class="highest_download_speed_provider_shortcode"></span>';
	return $highest_download_speed_provider_html;
} 
add_shortcode('max_residential_download_speed_provider_in_city', 'max_residential_download_speed_provider_in_city_shortcode'); 

function city_name_shortcode() { 
	global $city;
	return $city;
} 
add_shortcode('city_name', 'city_name_shortcode'); 

function state_name_shortcode() { 
	global $state;
	return $state;
} 
add_shortcode('state_name', 'state_name_shortcode'); 

function search_by_zip_shortcode($atts = '') { 
	extract( shortcode_atts( array(
		'text' => '',
	), $atts ) );
	$rand = rand();
	require get_theme_file_path( '/template-parts/zip-search-popup.php' );
	$return = '<a href=# class="zip-popup-btn" data-toggle="modal" data-target="#zipPopupModal-'.$rand.'">'.$text.'</a>';
	// ob_start();
	// require get_theme_file_path( '/template-parts/zip-search-popup.php' );
	// $return .= ob_get_clean();
	return $return;
 
} 
add_shortcode('search_by_zip', 'search_by_zip_shortcode'); 

//upload a csv to the zip uploads DB
function zip_upload_shortcode() { 
	$html ='';
	$html .= '
	<form enctype="multipart/form-data" action="" method="post">
	  <input type="file" id="myFile" name="filename">
	  <input type="submit" name="submit">
	</form>';

	if(isset($_POST["submit"])) {

		global $wpdb;
		$table_name = $wpdb->prefix . "zip_uploads";

		$uploadfile = 'wp-content/themes/wp-highspeedoptions/zip-uploads/'.basename($_FILES['filename']['name']);

		if (move_uploaded_file($_FILES['filename']['tmp_name'], $uploadfile)) {
		    echo "File is valid, and was successfully uploaded.\n";
		    $file = get_template_directory_uri().'/zip-uploads/'.basename($_FILES['filename']['name']);
			$sql="
			    LOAD DATA LOCAL INFILE '$file' INTO TABLE $table_name
			    FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
			    LINES TERMINATED BY '\n'
			    IGNORE 1 LINES
			    (provider_name,dba_name,holding_company_name,holding_company_final,state,technology_code,consumer,max_advertised_downstream_speed_mbps,max_advertised_upstream_speed_mbps,zip_code,connection_type,hso_provider,price);
			    ";
			$query = $wpdb->query($sql);
		} else {
		    echo "File was not successfully uploaded\n";
		}
	}

	return $html;
 
} 
add_shortcode('zip_upload', 'zip_upload_shortcode'); 


/**
* Shortcodes for testing purposes only
*/

function get_data_by_zip($atts = '') { 
	extract( shortcode_atts( array(
		'zipcode' => '',
	), $atts ) );
      
	$db = new ProvidersDBConnection($zipcode);
	//$return = $db->getAllInternetProviderData();
	//$return = $db->getAllTvProviderData();
	// $city = 'Denver';
	// $state ='CO';
	//$zip_arr = $db->getZipsByCity($city, $state);
	$return = $db->getAllZipUploadProviderData();

    //$return = $db->getAllTvProviderDataByCity($zip_arr);
	echo '<pre>';
	print_r($return);
	echo '</pre>';
 
}
// register shortcode
add_shortcode('data_by_zip', 'get_data_by_zip'); 



function mult_api_call($atts = '') { 

	extract( shortcode_atts( array(
		'city' => '',
		'state' => ''
	), $atts ) );
    $db = new ProvidersDBConnection();
	$zip_arr = $db->getZipsByCityWithoutQuotes($city, $state);

	//create the multiple cURL handle
	$auth = (new ProviderSearchController())->get_auth();
	$api_connection = new BDAPIConnection();
    $api = $api_connection->get_api_providers_by_multi_zip($zip_arr, $auth);
    $api = json_encode($api);
    global $wpdb;

    $table_name = $wpdb->prefix . "city_api_call";

    $return = $wpdb->replace( $table_name, 
	   array( 
	    'city'  => $city,
	    'state' => $state,
	    'provider_data'  => $api,
		), 
	   array(
	    '%s', '%s', '%s')
	);
	echo '<pre>';
	print_r($api);
	echo '</pre>';

} 
// register shortcode
add_shortcode('mult_api_call', 'mult_api_call'); 


function api_call_shortcode($atts = '') { 
	extract( shortcode_atts( array(
		'zipcode' => '',
	), $atts ) );
	$auth = (new ProviderSearchController())->get_auth();
	$api_connection = new BDAPIConnection();
    $api = $api_connection->get_api_providers_by_zip($zipcode, $auth);
	echo '<pre>';
	print_r($api);
	echo '</pre>';
 
} 
// register shortcode
add_shortcode('api_call', 'api_call_shortcode'); 

function postgres_call_shortcode() { 
 
	$pdo = new PostgreSQLConnection();
	// create tables and query the table from the
	// database
	// $table = $pdo->getConnectionInfo();
	// echo '<pre>';
	//  print_r($table);
	//  echo '</pre>';

	 $table2 = $pdo->getInternetProvidersByZip(43560);
	echo '<pre>';
	 print_r($table2);
	 echo '</pre>';

	 $table3 = $pdo->getTable('provider');
	 echo '<pre>';
	 print_r($table3);
	 echo '</pre>';
 
} 
// register shortcode
add_shortcode('postgres_call', 'postgres_call_shortcode'); 

function zip_search_shortcode($atts = '') { 
	extract( shortcode_atts( array(
		'zipcode' => '',
	), $atts ) );
	$plan_arr = (new ProviderSearchController())->getAllProviders($zipcode);

	echo '<pre>';
	 print_r($plan_arr);
	 echo '</pre>';
 
} 
// register shortcode
add_shortcode('zip_search', 'zip_search_shortcode'); 

function auth_shortcode() { 
 
 	$task = new VaultTask();
 	$task->unseal();

 	$response = $task->get_auth();

	echo '<pre>';
	print_r($response);
	echo '</pre>';


	// echo '<pre>';
	// print_r($return);
	// echo '</pre>'; 

 
} 
// register shortcode
add_shortcode('auth', 'auth_shortcode'); 


function zip_to_fip_api_shortcode() { 
set_time_limit(0);

// $city = 'Austin';
// $state = 'TX';

// $city_to_zips = get_template_directory_uri().'/uscities.csv';
// $zip_to_tract = get_template_directory_uri().'/zip_to_tract.csv';

// $tract_arr = [];
 	
// //get all zip codes for a given city
// if (($handle = fopen($city_to_zips, "r")) !== FALSE) {
//     while (($row = fgetcsv($handle, 500, ",")) !== FALSE) {
//     	if ($row[1] === $city && $row[2] === $state){
//         	$zips = $row[15];
//         	$zips = explode(' ',$zips);
//         	// echo '<pre>';
//         	// print_r($zips);
//         	// echo '</pre>';
//         }
//     }
//     fclose($handle);
// }
// //get all tracts for all zips
// if (($handle = fopen($zip_to_tract, "r")) !== FALSE) {
//     while (($row = fgetcsv($handle, 500, ",")) !== FALSE) {
//     	if (in_array($row[0], $zips)){
//         	$tract_arr[] = $row[1];
//         }
//     }
//     fclose($handle);
// }
// $tract_arr = array_unique($tract_arr);
// $tract_arr = implode(', ', $tract_arr);
// echo '<pre>';
// print_r($tract_arr);
// echo '</pre>';

//$tract_arr = array("fips LIKE '08005080300%'", "fips LIKE '08031006813%'", "fips LIKE '08005087100%'", "fips LIKE '08031007006%'", "fips LIKE '08005080500%'", "fips LIKE '08005084700%'", "fips LIKE '08005080600%'", "fips LIKE '08005080400%'", "fips LIKE '08005083600%'", "fips LIKE '08005083900%'", "fips LIKE '08005083800%'", "fips LIKE '08005081600%'", "fips LIKE '08005081500%'", "fips LIKE '08005006854%'", "fips LIKE '08005081400%'", "fips LIKE '08005082300%'", "fips LIKE '08005082400%'", "fips LIKE '08059012060%'", "fips LIKE '08059012052%'", "fips LIKE '08005005620%'", "fips LIKE '08059012054%'", "fips LIKE '08059012042%'", "fips LIKE '08059012059%'", "fips LIKE '08059012051%'", "fips LIKE '08031005503%'", "fips LIKE '08005005553%'", "fips LIKE '08059012050%'", "fips LIKE '08059012049%'", "fips LIKE '08059012041%'", "fips LIKE '08059012057%'", "fips LIKE '08059012048%'", "fips LIKE '08059011904%'", "fips LIKE '08059012046%'", "fips LIKE '08059012043%'", "fips LIKE '08059015900%'", "fips LIKE '08005005622%'", "fips LIKE '08005005619%'", "fips LIKE '08031012014%'", "fips LIKE '08059012047%'", "fips LIKE '08059980400%'", "fips LIKE '08005005621%'", "fips LIKE '08031012010%'", "fips LIKE '08059012023%'", "fips LIKE '08031001500%'", "fips LIKE '08031001702%'", "fips LIKE '08031001600%'", "fips LIKE '08031002601%'", "fips LIKE '08031001701%'", "fips LIKE '08031001902%'", "fips LIKE '08031002403%'", "fips LIKE '08031002000%'", "fips LIKE '08031001102%'", "fips LIKE '08031002803%'", "fips LIKE '08031002702%'", "fips LIKE '08031002802%'", "fips LIKE '08031002801%'", "fips LIKE '08031002602%'", "fips LIKE '08031002703%'", "fips LIKE '08031002701%'", "fips LIKE '08031003102%'", "fips LIKE '08031001800%'", "fips LIKE '08031002100%'", "fips LIKE '08031000905%'", "fips LIKE '08031000902%'", "fips LIKE '08031000702%'", "fips LIKE '08031000502%'", "fips LIKE '08031000904%'", "fips LIKE '08031001000%'", "fips LIKE '08031000903%'", "fips LIKE '08059980000%'", "fips LIKE '08031000600%'", "fips LIKE '08031001901%'", "fips LIKE '08031000800%'", "fips LIKE '08031000701%'", "fips LIKE '08031003500%'", "fips LIKE '08031003101%'", "fips LIKE '08031004101%'", "fips LIKE '08031002300%'", "fips LIKE '08031003603%'", "fips LIKE '08031003601%'", "fips LIKE '08031002402%'", "fips LIKE '08031003701%'", "fips LIKE '08031003602%'", "fips LIKE '08031003901%'", "fips LIKE '08031003703%'", "fips LIKE '08031003800%'", "fips LIKE '08031003202%'", "fips LIKE '08031004303%'", "fips LIKE '08031003203%'", "fips LIKE '08031004301%'", "fips LIKE '08031003702%'", "fips LIKE '08031003300%'", "fips LIKE '08031004201%'", "fips LIKE '08031004202%'", "fips LIKE '08031004103%'", "fips LIKE '08031004403%'", "fips LIKE '08031004107%'", "fips LIKE '08031004102%'", "fips LIKE '08031004104%'", "fips LIKE '08031003003%'", "fips LIKE '08031003402%'", "fips LIKE '08031002901%'", "fips LIKE '08031002902%'", "fips LIKE '08031003401%'", "fips LIKE '08031004005%'", "fips LIKE '08031003902%'", "fips LIKE '08005005800%'", "fips LIKE '08031004002%'", "fips LIKE '08031003004%'", "fips LIKE '08031001403%'", "fips LIKE '08031003001%'", "fips LIKE '08031003002%'", "fips LIKE '08031004006%'", "fips LIKE '08031000301%'", "fips LIKE '08031000102%'", "fips LIKE '08031000401%'", "fips LIKE '08031000202%'", "fips LIKE '08031000303%'", "fips LIKE '08031000402%'", "fips LIKE '08031001101%'", "fips LIKE '08031000302%'", "fips LIKE '08031000501%'", "fips LIKE '08059010603%'", "fips LIKE '08059010702%'", "fips LIKE '08031015400%'", "fips LIKE '08059010604%'", "fips LIKE '08059010701%'", "fips LIKE '08059010406%'", "fips LIKE '08001009751%'", "fips LIKE '08001008709%'", "fips LIKE '08001008901%'", "fips LIKE '08001009553%'", "fips LIKE '08001015000%'", "fips LIKE '08031003201%'", "fips LIKE '08031001402%'", "fips LIKE '08031004503%'", "fips LIKE '08031004602%'", "fips LIKE '08031004601%'", "fips LIKE '08031004505%'", "fips LIKE '08031001401%'", "fips LIKE '08031015700%'", "fips LIKE '08031001301%'", "fips LIKE '08031004504%'", "fips LIKE '08031004700%'", "fips LIKE '08031004603%'", "fips LIKE '08031015600%'", "fips LIKE '08031004506%'", "fips LIKE '08031004306%'", "fips LIKE '08031004404%'", "fips LIKE '08005007302%'", "fips LIKE '08031004302%'", "fips LIKE '08001007900%'", "fips LIKE '08031004304%'", "fips LIKE '08001007801%'", "fips LIKE '08005007202%'", "fips LIKE '08001009308%'", "fips LIKE '08001009752%'", "fips LIKE '08001009607%'", "fips LIKE '08001009306%'", "fips LIKE '08001009307%'", "fips LIKE '08001009310%'", "fips LIKE '08001009606%'", "fips LIKE '08001009502%'", "fips LIKE '08031000201%'", "fips LIKE '08001009501%'", "fips LIKE '08001009309%'", "fips LIKE '08031005200%'", "fips LIKE '08031004004%'", "fips LIKE '08031006812%'", "fips LIKE '08031006901%'", "fips LIKE '08031006809%'", "fips LIKE '08031005102%'", "fips LIKE '08031015500%'", "fips LIKE '08031004003%'", "fips LIKE '08031005104%'", "fips LIKE '08005015100%'", "fips LIKE '08031005300%'", "fips LIKE '08031001302%'", "fips LIKE '08005087200%'", "fips LIKE '08031005002%'", "fips LIKE '08031005001%'", "fips LIKE '08059011730%'", "fips LIKE '08059011731%'", "fips LIKE '08031011903%'", "fips LIKE '08059011729%'", "fips LIKE '08059011727%'", "fips LIKE '08059011726%'", "fips LIKE '08059011728%'", "fips LIKE '08059011951%'", "fips LIKE '08059011806%'", "fips LIKE '08031011902%'", "fips LIKE '08031012001%'", "fips LIKE '08031004801%'", "fips LIKE '08031007089%'", "fips LIKE '08031004405%'", "fips LIKE '08031006811%'", "fips LIKE '08005087300%'", "fips LIKE '08031006810%'", "fips LIKE '08031007013%'", "fips LIKE '08031006814%'", "fips LIKE '08031007088%'", "fips LIKE '08005086800%'", "fips LIKE '08031005502%'", "fips LIKE '08005005552%'", "fips LIKE '08005005551%'", "fips LIKE '08031006804%'", "fips LIKE '08031006701%'", "fips LIKE '08005006712%'", "fips LIKE '08031980100%'", "fips LIKE '08001008000%'", "fips LIKE '08031004106%'", "fips LIKE '08001008308%'", "fips LIKE '08001008309%'", "fips LIKE '08031008306%'", "fips LIKE '08001988700%'", "fips LIKE '08031008312%'", "fips LIKE '08031008305%'", "fips LIKE '08031008387%'", "fips LIKE '08031008388%'", "fips LIKE '08031008304%'", "fips LIKE '08001008200%'", "fips LIKE '08031008386%'", "fips LIKE '08005004952%'", "fips LIKE '08005004951%'", "fips LIKE '08031015300%'", "fips LIKE '08005086900%'", "fips LIKE '08005080100%'", "fips LIKE '08005087000%'", "fips LIKE '08031007037%'", "fips LIKE '08031008390%'", "fips LIKE '08031008389%'", "fips LIKE '08031980000%'", "fips LIKE '08001008353%'", "fips LIKE '08031008391%");

// $zip = '80212';

// $city_to_zips = get_template_directory_uri().'/uscities.csv';
// $zip_to_tract = get_template_directory_uri().'/zip_to_tract.csv';
// // // $providers = get_template_directory_uri().'/Fixed_Broadband_Deployment_Data__December_2019.csv';


// $tract_arr = [];
 	
// //get all tracts for all zips
// if (($handle = fopen($zip_to_tract, "r")) !== FALSE) {
//     while (($row = fgetcsv($handle, 500, ",")) !== FALSE) {
//     	if ($row[0] == $zip){
//         	$tract_arr[] = '"'.$row[1].'"';
//         }
//     }
//     fclose($handle);
// }
// $tract_arr = array_unique($tract_arr);
// $starts_with = implode(', ', $tract_arr);

	//Get all 
	global $wpdb;
	// $result = $wpdb->get_results ( "SELECT * FROM wp_test2 WHERE ".$starts_with." AND state = 'CO'");

// 	$sql = "SELECT
//   dba_name,
//   COUNT(*) AS `num`
// FROM
//   wp_test2
//   WHERE ".$starts_with." AND state = 'CO' AND consumer = '1' AND download > 0 AND upload > 0
// GROUP BY
//   dba_name";

// 	$sql = "SELECT
//    dba_name,
//    COUNT(*) AS `num`
// FROM
//   wp_test4
//   WHERE fips IN (".$starts_with.") GROUP BY
// dba_name";

$sql = [];
$sql = "SELECT dba_name, COUNT(*) AS `num` FROM wp_test12 WHERE fips IN ('08005080300', '08031006813', '08005087100', '08031007006', '08005080500', '08005084700', '08005080600', '08005080400', '08005083600', '08005083900', '08005083800', '08005081600', '08005081500', '08005006854', '08005081400', '08005082300', '08005082400', '08059012060', '08059012052', '08005005620', '08059012054', '08059012042', '08059012059', '08059012051', '08031005503', '08005005553', '08059012050', '08059012049', '08059012041', '08059012057', '08059012048', '08059011904', '08059012046', '08059012043', '08059015900', '08005005622', '08005005619', '08031012014', '08059012047', '08059980400', '08005005621', '08031012010', '08059012023', '08031001500', '08031001702', '08031001600', '08031002601', '08031001701', '08031001902', '08031002403', '08031002000', '08031001102', '08031002803', '08031002702', '08031002802', '08031002801', '08031002602', '08031002703', '08031002701', '08031003102', '08031001800', '08031002100', '08031000905', '08031000902', '08031000702', '08031000502', '08031000904', '08031001000', '08031000903', '08059980000', '08031000600', '08031001901', '08031000800', '08031000701', '08031003500', '08031003101', '08031004101', '08031002300', '08031003603', '08031003601', '08031002402', '08031003701', '08031003602', '08031003901', '08031003703', '08031003800', '08031003202', '08031004303', '08031003203', '08031004301', '08031003702', '08031003300', '08031004201', '08031004202', '08031004103', '08031004403', '08031004107', '08031004102', '08031004104', '08031003003', '08031003402', '08031002901', '08031002902', '08031003401', '08031004005', '08031003902', '08005005800', '08031004002', '08031003004', '08031001403', '08031003001', '08031003002', '08031004006', '08031000301', '08031000102', '08031000401', '08031000202', '08031000303', '08031000402', '08031001101', '08031000302', '08031000501', '08059010603', '08059010702', '08031015400', '08059010604', '08059010701', '08059010406', '08001009751', '08001008709', '08001008901', '08001009553', '08001015000', '08031003201', '08031001402', '08031004503', '08031004602', '08031004601', '08031004505', '08031001401', '08031015700', '08031001301', '08031004504', '08031004700', '08031004603', '08031015600', '08031004506', '08031004306', '08031004404', '08005007302', '08031004302', '08001007900', '08031004304', '08001007801', '08005007202', '08001009308', '08001009752', '08001009607', '08001009306', '08001009307', '08001009310', '08001009606', '08001009502', '08031000201', '08001009501', '08001009309', '08031005200', '08031004004', '08031006812', '08031006901', '08031006809', '08031005102', '08031015500', '08031004003', '08031005104', '08005015100', '08031005300', '08031001302', '08005087200', '08031005002', '08031005001', '08059011730', '08059011731', '08031011903', '08059011729', '08059011727', '08059011726', '08059011728', '08059011951', '08059011806', '08031011902', '08031012001', '08031004801', '08031007089', '08031004405', '08031006811', '08005087300', '08031006810', '08031007013', '08031006814', '08031007088', '08005086800', '08031005502', '08005005552', '08005005551', '08031006804', '08031006701', '08005006712', '08031980100', '08001008000', '08031004106', '08001008308', '08001008309', '08031008306', '08001988700', '08031008312', '08031008305', '08031008387', '08031008388', '08031008304', '08001008200', '08031008386', '08005004952', '08005004951', '08031015300', '08005086900', '08005080100', '08005087000', '08031007037', '08031008390', '08031008389', '08031980000', '08001008353', '08031008391') AND (consumer = '1' AND state = 'CO') GROUP BY dba_name";


//$sql = "SELECT dba_name, COUNT(*) AS `num` FROM wp_test12 WHERE fips IN ('48453002411', '48453002307', '48453002319', '48021950801', '48453002436', '48021950802', '48453002435', '48453002433', '48453980000', '48453002310', '48491020503', '48453001775', '48453001772', '48209010901', '48453002407', '48491020602', '48453000700', '48453000902', '48453000803', '48453000604', '48453001000', '48453001200', '48453001100', '48453000901', '48453000802', '48453000402', '48453002111', '48453000804', '48453002109', '48453000801', '48453001606', '48453001605', '48453001603', '48453000101', '48453001602', '48453000204', '48453001604', '48453000102', '48453002002', '48453001304', '48453002004', '48453001901', '48453001307', '48453001303', '48453001305', '48453002003', '48453002403', '48453001402', '48453002005', '48453001712', '48453001401', '48453001308', '48453002308', '48453000601', '48453000603', '48453000203', '48453000500', '48453000302', '48453000401', '48453001844', '48453001749', '48453001834', '48453001728', '48491020317', '48491020318', '48491020509', '48491020508', '48491020311', '48491020408', '48491020510', '48491020409', '48453001818', '48453002500', '48453002208', '48453002108', '48453002110', '48453000306', '48453000307', '48453002201', '48453001812', '48453002107', '48453002105', '48453002112', '48453001811', '48453002106', '48453002113', '48453002104', '48453001856', '48453002207', '48453002211', '48453002212', '48453002210', '48453002202', '48453001714', '48453001765', '48453001826', '48453001785', '48453001847', '48453001829', '48453001848', '48453001754', '48453001828', '48453001745', '48453001786', '48453001846', '48491020410', '48453001850', '48491020504', '48453001864', '48453001851', '48453001840', '48453001863', '48453001853', '48491020406', '48491020411', '48453001716', '48453001760', '48453001761', '48453001755', '48453001752', '48453001751', '48453001719', '48453001705', '48453001718', '48453001784', '48453001783', '48453001916', '48453001912', '48453001768', '48453001908', '48453001750', '48453001737', '48453001914', '48453001769', '48453001915', '48453001770', '48453001740', '48453001774', '48453001403', '48453002317', '48453002316', '48453002431', '48453002304', '48453002318', '48453002312', '48453002315', '48453002314', '48453002313', '48453002432', '48453002412', '48453002425', '48453002427', '48453002429', '48453002428', '48453002419', '48453002413', '48453002426', '48453002430', '48453001776', '48453002424', '48453002422', '48453002409', '48453001746', '48453002402', '48453002410', '48453002421', '48453001747', '48453001713', '48453002423', '48453001777', '48453001729', '48453001919', '48453001910', '48453001918', '48453001911', '48453001913', '48453001917', '48453002434', '48453001748', '48453001738', '48453001781', '48491020405', '48491020404', '48491020403', '48453001782', '48453000305', '48453000205', '48453001503', '48453000304', '48453001813', '48453001505', '48453001504', '48453001804', '48453001822', '48453001845', '48453001806', '48453001805', '48453001833', '48453001839', '48453001823', '48453001819', '48453001835', '48453001832', '48453001824', '48453001842', '48453001501', '48453000206', '48453001707', '48453001817', '48453001820', '48453001843', '48453001849', '48453001821', '48453001757', '48453001753', '48453001756', '48453001722', '48453001706') AND (consumer = '1' AND state = 'TX') GROUP BY dba_name";


// $sql = "SELECT download, dba_name FROM wp_test12 WHERE fips IN ('08005080300', '08031006813', '08005087100', '08031007006', '08005080500', '08005084700', '08005080600', '08005080400', '08005083600', '08005083900', '08005083800', '08005081600', '08005081500', '08005006854', '08005081400', '08005082300', '08005082400', '08059012060', '08059012052', '08005005620', '08059012054', '08059012042', '08059012059', '08059012051', '08031005503', '08005005553', '08059012050', '08059012049', '08059012041', '08059012057', '08059012048', '08059011904', '08059012046', '08059012043', '08059015900', '08005005622', '08005005619', '08031012014', '08059012047', '08059980400', '08005005621', '08031012010', '08059012023', '08031001500', '08031001702', '08031001600', '08031002601', '08031001701', '08031001902', '08031002403', '08031002000', '08031001102', '08031002803', '08031002702', '08031002802', '08031002801', '08031002602', '08031002703', '08031002701', '08031003102', '08031001800', '08031002100', '08031000905', '08031000902', '08031000702', '08031000502', '08031000904', '08031001000', '08031000903', '08059980000', '08031000600', '08031001901', '08031000800', '08031000701', '08031003500', '08031003101', '08031004101', '08031002300', '08031003603', '08031003601', '08031002402', '08031003701', '08031003602', '08031003901', '08031003703', '08031003800', '08031003202', '08031004303', '08031003203', '08031004301', '08031003702', '08031003300', '08031004201', '08031004202', '08031004103', '08031004403', '08031004107', '08031004102', '08031004104', '08031003003', '08031003402', '08031002901', '08031002902', '08031003401', '08031004005', '08031003902', '08005005800', '08031004002', '08031003004', '08031001403', '08031003001', '08031003002', '08031004006', '08031000301', '08031000102', '08031000401', '08031000202', '08031000303', '08031000402', '08031001101', '08031000302', '08031000501', '08059010603', '08059010702', '08031015400', '08059010604', '08059010701', '08059010406', '08001009751', '08001008709', '08001008901', '08001009553', '08001015000', '08031003201', '08031001402', '08031004503', '08031004602', '08031004601', '08031004505', '08031001401', '08031015700', '08031001301', '08031004504', '08031004700', '08031004603', '08031015600', '08031004506', '08031004306', '08031004404', '08005007302', '08031004302', '08001007900', '08031004304', '08001007801', '08005007202', '08001009308', '08001009752', '08001009607', '08001009306', '08001009307', '08001009310', '08001009606', '08001009502', '08031000201', '08001009501', '08001009309', '08031005200', '08031004004', '08031006812', '08031006901', '08031006809', '08031005102', '08031015500', '08031004003', '08031005104', '08005015100', '08031005300', '08031001302', '08005087200', '08031005002', '08031005001', '08059011730', '08059011731', '08031011903', '08059011729', '08059011727', '08059011726', '08059011728', '08059011951', '08059011806', '08031011902', '08031012001', '08031004801', '08031007089', '08031004405', '08031006811', '08005087300', '08031006810', '08031007013', '08031006814', '08031007088', '08005086800', '08031005502', '08005005552', '08005005551', '08031006804', '08031006701', '08005006712', '08031980100', '08001008000', '08031004106', '08001008308', '08001008309', '08031008306', '08001988700', '08031008312', '08031008305', '08031008387', '08031008388', '08031008304', '08001008200', '08031008386', '08005004952', '08005004951', '08031015300', '08005086900', '08005080100', '08005087000', '08031007037', '08031008390', '08031008389', '08031980000', '08001008353', '08031008391') AND (consumer = '1' AND state = 'CO') ORDER BY download DESC LIMIT 2";


//$sql[] = "SELECT dba_name, hc_name, download, upload FROM wp_test4 WHERE fips IN ('08005080300', '08031006813', '08005087100', '08031007006', '08005080500', '08005084700', '08005080600', '08005080400', '08005083600', '08005083900', '08005083800', '08005081600', '08005081500', '08005006854', '08005081400', '08005082300', '08005082400', '08059012060', '08059012052', '08005005620', '08059012054', '08059012042', '08059012059', '08059012051', '08031005503', '08005005553', '08059012050', '08059012049', '08059012041', '08059012057', '08059012048', '08059011904', '08059012046', '08059012043', '08059015900', '08005005622', '08005005619', '08031012014', '08059012047', '08059980400', '08005005621', '08031012010', '08059012023', '08031001500', '08031001702', '08031001600', '08031002601', '08031001701', '08031001902', '08031002403', '08031002000', '08031001102', '08031002803', '08031002702', '08031002802', '08031002801', '08031002602', '08031002703', '08031002701', '08031003102', '08031001800', '08031002100', '08031000905', '08031000902', '08031000702', '08031000502', '08031000904', '08031001000', '08031000903', '08059980000', '08031000600', '08031001901', '08031000800', '08031000701', '08031003500', '08031003101', '08031004101', '08031002300', '08031003603', '08031003601', '08031002402', '08031003701', '08031003602', '08031003901', '08031003703', '08031003800', '08031003202', '08031004303', '08031003203', '08031004301', '08031003702', '08031003300', '08031004201', '08031004202', '08031004103', '08031004403', '08031004107', '08031004102', '08031004104', '08031003003', '08031003402', '08031002901', '08031002902', '08031003401', '08031004005', '08031003902', '08005005800', '08031004002', '08031003004', '08031001403', '08031003001', '08031003002', '08031004006', '08031000301', '08031000102', '08031000401', '08031000202', '08031000303', '08031000402', '08031001101', '08031000302', '08031000501', '08059010603', '08059010702', '08031015400', '08059010604', '08059010701', '08059010406', '08001009751', '08001008709', '08001008901', '08001009553', '08001015000', '08031003201', '08031001402', '08031004503', '08031004602', '08031004601', '08031004505', '08031001401', '08031015700', '08031001301', '08031004504', '08031004700', '08031004603', '08031015600', '08031004506', '08031004306', '08031004404', '08005007302', '08031004302', '08001007900', '08031004304', '08001007801', '08005007202', '08001009308', '08001009752', '08001009607', '08001009306', '08001009307', '08001009310', '08001009606', '08001009502', '08031000201', '08001009501', '08001009309', '08031005200', '08031004004', '08031006812', '08031006901', '08031006809', '08031005102', '08031015500', '08031004003', '08031005104', '08005015100', '08031005300', '08031001302', '08005087200', '08031005002', '08031005001', '08059011730', '08059011731', '08031011903', '08059011729', '08059011727', '08059011726', '08059011728', '08059011951', '08059011806', '08031011902', '08031012001', '08031004801', '08031007089', '08031004405', '08031006811', '08005087300', '08031006810', '08031007013', '08031006814', '08031007088', '08005086800', '08031005502', '08005005552', '08005005551', '08031006804', '08031006701', '08005006712', '08031980100', '08001008000', '08031004106', '08001008308', '08001008309', '08031008306', '08001988700', '08031008312', '08031008305', '08031008387', '08031008388', '08031008304', '08001008200', '08031008386', '08005004952', '08005004951', '08031015300', '08005086900', '08005080100', '08005087000', '08031007037', '08031008390', '08031008389', '08031980000', '08001008353', '08031008391') AND (consumer = '1' AND state = 'CO') LIMIT 100000";
//$sql[] = "SELECT dba_name, hc_name, download, upload FROM wp_test4 WHERE fips IN ('08005080300', '08031006813', '08005087100', '08031007006', '08005080500', '08005084700', '08005080600', '08005080400', '08005083600', '08005083900', '08005083800', '08005081600', '08005081500', '08005006854', '08005081400', '08005082300', '08005082400', '08059012060', '08059012052', '08005005620', '08059012054', '08059012042', '08059012059', '08059012051', '08031005503', '08005005553', '08059012050', '08059012049', '08059012041', '08059012057', '08059012048', '08059011904', '08059012046', '08059012043', '08059015900', '08005005622', '08005005619', '08031012014', '08059012047', '08059980400', '08005005621', '08031012010', '08059012023', '08031001500', '08031001702', '08031001600', '08031002601', '08031001701', '08031001902', '08031002403', '08031002000', '08031001102', '08031002803', '08031002702', '08031002802', '08031002801', '08031002602', '08031002703', '08031002701', '08031003102', '08031001800', '08031002100', '08031000905', '08031000902', '08031000702', '08031000502', '08031000904', '08031001000', '08031000903', '08059980000', '08031000600', '08031001901', '08031000800', '08031000701', '08031003500', '08031003101', '08031004101', '08031002300', '08031003603', '08031003601', '08031002402', '08031003701', '08031003602', '08031003901', '08031003703', '08031003800', '08031003202', '08031004303', '08031003203', '08031004301', '08031003702', '08031003300', '08031004201', '08031004202', '08031004103', '08031004403', '08031004107', '08031004102', '08031004104', '08031003003', '08031003402', '08031002901', '08031002902', '08031003401', '08031004005', '08031003902', '08005005800', '08031004002', '08031003004', '08031001403', '08031003001', '08031003002', '08031004006', '08031000301', '08031000102', '08031000401', '08031000202', '08031000303', '08031000402', '08031001101', '08031000302', '08031000501', '08059010603', '08059010702', '08031015400', '08059010604', '08059010701', '08059010406', '08001009751', '08001008709', '08001008901', '08001009553', '08001015000', '08031003201', '08031001402', '08031004503', '08031004602', '08031004601', '08031004505', '08031001401', '08031015700', '08031001301', '08031004504', '08031004700', '08031004603', '08031015600', '08031004506', '08031004306', '08031004404', '08005007302', '08031004302', '08001007900', '08031004304', '08001007801', '08005007202', '08001009308', '08001009752', '08001009607', '08001009306', '08001009307', '08001009310', '08001009606', '08001009502', '08031000201', '08001009501', '08001009309', '08031005200', '08031004004', '08031006812', '08031006901', '08031006809', '08031005102', '08031015500', '08031004003', '08031005104', '08005015100', '08031005300', '08031001302', '08005087200', '08031005002', '08031005001', '08059011730', '08059011731', '08031011903', '08059011729', '08059011727', '08059011726', '08059011728', '08059011951', '08059011806', '08031011902', '08031012001', '08031004801', '08031007089', '08031004405', '08031006811', '08005087300', '08031006810', '08031007013', '08031006814', '08031007088', '08005086800', '08031005502', '08005005552', '08005005551', '08031006804', '08031006701', '08005006712', '08031980100', '08001008000', '08031004106', '08001008308', '08001008309', '08031008306', '08001988700', '08031008312', '08031008305', '08031008387', '08031008388', '08031008304', '08001008200', '08031008386', '08005004952', '08005004951', '08031015300', '08005086900', '08005080100', '08005087000', '08031007037', '08031008390', '08031008389', '08031980000', '08001008353', '08031008391') AND (consumer = '1' AND state = 'CO') LIMIT 10000001,10000000";
//   $sql = "INSERT INTO wp_test3 (zip, compressed)
// SELECT '80212', dba_name FROM wp_test2 WHERE (".$starts_with.") AND (consumer != '0' AND download != '0' AND upload != '0');";

// $sql = "INSERT INTO wp_test2 (zip)
// SELECT '80212' FROM wp_test2 WHERE (".$starts_with.") AND (consumer != '0' AND download != '0' AND upload != '0');";
// // 	echo $sql;
// 	$sql = "SELECT dba_name, COUNT(*) AS `num`, download, upload FROM wp_test4 WHERE (consumer != '0' AND state = 'CO') GROUP BY dba_name";
$result = $wpdb->get_results($sql);
	// $result = serialize($result);
	// $sql .= "('80212','".$result."'),";



	print "memory:" . formatBytes(memory_get_peak_usage());

    echo '<pre>';
	print_r($result);
	echo '</pre>';


 
} 
// register shortcode
add_shortcode('zip_to_fip_api', 'zip_to_fip_api_shortcode'); 


function provider_csv_to_db_shortcode() { 
	set_time_limit(0);
	$providers = get_template_directory_uri().'/providers.csv';
	$providers = [];
	$providers[] = get_template_directory_uri().'/xaa';
	$providers[] = get_template_directory_uri().'/xab';
	$providers[] = get_template_directory_uri().'/xac';
	$providers[] = get_template_directory_uri().'/xad';
	$providers[] = get_template_directory_uri().'/xae';
	$providers[] = get_template_directory_uri().'/xaf';
	$providers[] = get_template_directory_uri().'/xag';
	$providers[] = get_template_directory_uri().'/xah';
	$providers[] = get_template_directory_uri().'/xai';
	$providers[] = get_template_directory_uri().'/xaj';
	$providers[] = get_template_directory_uri().'/xak';
	$providers[] = get_template_directory_uri().'/xal';
	$providers[] = get_template_directory_uri().'/xam';
	$providers[] = get_template_directory_uri().'/xan';
	$providers[] = get_template_directory_uri().'/xao';
	$providers[] = get_template_directory_uri().'/xap';
	$providers[] = get_template_directory_uri().'/xaq';
	$providers[] = get_template_directory_uri().'/xar';
	$providers[] = get_template_directory_uri().'/xas';
	$providers[] = get_template_directory_uri().'/xat';
	$providers[] = get_template_directory_uri().'/xau';
	$providers[] = get_template_directory_uri().'/xav';
	$providers[] = get_template_directory_uri().'/xaw';
	$providers[] = get_template_directory_uri().'/xax';
	$providers[] = get_template_directory_uri().'/xay';
	$providers[] = get_template_directory_uri().'/xaz';
	$providers[] = get_template_directory_uri().'/xba';
	$providers[] = get_template_directory_uri().'/xbb';
	$providers[] = get_template_directory_uri().'/xbc';
	$providers[] = get_template_directory_uri().'/xbd';
	$providers[] = get_template_directory_uri().'/xbe';
	$providers[] = get_template_directory_uri().'/xbf';
	$providers[] = get_template_directory_uri().'/xbg';
	$providers[] = get_template_directory_uri().'/xbh';
	$providers[] = get_template_directory_uri().'/xbi';
	$providers[] = get_template_directory_uri().'/xbj';
	$providers[] = get_template_directory_uri().'/xbk';
	$providers[] = get_template_directory_uri().'/xbl';
	$providers[] = get_template_directory_uri().'/xbm';
	$providers[] = get_template_directory_uri().'/xbn';
	$providers[] = get_template_directory_uri().'/xbo';
	$providers[] = get_template_directory_uri().'/xbp';
	$providers[] = get_template_directory_uri().'/xbq';
	$providers[] = get_template_directory_uri().'/xbr';
	$providers[] = get_template_directory_uri().'/xbs';
	$providers[] = get_template_directory_uri().'/xbt';
	$providers[] = get_template_directory_uri().'/xbu';
	$providers[] = get_template_directory_uri().'/xbv';
	$providers[] = get_template_directory_uri().'/xbw';
	$providers[] = get_template_directory_uri().'/xbx';
	$providers[] = get_template_directory_uri().'/xby';
	$providers[] = get_template_directory_uri().'/xbz';
	$providers[] = get_template_directory_uri().'/xca';
	$providers[] = get_template_directory_uri().'/xcb';
	$providers[] = get_template_directory_uri().'/xcc';
	$providers[] = get_template_directory_uri().'/xcd';
	$providers[] = get_template_directory_uri().'/xce';
	$providers[] = get_template_directory_uri().'/xcf';
	$providers[] = get_template_directory_uri().'/xcg';
	$providers[] = get_template_directory_uri().'/xch';
	$providers[] = get_template_directory_uri().'/xci';
	$providers[] = get_template_directory_uri().'/xcj';
	$providers[] = get_template_directory_uri().'/xck';
	$providers[] = get_template_directory_uri().'/xcl';
	$providers[] = get_template_directory_uri().'/xcm';
	$providers[] = get_template_directory_uri().'/xcn';
	$providers[] = get_template_directory_uri().'/xco';
	$providers[] = get_template_directory_uri().'/xcp';
	$providers[] = get_template_directory_uri().'/xcq';
	$providers[] = get_template_directory_uri().'/xcr';
	$providers[] = get_template_directory_uri().'/xcs';
	$providers[] = get_template_directory_uri().'/xct';
	$providers[] = get_template_directory_uri().'/xcu';
	$providers[] = get_template_directory_uri().'/xcv';
	global $wpdb;
	$delete_sql = "DELETE FROM $table_name;";
	$query = $wpdb->query($delete_sql);
	$table_name = $wpdb->prefix . "fcc_provider_data";
	foreach ($providers as $provider){
		$sql="
	    LOAD DATA LOCAL INFILE '$provider' INTO TABLE $table_name
    FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
    LINES TERMINATED BY '\n'
    IGNORE 1 LINES
    (@dummy,@dummy,@dummy,provider_name,dba_name,hc_name,@dummy,hc_final,state,fips,tech_code,consumer,download,upload,@dummy);
    ";
	    $query = $wpdb->query($sql);
	}
	// $sql="
 //    LOAD DATA LOCAL INFILE '$providers' INTO TABLE $table_name
 //    FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
 //    LINES TERMINATED BY '\n'
 //    IGNORE 1 LINES
 //    (@dummy,@dummy,@dummy,provider_name,dba_name,hc_name,@dummy,@dummy,state,fips,tech_code,consumer,download,upload,@dummy);
 //    ";
 //    $query = $wpdb->query($sql);
} 
// register shortcode
add_shortcode('provider_csv_to_db', 'provider_csv_to_db_shortcode'); 


function top_city_shortcode($atts = '') { 
 	
 	extract( shortcode_atts( array(
		'zipcode' => '',
	), $atts ) );

	global $wpdb;
	$table_name = $wpdb->prefix . "zip_tract";

	//get city based on zip code
	$city_query = "SELECT usps_zip_pref_city, usps_zip_pref_state FROM $table_name WHERE zip = $zipcode LIMIT 1";
    $row = $wpdb -> get_results($city_query);
    $city = $row[0]->usps_zip_pref_city;
    $state = $row[0]->usps_zip_pref_state;
    //get all tracts so we can get provider count
    $tracts_query = "SELECT tract FROM $table_name WHERE usps_zip_pref_city = '$city' AND usps_zip_pref_state = '$state'";
    $row = $wpdb -> get_results($tracts_query);
    $tract_arr = [];
    foreach($row as $tract){
    	$tract_arr[] = "'".$tract->tract."'";
    }
    $tract_arr = array_unique($tract_arr);
    $tract_where = implode(', ', $tract_arr);
    $provider_table_name = $wpdb->prefix . "fcc_provider_data";
    $sql = "SELECT COUNT(DISTINCT dba_name) as dba_count FROM $provider_table_name WHERE fips IN ($tract_where) AND (consumer = '1' AND state = '$state')";
    $provider_count = $wpdb -> get_results($sql);
    $provider_count = $provider_count[0]->dba_count;

    //get geoid based on city
    $table_name = $wpdb->prefix . "city_to_geoid";
    $place_name = $city.', '.$state;
    $geoid_query = "SELECT geoid FROM $table_name WHERE name = '$place_name' AND type='place' LIMIT 1";
    $row = $wpdb -> get_results($geoid_query);
    $geoid = $row[0]->geoid;
    $geoid = str_pad($geoid, 7, '0', STR_PAD_LEFT);
    //echo $geoid;
	$app_token = 'aK7RjsSGrARQEmw9UHhhilmG5';
	$api_endpoint = "https://opendata.fcc.gov/resource/ktav-pdj7.json?type=place&id=$geoid&speed=25&tribal_non=N";
    $curl = curl_init($api_endpoint);

    $curl_data = [
        '$limit' => 5000,
    ]; 
    $curl_data = json_encode($curl_data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json', 
            'X-App-Token: '.$app_token,
            'Accept: application/json',
        )                                                                               
    );                                                                    
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                            
    $return_json = curl_exec($curl);
    $return_array = json_decode($return_json, true);

    // echo '<pre>';
    // print_r($return_array);
    // echo '</pre>';

    //format population
    // if (strlen($population) >= 7){
    // 	$population_formatted = round(($population/1000000), 1).' million';
    // } else {
    // 	$population_formatted = number_format($population, 0, '', ',');
    // }
    $available = [];
    $coverage_arr = [];
    $connection_type_text = [];
    $covered = 0;
    $not_covered = 0;
    $fiber_covered = 0;
    $fiber_not_covered = 0;
    foreach($return_array as $item){
    	//used only dsl (a), fiber (f), cable (c) and o (other) to determine tital coverage as satellite and fixed wireless tended to show 100% coverage in most cases
    	if ($item['tech'] == 'acfo'){
    		//find percentage covered by urban and by rural and combine them
    		$covered = $covered + $item['has_1'] + $item['has_2'] + $item['has_3more'];
    		$not_covered = $not_covered + $item['has_0'];
    	}
    	// if ($item['tech'] == 'a'){
    	// 	$coverage = $item['has_1'] + $item['has_2'] + $item['has_3more'];
    	// 	if ($coverage > 0){
    	// 		$available[] = 'DSL';
    	// 		$coverage_arr['DSL'] = $coverage;

    	// 	}
    	// }
    	// if ($item['tech'] == 'c'){
    	// 	$coverage = $item['has_1'] + $item['has_2'] + $item['has_3more'];
    	// 	if ($coverage > 0){
    	// 		$available[] = 'cable';
    	// 		$coverage_arr['cable'] = $coverage;
    	// 	}
    	// }
    	// if ($item['tech'] == 's'){
    	// 	$coverage = $item['has_1'] + $item['has_2'] + $item['has_3more'];
    	// 	if ($coverage > 0){
    	// 		$available[] = 'satellite';
    	// 	}
    	// }
    	// if ($item['tech'] == 'w'){
    	// 	$coverage = $item['has_1'] + $item['has_2'] + $item['has_3more'];
    	// 	if ($coverage > 0){
    	// 		$available[] = 'fixed wireless';
    	// 		$coverage_arr['fixed wireless'] = $coverage;
    	// 	}
    	// }
    	// if ($item['tech'] == 'f'){
    	// 	$coverage = $item['has_1'] + $item['has_2'] + $item['has_3more'];
    	// 	if ($coverage > 0){
    	// 		$available[] = 'fiber';
    	// 		$coverage_arr['fiber'] = $coverage;
    	// 	}
    	// }
    	if ($item['tech'] == 'f'){
    		$fiber_covered = $fiber_covered + $item['has_1'] + $item['has_2'] + $item['has_3more'];
    		$fiber_not_covered = $fiber_not_covered + $item['has_0'];
    	}
    }
    $total = $covered + $not_covered;
    $population = $total/2;
    $percentage_covered = $covered / $total;
    $percentage_covered = round((float)$percentage_covered * 100 ) . '%';
    $fiber_percentage_covered = $fiber_covered / $total;
    $fiber_percentage_covered = round((float)$fiber_percentage_covered * 100 ) . '%';
    // $permanent_coverage_arr = $coverage_arr;
    // $most_common = array_search(max($coverage_arr),$coverage_arr);
    // $coverage_most_common = round((float)($coverage_arr[$most_common]/$population) * 100 ) . '%';
    // //unset the most common from the array so we can find the second most common
    // unset($coverage_arr[$most_common]);
    // $second_most_common = array_search(max($coverage_arr),$coverage_arr);
    // $coverage_second_most_common = round((float)($coverage_arr[$second_most_common]/$population) * 100 ) . '%';
    echo 'City, State: '.$city. ', '.$state.'<br>';
    echo 'Provider Count: '.$provider_count.'<br>';
    //echo 'Population: '.$population_formatted.'<br>';
    echo 'Percentage Covered: '.$percentage_covered.'<br>';
    echo 'Fiber Covered: '.$fiber_percentage_covered.'<br>';
    // echo 'Available: '.implode(', ' , $available).'<br>';
    // echo 'Most Commom Connection Type: '.$most_common.'<br>';
    // echo 'Most Commom Connection Type Coverage: '.$coverage_most_common.'<br>';
    // echo 'Second Most Commom Connection Type: '.$second_most_common.'<br>';
    // echo 'Second Most Commom Connection Type Coverage: '.$coverage_second_most_common.'<br>';
    // echo '<ul>';
    // if (in_array('fiber', $available)){
    // 	echo '<li><strong>Fiber-Optic:</strong> Currently the fastest internet connection, fiber internet transmits light signals along glass-threaded wires and offers symmetrical upload and download speeds. While a few providers deliver speeds up to 10 Gbps, most fiber-optic providers offer equal speeds up to 1,000 Mbps.</li>';
    // }
    // if (in_array('cable',$available)){
    // 	echo '<li><strong>Cable:</strong> A better alternative to DSL, cable internet runs on coaxial cables from existing TV lines. Depending on where you’re located, you may see speeds range up to 1,000 Mbps.</li>';
    // }
    // if (in_array('DSL',$available)){
    // 	echo '<li><strong>DSL:</strong> Middle-range on the speed meter, DSL internet is delivered over pre-existing telephone lines. Typically, DSL providers offer speeds ranging from 1-100 Mbps.</li>';
    // }
    // if (in_array('satellite',$available)){
    // 	echo '<li><strong>Satellite:</strong> Customers need a stationary dish installed on their property to access satellite internet. Since this type of network is made of orbiting satellites, it’s theoretically available across the contiguous United States. However, you typically don’t get speeds faster than 25 Mbps.</li>';
    // }
    // if (in_array('fixed wireless',$available)){
    // 	echo '<li><strong>Fixed Wireless:</strong> Considered the slowest internet type, fixed wireless is usually transmitted through radio waves between two fixed points. It’s typically used among businesses more so than residential properties.</li>';
    // }
    // echo '</ul>';
 
} 
// register shortcode
add_shortcode('top_city', 'top_city_shortcode');

function zip_to_city_redirect_shortcode() { 
 	
 	$html = '
    <form action="" class="zip_to_city_search_form search_wrap">
        <input type="number" class="zip_to_city_search_input" id="zip" name="zip" minlength="5" maxlength="5" placeholder="Search by ZIP" pattern="\d*"/>
        <button type="button" class="submit-zip-to-city">Search</button>
    </form>';
	return $html;
 
} 
// register shortcode
add_shortcode('zip_to_city_redirect', 'zip_to_city_redirect_shortcode');  


function tract_by_zip($atts = '') { 
 	
 	extract( shortcode_atts( array(
		'zipcode' => '',
	), $atts ) );

	global $wpdb;
	$table_name = $wpdb->prefix . "zip_tract";

	//get city based on zip code
	$tract_query = "SELECT tract FROM $table_name WHERE zip = $zipcode";
    $row = $wpdb -> get_results($tract_query);
    $tract = $row[0]->tract;
    
    echo '<pre>';
    print_r($row);
    echo '</pre>';

 
} 
// register shortcode
add_shortcode('tract_by_zip', 'tract_by_zip');

