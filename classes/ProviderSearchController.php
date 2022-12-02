<?php
namespace ZipSearch;

use ZipSearch\PostgreSQLConnection as PostgreSQLConnection;
use ZipSearch\BDAPIConnection as BDAPIConnection;
use ZipSearch\ProvidersDBConnection as ProvidersDBConnection;
use Dotenv\Dotenv as DotEnv;
// use ZipSearch\VaultTask as VaultTask;

class ProviderSearchController {

    /**
     * return all providers
     */
    public function getAllProviders($args=[]) {

        $zipcode = isset($args['zipcode']) ? $args['zipcode'] : null;
        $is_city = isset($args['is_city']) ? $args['is_city'] : false;
        $is_programmatic_city_page = isset($args['is_programmatic_city_page']) ? $args['is_programmatic_city_page'] : false;
        $zip_arr = isset($args['zip_arr']) ? $args['zip_arr'] : [];
        $city = isset($args['city']) ? $args['city'] : null;
        $state = isset($args['state']) ? $args['state'] : null;
        $zip_provider = isset($args['provider_id']) ? $args['provider_id'] : null;

        $auth = $this->get_auth();
        $bd_providers = [];
        $fcc_providers = [];
        $provider_arr = [];
        $provider_arr['internet'] = [];
        $provider_arr['tv'] = [];
        $provider_arr['bundles'] = [];
        if (!$is_city){
            $is_valid_zip = self::isValidZipCode($zipcode);
            if (!$is_valid_zip){
                return  $provider_arr;
            }
        }
        //get provider buyers
        if($zip_provider) {
            $providers[] = get_post($zip_provider);
        }
        else {
            $providers = get_posts(array(
                'numberposts'   => -1,
                'post_type'     => 'provider',
            ));
        }

        //sort out where we need to get data for each provider
        foreach($providers as $provider){

            if (get_field('partner', $provider->ID)){
                $partner_order = get_field('order', $provider->ID);
                if (!$partner_order){
                    $partner_order = 9999999;
                }
                $hide_from_results = get_field('hide_from_zip_search', $provider->ID);
                if ($hide_from_results){
                    continue;
                }
                $buyer_id = get_field('buyer', $provider->ID);
                $campaigns = get_field('campaign', $buyer_id);
                $possible_names_arr = get_field('possible_provider_names', $provider->ID);
                $possible_names = [];
                $campaign_key = false;
                if (is_array($possible_names_arr)){
                    foreach($possible_names_arr as $name){
                        $possible_names[] = $name['name'];
                    }
                }
                //find campaign key where the campaign name field has the same provider id
                $test = array_search (  $provider->ID, array_column($campaigns, 'campaign_name') );
                if (is_numeric($test) && $test !== false){
                    $campaign_key = $test;
                }
                if ($campaign_key !== false){
                    $has_internet=false;$has_tv=false;$has_bundles=false;
                    $coverage_type = $campaigns[$campaign_key]['coverage_type'];
                    $products_arr = get_field('products', $provider->ID);
                    if (is_array($products_arr)){
                        if (in_array('internet', $products_arr)){
                            $internet = get_field('internet', $provider->ID);
                            if ($internet['split_out_connection']){
                                $i_connection_types = $internet['connection_types'];
                                $min_starting_price_arr = [];
                                $max_d_speed_arr = [];
                                $max_u_speed_arr = [];
                                foreach ($i_connection_types as $i_connection_type){
                                    switch ($i_connection_type) {
                                        case "cable":
                                            $min_starting_price_arr[] = $internet['cable_connection']['cable_starting_price'];
                                            $max_d_speed_arr[] = $internet['cable_connection']['cable_max_download_speed'];
                                            $max_u_speed_arr[] = $internet['cable_connection']['cable_max_upload_speed'];
                                        case "fiber":
                                            $min_starting_price_arr[] = $internet['fiber_connection']['fiber_starting_price'];
                                            $max_d_speed_arr[] = $internet['fiber_connection']['fiber_max_download_speed'];
                                            $max_u_speed_arr[] = $internet['fiber_connection']['fiber_max_upload_speed'];
                                        case "dsl":
                                            $min_starting_price_arr[] = $internet['dsl_connection']['dsl_starting_price'];
                                            $max_d_speed_arr[] = $internet['dsl_connection']['dsl_max_download_speed'];
                                            $max_u_speed_arr[] = $internet['dsl_connection']['dsl_max_upload_speed'];
                                        case "fixed":
                                            $min_starting_price_arr[] = $internet['fixed_connection']['fixed_starting_price'];
                                            $max_d_speed_arr[] = $internet['fixed_connection']['fixed_max_download_speed'];
                                            $max_u_speed_arr[] = $internet['fixed_connection']['fixed_max_upload_speed'];
                                        case "wireless":
                                            $min_starting_price_arr[] = $internet['wireless_connection']['wireless_starting_price'];
                                            $max_d_speed_arr[] = $internet['wireless_connection']['wireless_max_download_speed'];
                                            $max_u_speed_arr[] = $internet['wireless_connection']['wireless_max_upload_speed'];
                                        case "satellite":
                                            $min_starting_price_arr[] = $internet['satellite_connection']['satellite_starting_price'];
                                            $max_d_speed_arr[] = $internet['satellite_connection']['satellite_max_download_speed'];
                                            $max_u_speed_arr[] = $internet['satellite_connection']['satellite_max_upload_speed'];
                                    }
                                    if (!empty($min_starting_price_arr)){
                                        $min_starting_price_arr = array_filter($min_starting_price_arr);
                                        $i_min_starting_price = min($min_starting_price_arr);
                                    } else {
                                        $i_min_starting_price = $internet['details']['min_starting_price'];
                                    }
                                    if (!empty($max_d_speed_arr)){
                                        $max_d_speed_arr = array_filter($max_d_speed_arr);
                                        $i_max_d_speed = max($max_d_speed_arr);
                                    } else {
                                        $i_max_d_speed = $internet['details']['max_download_speed'];
                                    }
                                    if (!empty($max_u_speed_arr)){
                                        $max_u_speed_arr = array_filter($max_u_speed_arr);
                                        $i_max_u_speed = max($max_u_speed_arr);
                                    } else {
                                        $i_max_u_speed = $internet['details']['max_upload_speed'];
                                    }
                                }
                            } else {
                                $i_min_starting_price = $internet['details']['min_starting_price'];
                                $i_max_d_speed = $internet['details']['max_download_speed'];
                                $i_max_u_speed = $internet['details']['max_upload_speed'];
                            }
                            $has_internet = true;
                            $i_min_starting_price = preg_replace("/[^0-9.]/", "", $i_min_starting_price);
                        } 
                        if (in_array('tv', $products_arr)){
                            $tv = get_field('tv', $provider->ID);
                            if ($tv['split_out_connection']){
                                $t_connection_types = $tv['connection_types'];
                                $min_starting_price_arr = [];
                                $max_channel_count_arr = [];
                                foreach ($t_connection_types as $t_connection_type){
                                    switch ($t_connection_type) {
                                    case "cable":
                                        $min_starting_price_arr[] = $tv['cable_connection']['cable_starting_price'];
                                        $max_channel_count_arr[] = $tv['cable_connection']['cable_max_channel_count'];
                                    case "fiber":
                                        $min_starting_price_arr[] = $tv['fiber_connection']['fiber_starting_price'];
                                        $max_channel_count_arr[] = $tv['fiber_connection']['fiber_max_channel_count'];
                                    case "streaming":
                                        $min_starting_price_arr[] = $tv['streaming_connection']['streaming_starting_price'];
                                        $max_channel_count_arr[] = $tv['streaming_connection']['streaming_max_channel_count'];
                                    case "satellite":
                                        $min_starting_price_arr[] = $tv['satellite_connection']['satellite_starting_price'];
                                        $max_channel_count_arr[] = $tv['satellite_connection']['satellite_max_channel_count'];
                                }
                                if (!empty($min_starting_price_arr)){
                                    $min_starting_price_arr = array_filter($min_starting_price_arr);
                                    $t_min_starting_price = min($min_starting_price_arr);
                                } else {
                                    $t_min_starting_price = $tv['details']['min_starting_price'];
                                }
                                if (!empty($max_channel_count_arr)){
                                    $max_channel_count_arr = array_filter($max_channel_count_arr);
                                    $t_max_channel_count = max($max_channel_count_arr);
                                } else {
                                    $t_max_channel_count = $tv['details']['max_channel_count'];
                                }
                            }
                            } else {
                                $t_min_starting_price = $tv['details']['min_starting_price'];
                                $t_max_channel_count = $tv['details']['max_channel_count'];
                            }
                            $has_tv = true;
                            $t_min_starting_price = preg_replace("/[^0-9.]/", "", $t_min_starting_price);
                            $t_max_channel_count = preg_replace("/[^0-9.]/", "", $t_max_channel_count);
                        } 
                        if (in_array('bundles', $products_arr)){
                            $bundles = get_field('bundles', $provider->ID);
                            $has_bundles = true;
                        }
                    } 
                    if ($coverage_type == 'bundle_dealer_api'){
                        $bd_providers[$provider->ID] = [
                            'possible_names' => $possible_names,
                            'order' => $partner_order,
                        ];
                    } elseif ($coverage_type == 'all') {
                        if ($has_internet){
                            $provider_arr['internet'][$possible_names[0]] = [
                                'name' => $possible_names[0],
                                'id' => $provider->ID,
                                'download_speed' => preg_replace('/[^0-9]/', '', $i_max_d_speed),
                                'upload_speed' => preg_replace('/[^0-9]/', '', $i_max_u_speed),
                                'cost' => $i_min_starting_price,
                                'order' => $partner_order
                            ];
                        } 
                        if ($has_tv){
                            $provider_arr['tv'][$possible_names[0]] = [
                                'name' => $possible_names[0],
                                'id' => $provider->ID,
                                'channels' => $t_max_channel_count,
                                'cost' => $t_min_starting_price,
                                'order' => $partner_order
                            ];
                        } 
                        if ($has_bundles){
                            $provider_arr['bundles'][$possible_names[0]] = [
                                'name' => $possible_names[0],
                                'id' => $provider->ID,
                                'download_speed' => $bundles['max_download_speeds'],
                                'channels' => $bundles['minimum_channel_count'],
                                'cost' => $bundles['starting_price'],
                                'order' => $partner_order
                            ];
                        }
                    } else {
                        $channels = 'N/A';
                        $tv_cost = 'N/A';
                        $i_cost = 'N/A';
                        $b_cost = 'N/A';
                        $b_download_speed = 'N/A';
                        $b_channels = 'N/A';
                        $tv_cov = false;
                        $bundle_cov = false;
                        if ($has_internet){
                            $i_cost = $i_min_starting_price;
                        }
                        if ($has_tv){
                            $tv_cov = true;
                            $channels = $t_max_channel_count;
                            $tv_cost = $t_min_starting_price;
                        }
                        if ($has_bundles){
                            $b_cost = $bundles['starting_price'];
                            $b_download_speed = $bundles['max_download_speeds'];
                            $b_channels = $bundles['minimum_channel_count'];
                            $bundle_cov = true;
                        }
                        if ($coverage_type == 'zip_upload'){
                            $zip_upload_providers[$provider->ID] = [
                                'possible_names' => $possible_names,
                                'i_cost' => $i_cost,
                                'channels' => $channels,
                                'tv_cost' => $tv_cost,
                                'b_download_speed' => $b_download_speed,
                                'b_channels' => $b_channels,
                                'b_cost' => $b_cost,
                                'tv_cov' => $tv_cov,
                                'bundle_cov' => $bundle_cov,
                                'order' => $partner_order
                            ];

                        } elseif ($coverage_type == 'fcc'){
                            $fcc_providers[$provider->ID] = [
                                'possible_names' => $possible_names,
                                'i_cost' => $i_cost,
                                'channels' => $channels,
                                'tv_cost' => $tv_cost,
                                'b_download_speed' => $b_download_speed,
                                'b_channels' => $b_channels,
                                'b_cost' => $b_cost,
                                'tv_cov' => $tv_cov,
                                'bundle_cov' => $bundle_cov,
                                'order' => $partner_order
                            ];
                        }
                    }

                }
            }
        }
        $providersDB = new ProvidersDBConnection($zipcode);
        $temp_provider_arr = [];
        $temp_provider_arr['internet'] = [];
        // $temp_provider_arr['tv'] = [];
        // $temp_provider_arr['bundles'] = [];
        //get all internet providers from FCC
        if ($is_city){
            $internet_providers_arr = $providersDB->getAllInternetProviderDataByCity($zip_arr);
            $zip_upload_prov_arr = $providersDB->getAllZipUploadProviderDataByCity($zip_arr);
            //$tv_providers_arr = $providersDB->getAllTvProviderDataByCity($zip_arr);
        } else {
            $internet_providers_arr = $providersDB->getAllInternetProviderData();
            $zip_upload_prov_arr = $providersDB->getAllZipUploadProviderData();
            //$tv_providers_arr = $providersDB->getAllTvProviderData();
        } 
        $in_fcc_zip_upload_arrs = [];
        if (is_array($zip_upload_prov_arr) && !empty($zip_upload_prov_arr)){
            foreach($zip_upload_prov_arr as $provider){
                foreach($zip_upload_providers as $key => $zip_upload_provider) {
                   if (in_array($provider->hso_provider, $zip_upload_provider['possible_names'])) {
                        $name = $zip_upload_provider['possible_names'][0];
                        $in_fcc_zip_upload_arrs[] = $name;
                        $temp_provider_arr['internet'][$name] = [
                            'name' => $name,
                            'id' => $key,
                            'download_speed' => (int)$provider->max_advertised_downstream_speed_mbps,
                            'upload_speed' => (int)$provider->max_advertised_upstream_speed_mbps,
                            'cost' => $zip_upload_provider['i_cost'],
                            'order' => $zip_upload_provider['order'],
                            'connection_type' => $provider->connection_type
                        ];
                        //if download speed is empty then add it to fcc providers array
                        if ($temp_provider_arr['internet'][$name]['download_speed'] == '' || $temp_provider_arr['internet'][$name]['upload_speed'] == ''){
                            $fcc_providers[$key] = $zip_upload_provider;
                            if ($temp_provider_arr['internet'][$name]['download_speed'] == ''){
                                $fcc_providers[$key]['update_download_speed'] == true;
                            }
                            if ($temp_provider_arr['internet'][$name]['upload_speed'] == ''){
                                $fcc_providers[$key]['update_upload_speed'] == true;
                            }
                            if ($temp_provider_arr['internet'][$name]['connection_type'] == ''){
                                $fcc_providers[$key]['update_connection_type'] == true;
                            }
                        }
                        //just for xfinity and Frontier we use the price in the zip uploads table instead of the WP backend
                        if ($name == 'Xfinity' || $name == 'Frontier'){
                            $temp_provider_arr['internet'][$name]['cost'] = (float)$provider->price;
                        }
                   }
                }
            }
        }
        if (is_array($internet_providers_arr) && !empty($internet_providers_arr)){
            foreach($internet_providers_arr as $provider){
                foreach($fcc_providers as $key => $fcc_provider) {
                   if (in_array($provider->hso_provider, $fcc_provider['possible_names'])) {
                        $name = $fcc_provider['possible_names'][0];
                        //never grab frontier data even if it's blank
                        if ($name == 'Frontier'){
                            continue;
                        }
                        if ($fcc_provider['update_download_speed'] || $fcc_provider['update_upload_speed']){
                            if ($fcc_provider['update_download_speed']){
                                $temp_provider_arr['internet'][$name] = [
                                    'download_speed' => (int)$provider->max_advertised_downstream_speed_mbps,
                                ];
                            }
                            if ($fcc_provider['update_upload_speed']){
                                $temp_provider_arr['internet'][$name] = [
                                    'upload_speed' => (int)$provider->max_advertised_upstream_speed_mbps,
                                ];
                            }
                            if ($fcc_provider['update_connection_type']){
                                $temp_provider_arr['internet'][$name] = [
                                    'connection_type' => $provider->connection_type,
                                ];
                            }
                        } else {
                            $in_fcc_zip_upload_arrs[] = $name;
                            $temp_provider_arr['internet'][$name] = [
                                'name' => $name,
                                'id' => $key,
                                'download_speed' => (int)$provider->max_advertised_downstream_speed_mbps,
                                'upload_speed' => (int)$provider->max_advertised_upstream_speed_mbps,
                                'cost' => $fcc_provider['i_cost'],
                                'order' => $fcc_provider['order'],
                                'connection_type' => $provider->connection_type
                            ];
                        }
                   }
                }
            }
        }
        
        $provider_arr['internet'] = array_merge($temp_provider_arr['internet'], $provider_arr['internet']);


        //get all providers for API
        if ($is_city && !$is_programmatic_city_page){
            //if is top city return BD from the DB (cached) and disregard TV and bundles since they're not on the page
            $bd_api_return = $providersDB->getAllBDAPIDataByCity($city, $state);
            $bd_api_return = $bd_api_return[0]->provider_data;
            $bd_api_return = json_decode($bd_api_return, true);
            if (isset($bd_api_return['internet'])){
                foreach ($bd_api_return['internet'] as $provider_name => $bd_provider_arr){
                    $altice_found = false;
                    if ($provider_name == 'AT&T and DIRECTV'){
                        $provider_name = 'AT&T';
                    }
                    if ( $provider_name == 'Earthlink'){
                        continue;
                    }
                    if ($provider_name == 'Earthlink Hyperlink'){
                        $provider_name = 'Earthlink';
                    }
                    foreach($bd_providers as $key => $bd_provider) {
                        if (in_array($provider_name, $bd_provider['possible_names'])) {
                            if ($provider_name == 'Altice'){
                                if ($altice_found){
                                    continue;
                                }
                                foreach($internet_providers_arr as $key2=>$fcc_provider){
                                    if (in_array($key2, $bd_provider['possible_names'])){
                                        $altice_found = true;
                                        break;
                                    }
                                }
                            }
                            $provider_id = $key;
                            $provider_order = $bd_provider['order'];
                            $provider_arr['internet'][ $provider_name] = [
                                'name' => $provider_name,
                                'id' => $provider_id,
                                'download_speed' => $bd_provider_arr['download_speed'],
                                'upload_speed' => $bd_provider_arr['upload_speed'],
                                'cost' => $bd_provider_arr['cost'],
                                'order' => $provider_order
                            ];
                        }
                    }
                }
            }
            if (isset($bd_api_return['tv'])){
                foreach ($bd_api_return['tv'] as $provider_name => $bd_provider_arr){
                    $altice_found = false;
                    if ($provider_name == 'AT&T and DIRECTV'){
                        $provider_name = 'AT&T';
                    }
                    if ( $provider_name == 'Earthlink'){
                        continue;
                    }
                    if ($provider_name == 'Earthlink Hyperlink'){
                        $provider_name = 'Earthlink';
                    }
                    foreach($bd_providers as $key => $bd_provider) {
                        if (in_array($provider_name, $bd_provider['possible_names'])) {
                            if ($provider_name == 'Altice'){
                                if ($altice_found){
                                    continue;
                                }
                                foreach($internet_providers_arr as $key2=>$fcc_provider){
                                    if (in_array($key2, $bd_provider['possible_names'])){
                                        $altice_found = true;
                                        break;
                                    }
                                }
                            }
                            $provider_id = $key;
                            $provider_order = $bd_provider['order'];
                            $provider_arr['tv'][ $provider_name] = [
                                'name' => $provider_name,
                                'id' => $provider_id,
                                'channels' => $bd_provider_arr['channels'],
                                'cost' => $bd_provider_arr['cost'],
                                'order' => $provider_order
                            ];
                        }
                    }
                }
            }
            if (isset($bd_api_return['bundles'])){
                foreach ($bd_api_return['bundles'] as $provider_name => $bd_provider_arr){
                    $altice_found = false;
                    if ($provider_name == 'AT&T and DIRECTV'){
                        $provider_name = 'AT&T';
                    }
                    if ( $provider_name == 'Earthlink'){
                        continue;
                    }
                    if ($provider_name == 'Earthlink Hyperlink'){
                        $provider_name = 'Earthlink';
                    }
                    foreach($bd_providers as $key => $bd_provider) {
                        if (in_array($provider_name, $bd_provider['possible_names'])) {
                            if ($provider_name == 'Altice'){
                                if ($altice_found){
                                    continue;
                                }
                                foreach($internet_providers_arr as $key2=>$fcc_provider){
                                    if (in_array($key2, $bd_provider['possible_names'])){
                                        $altice_found = true;
                                        break;
                                    }
                                }
                            }
                            $provider_id = $key;
                            $provider_order = $bd_provider['order'];
                            $provider_arr['bundles'][ $provider_name] = [
                                'name' => $provider_name,
                                'id' => $provider_id,
                                'download_speed' => $bd_provider_arr['download_speed'],
                                'channels' => $bd_provider_arr['channels'],
                                'cost' => $bd_provider_arr['cost'],
                                'order' => $provider_order
                            ];
                        }
                    }
                }
            }
        } else {

            $api_connection = new BDAPIConnection();

            $api = $api_connection->get_api_providers_by_zip($zipcode, $auth);
            if (is_array($api) && isset($api['AvailableProducts'])){
                foreach ($api['AvailableProducts'] as $provider){
                    //ignore earthlinkv6
                    if ( isset($provider['Provider']['ProviderCode']) && $provider['Provider']['ProviderCode'] == 'EARTHLINKv6'){
                        continue;
                    }
                    if (isset($provider['Provider']['ProviderName']) && $provider['Provider']['ProviderName'] == 'Earthlink Hyperlink'){
                        $provider['Provider']['ProviderName'] = 'Earthlink';
                    }
                    if (isset($provider['Provider']['ProviderName'])){
                        $found = false;
                        $altice_found = false;
                        foreach($bd_providers as $key => $bd_provider) {
                            if ($altice_found){
                                break;
                            }
                            if (in_array($provider['Provider']['ProviderName'], $bd_provider['possible_names'])) {
                                $found = true;
                                $provider_id = $key;
                                $provider_order = $bd_provider['order'];
                                //if the provider returned is altice, check the FCC data to see if it's suddenlink or optimum
                                if ($provider['Provider']['ProviderName'] == 'Altice'){
                                    $found = false;
                                    foreach($internet_providers_arr as $key2=>$fcc_provider){
                                        if (in_array($key2, $bd_provider['possible_names'])){
                                            $altice_found = true;
                                            $found = true;
                                        }
                                    }
                                }
                            }
                        }
                        if ($found == true){
                            if (isset($provider['Products']) && is_array($provider['Products'])){
                                $download_speed = 0;
                                $upload_speed = 0;
                                $i_cost = 9999999999;
                                $channels = 0;
                                $tv_cost = 9999999999;
                                $b_download_speed = 0;
                                $b_channels = 0;
                                $b_cost = 9999999999;
                                foreach ($provider['Products'] as $product){

                                    if ($product['VerticalName'] == 'Internet'){

                                        if ($provider['Provider']['ProviderName'] == 'AT&T and DIRECTV'){
                                            $i_provider_name = 'AT&T';
                                            foreach($bd_providers as $key => $bd_provider) {
                                               if (in_array($i_provider_name, $bd_provider['possible_names'])) {
                                                  $provider_id = $key;
                                                  $provider_order = $bd_provider['order'];
                                               }
                                            }
                                        } else {
                                            $i_provider_name = $provider['Provider']['ProviderName'];
                                        }

                                        if (is_array($product['FeatureList'])){
                                            foreach($product['FeatureList'] as $featurelist){
                                                $lc_featurelist_name = strtolower($featurelist['Name']);
                                                $lc_featurelist_val = strtolower($featurelist['Value']);
                                                if ( $lc_featurelist_name == 'download speed'){
                                                    $download_speed = $featurelist['Value'];
                                                    if (strpos($lc_featurelist_val, 'gb') !== false || strpos($lc_featurelist_val, 'gbps') !== false){
                                                        $download_speed = preg_replace('/[^0-9\.]/', '',$download_speed);
                                                        $download_speed = $download_speed * 1000;
                                                    } elseif (strpos($lc_featurelist_val, 'kb') !== false || strpos($lc_featurelist_val, 'kbs') !== false || strpos($lc_featurelist_val, 'kbps') !== false){
                                                        $download_speed = preg_replace('/[^0-9\.]/', '',$download_speed);
                                                        $download_speed = $download_speed / 1000;
                                                    } else {
                                                        $download_speed = preg_replace('/[^0-9\.]/', '',$download_speed);
                                                    }
                                                }
                                                if ($lc_featurelist_name == 'upload speed' || $lc_featurelist_name == 'upstream speed'){
                                                    $upload_speed = $featurelist['Value'];
                                                    if (strpos($lc_featurelist_val, 'gb') !== false || strpos($lc_featurelist_val, 'gbps') !== false){
                                                        $upload_speed = preg_replace('/[^0-9\.]/', '',$upload_speed);
                                                        $upload_speed = $upload_speed * 1000;
                                                    } elseif (strpos($lc_featurelist_val, 'kb') !== false || strpos($lc_featurelist_val, 'kbs') !== false || strpos($lc_featurelist_val, 'kbps') !== false){
                                                        $upload_speed = preg_replace('/[^0-9\.]/', '',$upload_speed);
                                                        $upload_speed = $upload_speed / 1000;
                                                    } else {
                                                        $upload_speed = preg_replace('/[^0-9\.]/', '',$upload_speed);
                                                    }
                                                }
                                            }
                                        }
                                        if (!isset($provider_arr['internet'][$i_provider_name])){
                                            if ($product['BasePrice']['InitialAmount'] == 0){
                                                $product['BasePrice']['InitialAmount'] = $i_cost;
                                            }
                                            $provider_arr['internet'][ $i_provider_name] = [
                                                'name' => $i_provider_name,
                                                'id' => $provider_id,
                                                'download_speed' => $download_speed,
                                                'upload_speed' => $upload_speed,
                                                'cost' => $product['BasePrice']['InitialAmount'],
                                                'order' => $provider_order
                                            ];
                                        } else {
                                            if ($download_speed > $provider_arr['internet'][$i_provider_name]['download_speed']){
                                                $provider_arr['internet'][$i_provider_name]['download_speed'] = $download_speed;
                                            }
                                            if ($upload_speed > $provider_arr['internet'][$i_provider_name]['upload_speed']){
                                                $provider_arr['internet'][$i_provider_name]['upload_speed'] = $upload_speed;
                                            }
                                            if ($product['BasePrice']['InitialAmount'] != 0 && $product['BasePrice']['InitialAmount'] < $provider_arr['internet'][$i_provider_name]['cost']){
                                                $provider_arr['internet'][$i_provider_name]['cost'] = $product['BasePrice']['InitialAmount'];
                                            }
                                        }
                                    } elseif ($product['VerticalName'] == 'TV'){

                                        if ($provider['Provider']['ProviderName'] == 'AT&T and DIRECTV'){
                                            if ($product['CategoryCode'] == 'DTV'){
                                                $t_provider_name = 'DIRECTV';
                                            } else {
                                                $t_provider_name = 'AT&T';
                                            }
                                            foreach($bd_providers as $key => $bd_provider) {
                                               if (in_array($t_provider_name, $bd_provider['possible_names'])) {
                                                  $provider_id = $key;
                                                  $provider_order = $bd_provider['order'];
                                               }
                                            }
                                        } else {
                                            $t_provider_name = $provider['Provider']['ProviderName'];
                                        }

                                        if (is_array($product['FeatureList'])){
                                            foreach($product['FeatureList'] as $featurelist){
                                                if ($featurelist['Name'] == 'No of Channels' || $featurelist['Name'] == 'Number Of Channels'){
                                                    $channels = $featurelist['Value'];
                                                    $pieces = explode(' ', $channels);
                                                    foreach($pieces as $piece){
                                                        $mod_piece = preg_replace('/[^0-9]/', '', $piece);
                                                        if ((int)$mod_piece > 10){
                                                            $channels = $mod_piece;
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        if (!isset($provider_arr['tv'][$t_provider_name])){

                                            if ($product['BasePrice']['InitialAmount'] == 0){
                                                $product['BasePrice']['InitialAmount'] = $tv_cost;
                                            }
                                            $provider_arr['tv'][ $t_provider_name] = [
                                                'name' => $t_provider_name,
                                                'id' => $provider_id,
                                                'channels' => $channels,
                                                'cost' => $product['BasePrice']['InitialAmount'],
                                                'order' => $provider_order
                                            ];
                                        } else {
                                            if ($channels > $provider_arr['tv'][$t_provider_name]['channels']){
                                                $provider_arr['tv'][$t_provider_name]['channels'] = $channels;
                                            }
                                            if ($product['BasePrice']['InitialAmount'] != 0 && $product['BasePrice']['InitialAmount'] < $provider_arr['tv'][$t_provider_name]['cost']){
                                                $provider_arr['tv'][$t_provider_name]['cost'] = $product['BasePrice']['InitialAmount'];
                                            }
                                        }
                                    } elseif ($product['VerticalName'] == 'Bundles'){
                                        $phone_key = array_search('Phone', array_column($product['ComponentList'], 'VerticalName'));
                                        if ($phone_key !== false){
                                            continue;
                                        }
                                        if ($provider['Provider']['ProviderName'] == 'AT&T and DIRECTV'){
                                            $b_provider_name = 'AT&T';
                                            foreach($bd_providers as $key => $bd_provider) {
                                               if (in_array($b_provider_name, $bd_provider['possible_names'])) {
                                                  $provider_id = $key;
                                                  $provider_order = $bd_provider['order'];
                                               }
                                            }
                                        } else {
                                            $b_provider_name = $provider['Provider']['ProviderName'];
                                        }
                                        if (is_array($product['FeatureList'])){
                                            foreach($product['FeatureList'] as $featurelist){
                                                $lc_featurelist_name = strtolower($featurelist['Name']);
                                                $lc_featurelist_val = strtolower($featurelist['Value']);
                                                if ($lc_featurelist_name == 'download speed'){
                                                    $b_download_speed = $featurelist['Value'];
                                                    if (strpos($lc_featurelist_val, 'gb') !== false || strpos($lc_featurelist_val, 'gbps') !== false){
                                                        $b_download_speed = preg_replace('/[^0-9\.]/', '',$b_download_speed);
                                                        $b_download_speed = $b_download_speed * 1000;
                                                    } elseif (strpos($lc_featurelist_val, 'kb') !== false || strpos($lc_featurelist_val, 'kbps') !== false || strpos($lc_featurelist_val, 'kbs') !== false){
                                                        $b_download_speed = preg_replace('/[^0-9\.]/', '',$b_download_speed);
                                                        $b_download_speed = $b_download_speed / 1000;
                                                    } else {
                                                        $b_download_speed = preg_replace('/[^0-9\.]/', '',$b_download_speed);
                                                    }
                                                }
                                                if ($featurelist['Name'] == 'No of Channels' || $featurelist['Name'] == 'Number Of Channels'){
                                                    $b_channels = $featurelist['Value'];
                                                    $pieces = explode(' ', $b_channels);
                                                    foreach($pieces as $piece){
                                                        $mod_piece = preg_replace('/[^0-9]/', '', $piece);
                                                        if ((int)$mod_piece > 10){
                                                            $b_channels = $mod_piece;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        if (!isset($provider_arr['bundles'][$b_provider_name])){
                                            if ($product['BasePrice']['InitialAmount'] == 0){
                                                $product['BasePrice']['InitialAmount'] = $b_cost;
                                            }
                                            $provider_arr['bundles'][ $b_provider_name] = [
                                                'name' => $b_provider_name,
                                                'id' => $provider_id,
                                                'download_speed' => $b_download_speed,
                                                'channels' => $b_channels,
                                                'cost' => $product['BasePrice']['InitialAmount'],
                                                'order' => $provider_order
                                            ];
                                        } else {
                                            if ($b_download_speed > $provider_arr['bundles'][$b_provider_name]['download_speed']){
                                                $provider_arr['bundles'][$b_provider_name]['download_speed'] = $b_download_speed;
                                            }
                                            if ($b_channels > $provider_arr['bundles'][$b_provider_name]['channels']){
                                                $provider_arr['bundles'][$b_provider_name]['channels'] = $b_channels;
                                            }
                                            if ($product['BasePrice']['InitialAmount'] != 0 && $product['BasePrice']['InitialAmount'] < $provider_arr['bundles'][$b_provider_name]['cost']){
                                                $provider_arr['bundles'][$b_provider_name]['cost'] = $product['BasePrice']['InitialAmount'];
                                            }
                                        } 
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        //Fill Out Bundles if Data is Available
        if(!$provider_arr['bundles'] && $provider_arr['internet']) {
            $tv_found = false;
            foreach($provider_arr['internet'] as $iid) {
                //See if internet provider has tv
                $key = array_search($iid['name'], array_column($provider_arr['tv'], 'name'));
                if($key) {
                    //error_log(print_r('Found Same Provider: ' . $key, TRUE));
                    $tv_found = true;
                    $provider_arr['bundles'][] = [
                        'name' => $iid['name'],
                        'id' => $iid['id'],
                        'download_speed' => $iid['download_speed'],
                        'upload_speed' => $iid['upload_speed'],
                        'channels' => $provider_arr['tv'][$key]['channels'],
                        'cost' => $iid['cost'],
                        'order' => $iid['order']
                    ];
                }
            }
            if(!$tv_found) {
                foreach($provider_arr['internet'] as $iid) {
                    $provider_arr['bundles'][] = [
                        'name' => $iid['name'],
                        'id' => $iid['id'],
                        'download_speed' => $iid['download_speed'],
                        'upload_speed' => $iid['upload_speed'],
                        'channels' => $iid['channels'],
                        'cost' => $iid['cost'],
                        'order' => $iid['order']
                    ];
                }
            }
        }


        usort($provider_arr['internet'], function($a, $b) {
            return $b['download_speed'] <=> $a['download_speed'];
        });
        usort($provider_arr['internet'], function($a, $b) {
            if ($a['order'] === $b['order']) return 0;
            if ($a['order'] === 0) return 1;
            if ($b['order'] === 0) return -1;
            return $a['order'] > $b['order'] ? 1 : -1;
        });
        usort($provider_arr['tv'], function($a, $b) {
            return $b['channels'] <=> $a['channels'];
        });
        usort($provider_arr['tv'], function($a, $b) {
            if ($a['order'] === $b['order']) return 0;
            if ($a['order'] === 0) return 1;
            if ($b['order'] === 0) return -1;
            return $a['order'] > $b['order'] ? 1 : -1;
        });
        usort($provider_arr['bundles'], function($a, $b) {
            return $b['download_speed'] <=> $a['download_speed'];
        });
        usort($provider_arr['bundles'], function($a, $b) {
            if ($a['order'] === $b['order']) return 0;
            if ($a['order'] === 0) return 1;
            if ($b['order'] === 0) return -1;
            return $a['order'] > $b['order'] ? 1 : -1;
        });
        foreach ($provider_arr['internet'] as $key => $internet){
            if ($internet['download_speed'] === 0 || $internet['download_speed'] === 'N/A' || $internet['download_speed'] === null || $internet['download_speed'] === ''){
                $provider_arr['internet'][$key]['download_speed'] = 'N/A';
            } elseif ($internet['download_speed'] < 1){
                $provider_arr['internet'][$key]['download_speed'] = '< 1';
            } else {
                $provider_arr['internet'][$key]['download_speed'] = round($internet['download_speed'], 0);
            }
            if ($internet['upload_speed'] === 0 || $internet['upload_speed'] === 'N/A' || $internet['upload_speed'] === null || $internet['upload_speed'] === ''){
                $provider_arr['internet'][$key]['upload_speed'] = 'N/A';
            } elseif ($internet['upload_speed'] < 1){
                $provider_arr['internet'][$key]['upload_speed'] = '< 1';
            } else {
                $provider_arr['internet'][$key]['upload_speed'] = round($internet['upload_speed'], 0);
            }
            if ($internet['cost'] == 9999999999 || $internet['cost'] == 'N/A'){
                $provider_arr['internet'][$key]['cost'] = 'N/A';
            } else {
                $provider_arr['internet'][$key]['cost'] = number_format((float)$provider_arr['internet'][$key]['cost'], 2, '.', '');
            }
            if ($provider_arr['internet'][$key]['download_speed'] == 'N/A' && $provider_arr['internet'][$key]['upload_speed'] == 'N/A' && $provider_arr['internet'][$key]['cost'] == 'N/A' && $provider_arr['internet'][$key]['name'] != 'Frontier'){
                unset($provider_arr['internet'][$key]);
            }
        }
        foreach ($provider_arr['tv'] as $key => $tv){
            if ($tv['channels'] == 0){
                $provider_arr['tv'][$key]['channels'] = 'N/A';
            }
            if ($tv['cost'] == 9999999999 || $tv['cost'] == 'N/A'){
                $provider_arr['tv'][$key]['cost'] = 'N/A';
            } else {
                $provider_arr['tv'][$key]['cost'] = number_format((float)$provider_arr['tv'][$key]['cost'], 2, '.', '');
            }
            if ($provider_arr['tv'][$key]['channels'] == 'N/A' && $provider_arr['tv'][$key]['cost'] == 'N/A'){
                unset($provider_arr['tv'][$key]);
            }
        }
        foreach ($provider_arr['bundles'] as $key => $bundles){
            if ($bundles['download_speed'] === 0 || $bundles['download_speed'] === 'N/A' || $bundles['download_speed'] === null || $bundles['download_speed'] === ''){
                $provider_arr['bundles'][$key]['download_speed'] = 'N/A';
            } elseif ($bundles['download_speed'] < 1){
                $provider_arr['bundles'][$key]['download_speed'] = '< 1';
            } else {
                $provider_arr['bundles'][$key]['download_speed'] = round($bundles['download_speed'], 0);
            }
            if ($bundles['channels'] == 0){
                $provider_arr['bundles'][$key]['channels'] = 'N/A';
            }
            if ($bundles['cost'] == 9999999999 || $bundles['cost'] == 'N/A'){
                $provider_arr['bundles'][$key]['cost'] = 'N/A';
            } else {
                $provider_arr['bundles'][$key]['cost'] = number_format((float)$provider_arr['bundles'][$key]['cost'], 2, '.', '');
            }
            if ($provider_arr['bundles'][$key]['download_speed'] == 'N/A' && $provider_arr['bundles'][$key]['channels'] == 'N/A' && $provider_arr['bundles'][$key]['cost'] == 'N/A'){
                unset($provider_arr['bundles'][$key]);
            }
        }

        return $provider_arr;
    }

    static function format_phone_number($number){
        $phone = preg_replace("/[^\d]/", "", $number);
        return preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $phone);
    }
    static function isValidZipCode($zipCode) {
        return (preg_match('/^[0-9]{5}(-[0-9]{4})?$/', $zipCode)) ? true : false;
    }
    static function getZipType(){
        $type = get_post_field( 'post_name', get_post() );
        if ($type != 'internet' && $type != 'tv' && $type != 'bundle'){
            $type = 'internet';
        }
        return $type;
    }
    static function get_auth(){
        // $task = new VaultTask();
        // $task->unseal();
        // $response = $task->get_auth();
        $dotenv = DotEnv::createUnsafeImmutable(ABSPATH);
        $dotenv->load();
        $json = base64_decode(getenv('ZIP_AUTH'));
        $auth_arr = json_decode($json, true);
        return $auth_arr;
    }

}
