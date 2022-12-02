<?php
namespace ZipSearch;

class BDAPIConnection {

    /**
     * API array
     * @var $api
     */
    private $api;
    private $api_endpoint;

    /**
     * init the object with an api call
     * @param type $zipcode
     */
    public function __construct() {
        
        $this->api_endpoint = 'http://qa.bundledealer.com/qualifynow/Qualify/ZipQualification';
        //temporary endpoint change for dev so liz can test some things
        if('development' === wp_get_environment_type()) {
            $this->api_endpoint = 'http://bundledealer.com/qualifynow/Qualify/ZipQualification';
        }
        if('production' === wp_get_environment_type()) {
            $this->api_endpoint = 'http://bundledealer.com/qualifynow/Qualify/ZipQualification';
        }
    }

    public function get_api_providers_by_zip($zipcode='', $auth=[]){
        $zipcode = str_pad($zipcode, 5, '0', STR_PAD_LEFT);
        connect:
        $curl = curl_init($this->api_endpoint);

        $data = [
            "Header" => [
                "PartnerName" => sanitize_text_field($auth['bd.partner']),
                "AuthToken" =>  sanitize_text_field($auth['bd.token']),
                "RequestType" => "AddressQualification",
                "RequestOriginType" => "CallCentre"
            ],
            "CustomerType" => "New",
            "ZipCode" => $zipcode,
            "SalesAgentCode" => "12",
        ]; 
        $data = json_encode($data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json', 
            )                                                                               
        );
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                                                                     
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);                                                                 
        $return_json = curl_exec($curl);
        $this->storeBDAPIData($zipcode, $return_json);

        $return_array = json_decode($return_json, true);
        if (!$return_array || !is_array($return_array) || empty($return_array)){
            $return_array == false;
        } elseif (isset($return_array['ExceptionMessage'])){
            $return_array = $return_array['ExceptionMessage'];
        } elseif (isset($return_array['Response']) && isset($return_array['Response']['ResponseCode']) && $return_array['Response']['ResponseCode'] == 'Collection was modified; enumeration operation may not execute.'){
            goto connect;
        }
        $this->api = $return_array;
        if (!is_array($this->api)){
            return "Error establishing connection with api";
        }
        return $this->api;
    }

    public function get_api_providers_by_multi_zip($zip_arr=[], $auth=[]){
        $memory_limit = ini_get('memory_limit');
        echo $memory_limit;
        // ini_set('memory_limit','500M');
        // ini_set('max_execution_time', '300');
        // $memory_limit = ini_get('memory_limit');
        // echo $memory_limit;
        $mh = curl_multi_init();
        $content_arr = [];
        for($i=0;$i<count($zip_arr); $i++){
            $zip_arr[$i] = str_pad($zip_arr[$i], 5, '0', STR_PAD_LEFT);
            ${'ch'.$i} = curl_init();

            // set URL and other appropriate options
            curl_setopt(${'ch'.$i}, CURLOPT_URL, $this->api_endpoint);
            $data = [
                "Header" => [
                    "PartnerName" => sanitize_text_field($auth['bd.partner']),
                    "AuthToken" =>  sanitize_text_field($auth['bd.token']),
                    "RequestType" => "AddressQualification",
                    "RequestOriginType" => "CallCentre"
                ],
                "CustomerType" => "New",
                "ZipCode" => $zip_arr[$i],
                "SalesAgentCode" => "12",
            ]; 
            $data = json_encode($data);
            curl_setopt(${'ch'.$i}, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json', 
                )                                                                               
            );
            curl_setopt(${'ch'.$i}, CURLOPT_POST, true);
            curl_setopt(${'ch'.$i}, CURLOPT_POSTFIELDS, $data);                                                                     
            curl_setopt(${'ch'.$i}, CURLOPT_RETURNTRANSFER, true);

            curl_multi_add_handle($mh,${'ch'.$i});
        }
        //execute the multi handle
        do {
            curl_multi_exec($mh, $running);
            curl_multi_select($mh);
        } while ($running > 0);
        $provider_arr = [];
        for($i=0;$i<count($zip_arr);$i++){
            $content = curl_multi_getcontent(${'ch'.$i});
            $content = json_decode($content, true);
            $content = $content['AvailableProducts'];
            if (!empty($content)){
                foreach ($content as $provider){
                    $provider_name = $provider['Provider']['ProviderName'];

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
                                if (!isset($provider_arr['internet'][$provider_name])){
                                    if ($product['BasePrice']['InitialAmount'] == 0){
                                        $product['BasePrice']['InitialAmount'] = $i_cost;
                                    }
                                    $provider_arr['internet'][ $provider_name] = [
                                        'name' => $provider_name,
                                        'download_speed' => $download_speed,
                                        'upload_speed' => $upload_speed,
                                        'cost' => $product['BasePrice']['InitialAmount'],
                                    ];
                                } else {
                                    if ($download_speed > $provider_arr['internet'][$provider_name]['download_speed']){
                                        $provider_arr['internet'][$provider_name]['download_speed'] = $download_speed;
                                    }
                                    if ($upload_speed > $provider_arr['internet'][$provider_name]['upload_speed']){
                                        $provider_arr['internet'][$provider_name]['upload_speed'] = $upload_speed;
                                    }
                                    if ($product['BasePrice']['InitialAmount'] != 0 && $product['BasePrice']['InitialAmount'] < $provider_arr['internet'][$provider_name]['cost']){
                                        $provider_arr['internet'][$provider_name]['cost'] = $product['BasePrice']['InitialAmount'];
                                    }
                                }
                            } elseif ($product['VerticalName'] == 'TV'){

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

                                if (!isset($provider_arr['tv'][$provider_name])){

                                    if ($product['BasePrice']['InitialAmount'] == 0){
                                        $product['BasePrice']['InitialAmount'] = $tv_cost;
                                    }
                                    $provider_arr['tv'][ $provider_name] = [
                                        'name' => $provider_name,
                                        'channels' => $channels,
                                        'cost' => $product['BasePrice']['InitialAmount'],
                                    ];
                                } else {
                                    if ($channels > $provider_arr['tv'][$provider_name]['channels']){
                                        $provider_arr['tv'][$provider_name]['channels'] = $channels;
                                    }
                                    if ($product['BasePrice']['InitialAmount'] != 0 && $product['BasePrice']['InitialAmount'] < $provider_arr['tv'][$provider_name]['cost']){
                                        $provider_arr['tv'][$provider_name]['cost'] = $product['BasePrice']['InitialAmount'];
                                    }
                                }
                            } elseif ($product['VerticalName'] == 'Bundles'){
                                $phone_key = array_search('Phone', array_column($product['ComponentList'], 'VerticalName'));
                                if ($phone_key !== false){
                                    continue;
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
                                if (!isset($provider_arr['bundles'][$provider_name])){
                                    if ($product['BasePrice']['InitialAmount'] == 0){
                                        $product['BasePrice']['InitialAmount'] = $b_cost;
                                    }
                                    $provider_arr['bundles'][ $provider_name] = [
                                        'name' => $provider_name,
                                        'download_speed' => $b_download_speed,
                                        'channels' => $b_channels,
                                        'cost' => $product['BasePrice']['InitialAmount'],
                                    ];
                                } else {
                                    if ($b_download_speed > $provider_arr['bundles'][$provider_name]['download_speed']){
                                        $provider_arr['bundles'][$provider_name]['download_speed'] = $b_download_speed;
                                    }
                                    if ($b_channels > $provider_arr['bundles'][$provider_name]['channels']){
                                        $provider_arr['bundles'][$provider_name]['channels'] = $b_channels;
                                    }
                                    if ($product['BasePrice']['InitialAmount'] != 0 && $product['BasePrice']['InitialAmount'] < $provider_arr['bundles'][$provider_name]['cost']){
                                        $provider_arr['bundles'][$provider_name]['cost'] = $product['BasePrice']['InitialAmount'];
                                    }
                                } 
                            }
                        }
                    }
                }    
            }
            
            curl_multi_remove_handle($mh, ${'ch'.$i});
        }
       
        curl_multi_close($mh);
        $this->api = $provider_arr;
        if (!is_array($this->api)){
            return "Error establishing connection with api";
        }
        return $this->api;
    }

    /**
     * return api array without phone
     */
    public function getWithoutPhone() {

        if (!$this->api || !is_array($this->api)){
            return "Error establishing connection with api";
        }
        $api_arr = $this->api;
        if (is_array($api_arr['AvailableProducts'])){
            $prod_count = count($api_arr['AvailableProducts']);
            if ($prod_count>0){
                for($i=0;$i<$prod_count;$i++){
                    foreach($api_arr['AvailableProducts'][$i]['Products'] as $key=>$value){
                        if ($value['VerticalName'] == 'Phone' || array_search('Phone', array_column($value['ComponentList'], 'VerticalName')) == true ){
                            unset($api_arr['AvailableProducts'][$i]['Products'][$key]);
                        }
                    }
                }
            }
        }

        return $api_arr;

    }

    /**
     * //function to just save the bd api data for a given zip code (to have in case BD API shuts off service)
     */
    public function storeBDAPIData($zip, $data) {
        if (strpos($data, "AvailableProducts") === FALSE) {
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . "bd_api";
        $wpdb->replace( $table_name, 
           array( 
            'zip'  => $zip,
            'plan_data' => $data,
            ), 
           array(
            '%s', '%s')
        );

    }

}