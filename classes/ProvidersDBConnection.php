<?php
namespace ZipSearch;

class ProvidersDBConnection {

    /**
     * PDO object
     * @var $pdo
     */
    private $internet_providers_table_name;
    // private $tv_providers_table_name;
    // private $provider_id_table_name;
    private $zip_uploads_table_name;
    private $bd_api_table_name;
    private $wpdb;
    private $zipcode;

    /**
     * init the object with a \PDO object
     */
    public function __construct($zipcode = '') {

        global $wpdb;
        $this->wpdb = $wpdb;
        $this->internet_providers_table_name = $wpdb->prefix . "broadband_hso";
        // $this->tv_providers_table_name = $wpdb->prefix . "tv_zip";
        // $this->provider_id_table_name = $wpdb->prefix . "provider";
        $this->zip_uploads_table_name = $wpdb->prefix . "zip_uploads";
        $this->bd_api_table_name = $wpdb->prefix . "city_api_call";
        $this->zipcode = $zipcode;

    }

    /**
     * return table in the database
     */
    private function filterInternetProvidersReturn($providers=[]) {

        $filtered_providers = [];
        foreach($providers as $provider){
            if (!isset($filtered_providers[$provider->hso_provider])){
                $filtered_providers[$provider->hso_provider] = $provider;
            } else {
                if ($filtered_providers[$provider->hso_provider]->max_advertised_downstream_speed_mbps < $provider->max_advertised_downstream_speed_mbps){
                    $filtered_providers[$provider->hso_provider]->max_advertised_downstream_speed_mbps = $provider->max_advertised_downstream_speed_mbps;
                }
                if ($filtered_providers[$provider->hso_provider]->max_advertised_upstream_speed_mbps < $provider->max_advertised_upstream_speed_mbps){
                    $filtered_providers[$provider->hso_provider]->max_advertised_upstream_speed_mbps = $provider->max_advertised_upstream_speed_mbps;
                }
            }
        }

        return $filtered_providers;
    }

    /**
     * return table in the database
     */
    //no longer using tv table as it's probably not accurate

    // private function filterTvProvidersReturn($providers=[]) {

    //     $filtered_providers = [];
    //     foreach($providers as $provider){
    //         $provider_name = $this->getProviderNameById($provider->provider_id);
    //         $filtered_providers[$provider->provider_id]['name'] = $provider_name;
    //     }

    //     return $filtered_providers;
    // }

    /**
     * return table in the database
     */
    public function getAllInternetProviderData() {

        if (!$this->internet_providers_table_name){
            return "Error establishing connection with database";
        }

        //Dump all data for a city
        $sql = "SELECT * FROM $this->internet_providers_table_name WHERE zip_code='$this->zipcode'";
        $providers = $this->wpdb -> get_results($sql);

        $providers = $this->filterInternetProvidersReturn($providers);
        return $providers;
    }
    /**
     * return table in the database
     */
    public function getAllInternetProviderDataByCity($zip_arr) {

        if (!$this->internet_providers_table_name){
            return "Error establishing connection with database";
        }
        $zips = implode(', ', $zip_arr);
        //Dump all data for a city
        $sql = "SELECT * FROM $this->internet_providers_table_name WHERE zip_code IN ($zips);";
        $providers = $this->wpdb -> get_results($sql);

        $providers = $this->filterInternetProvidersReturn($providers);
        return $providers;
    }
    /**
     * return table in the database
     */
    public function getAllZipUploadProviderData() {
        //Dump all data for a city
        $sql = "SELECT * FROM $this->zip_uploads_table_name WHERE zip_code='$this->zipcode'";
        $providers = $this->wpdb -> get_results($sql);

        return $providers;
    }
    /**
     * return table in the database
     */
    public function getAllZipUploadProviderDataByCity($zip_arr) {

        $zips = implode(', ', $zip_arr);
        //Dump all data for a city
        $sql = "SELECT * FROM $this->zip_uploads_table_name WHERE zip_code IN ($zips);";
        $providers = $this->wpdb -> get_results($sql);

        return $providers;
    }
    /**
     * return table in the database
     */
    public function getAllBDAPIDataByCity($city, $state) {

        //Dump all data for a city
        $sql = "SELECT * FROM $this->bd_api_table_name WHERE city='$city' AND state='$state';";
        $providers = $this->wpdb -> get_results($sql);

        return $providers;
    }

    public function getTractsByState($state){
        $table_name = $this->wpdb->prefix . "zip_tract";
        $tracts_query = "SELECT tract FROM $table_name WHERE usps_zip_pref_state = '$state'";
        $row = $this->wpdb -> get_results($tracts_query);
        $tract_arr = [];
        foreach($row as $tract){
            $tract_arr[] = "'".$tract->tract."'";
        }
        $tract_arr = array_unique($tract_arr);
        return $tract_arr;
    }
    
    public function getTractsByCity($city, $state){
        $table_name = $this->wpdb->prefix . "zip_tract";
        $tracts_query = "SELECT tract FROM $table_name WHERE usps_zip_pref_city = '$city' AND usps_zip_pref_state = '$state'";
        $row = $this->wpdb -> get_results($tracts_query);
        $tract_arr = [];
        foreach($row as $tract){
            $tract_arr[] = "'".$tract->tract."'";
        }
        $tract_arr = array_unique($tract_arr);
        return $tract_arr;
    }
    public function getZipsByCity($city, $state){
        $table_name = $this->wpdb->prefix . "zip_tract";
        $zips_query = "SELECT zip FROM $table_name WHERE usps_zip_pref_city = '$city' AND usps_zip_pref_state = '$state'";
        $row = $this->wpdb -> get_results($zips_query);

        $zip_arr = [];
        foreach($row as $zip){
            $zip_arr[] = "'".$zip->zip."'";
        }
        $zip_arr = array_unique($zip_arr);
        $zip_arr = array_values($zip_arr);



        return $zip_arr;
    }
    //return zips by city without quotes for db query
    public function getZipsByCityWithoutQuotes($city, $state){
        $table_name = $this->wpdb->prefix . "zip_tract";
        $zips_query = "SELECT zip FROM $table_name WHERE usps_zip_pref_city = '$city' AND usps_zip_pref_state = '$state'";
        $row = $this->wpdb -> get_results($zips_query);

        $zip_arr = [];
        foreach($row as $zip){
            $zip_arr[] = $zip->zip;
        }
        $zip_arr = array_unique($zip_arr);
        $zip_arr = array_values($zip_arr);



        return $zip_arr;
    }
    //return zips by city without quotes for db query
    public function getCityPopulation($city, $state){
        $table_name = $this->wpdb->prefix . "population";
        $pop_query = "SELECT population FROM $table_name WHERE city = '$city' AND state = '$state' LIMIT 1";
        $row = $this->wpdb -> get_results($pop_query);
        if (empty($row)){
            //if no city is found, try adding ship to the end (ssome townships have this)
            $city = $city.'ship';
            $pop_query = "SELECT population FROM $table_name WHERE city = '$city' AND state = '$state' LIMIT 1";
            $row = $this->wpdb -> get_results($pop_query);
        }
        if (empty($row)){
            return 0;
        }

        return $row[0]->population;
    }

    /**
     * return table in the database
     */
    public function getHighestDownloadSpeedWithProvider($zip_arr) {
        $zips = implode(', ', $zip_arr);
        //Dump all data for a city
        $sql = "SELECT hso_provider, MAX(max_advertised_downstream_speed_mbps) as max_downstream_speed FROM $this->zip_uploads_table_name WHERE zip_code IN ($zips) LIMIT 1;";
        $providers = $this->wpdb -> get_results($sql);

        if (empty($providers) || $providers[0]->max_downstream_speed == 0){
            $sql = "SELECT hso_provider, MAX(max_advertised_downstream_speed_mbps) as max_downstream_speed FROM $this->internet_providers_table_name WHERE zip_code IN ($zips) LIMIT 1;";
            $providers = $this->wpdb -> get_results($sql);
        }
        add_filter('posts_where', 'my_posts_where');

        // args
        $args = array(
            'numberposts'   => 1,
            'post_type'     => 'provider',
            'suppress_filters' => false,
            'fields'        => 'ids',
            'meta_query'    => array(
                array(
                    'key'       => 'possible_provider_names_$_name',
                    'compare'   => 'IN',
                    'value'     => $providers[0]->hso_provider,
                ),
            )
        );
        $wp_providers = get_posts($args);
        if (!empty($wp_providers)){
            $providers[0]->hso_provider = get_the_title($wp_providers[0]);
        }

        return $providers[0];
    }

    
}