<?php

add_action("after_switch_theme", "hso_create_extra_table");
function hso_create_extra_table(){
    global $wpdb;
    set_time_limit(0);

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $table_name = $wpdb->prefix . 'fcc_provider_data';
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);

    $table_name = $wpdb->prefix . "broadband_hso";

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id int(10) unsigned NOT NULL AUTO_INCREMENT,
        provider_name varchar(255) NOT NULL,
        dba_name varchar(255) NOT NULL,
        holding_company_name varchar(255) NOT NULL,
        holding_company_final varchar(255) NOT NULL,
        state varchar(2) NOT NULL,
        technology_code int(11) NOT NULL,
        consumer int(1),
        max_advertised_downstream_speed_mbps int(9),
        max_advertised_upstream_speed_mbps int(9),
        census_block_fips_code_11 varchar(11) NOT NULL,
        zip_code int(5) NOT NULL,
        connection_type varchar(255),
        hso_provider varchar(255),
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    dbDelta( $sql );

    $sql_check_query = "SELECT (id) FROM $table_name LIMIT 1;";
    $sql_check = $wpdb -> get_results($sql_check_query);

    $file1 = get_template_directory_uri().'/broadband_hso_1.csv';
    $file2 = get_template_directory_uri().'/broadband_hso_2.csv';
    if (!$sql_check){
        $sql="
            LOAD DATA LOCAL INFILE '$file1' INTO TABLE $table_name
            FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
            LINES TERMINATED BY '\n'
            IGNORE 1 LINES
            (holding_company_final,connection_type,hso_provider,technology_code,max_advertised_upstream_speed_mbps,dba_name,zip_code,max_advertised_downstream_speed_mbps,state,provider_name,census_block_fips_code_11,holding_company_name,consumer);
            ";
        $query = $wpdb->query($sql);
        $sql="
            LOAD DATA LOCAL INFILE '$file2' INTO TABLE $table_name
            FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
            LINES TERMINATED BY '\n'
            IGNORE 1 LINES
            (holding_company_final,connection_type,hso_provider,technology_code,max_advertised_upstream_speed_mbps,dba_name,zip_code,max_advertised_downstream_speed_mbps,state,provider_name,census_block_fips_code_11,holding_company_name,consumer);
            ";
        $query = $wpdb->query($sql);
    }

    $table_name = $wpdb->prefix . "zip_uploads";

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id int(10) unsigned NOT NULL AUTO_INCREMENT,
        provider_name varchar(255) NOT NULL,
        dba_name varchar(255) NOT NULL,
        holding_company_name varchar(255) NOT NULL,
        holding_company_final varchar(255) NOT NULL,
        state varchar(2) NOT NULL,
        technology_code int(11) NOT NULL,
        consumer int(1),
        max_advertised_downstream_speed_mbps int(9),
        max_advertised_upstream_speed_mbps int(9),
        zip_code int(5) NOT NULL,
        connection_type varchar(255),
        hso_provider varchar(255),
        price varchar(255),
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    dbDelta( $sql );

    $table_name = $wpdb->prefix . "city_api_call";

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id int(10) unsigned NOT NULL AUTO_INCREMENT,
        city varchar(255) NOT NULL,
        state varchar(255) NOT NULL,
        provider_data longtext,
        PRIMARY KEY  (id),
        CONSTRAINT unique_city UNIQUE (city,state)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    dbDelta( $sql );

    $table_name = $wpdb->prefix . "bd_api";

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id int(10) unsigned NOT NULL AUTO_INCREMENT,
        zip int(5) NOT NULL,
        plan_data longtext,
        PRIMARY KEY  (id),
        CONSTRAINT unique_zip UNIQUE (zip)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    dbDelta( $sql );

    $table_name = $wpdb->prefix . "zip_tract";

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id int(10) unsigned NOT NULL AUTO_INCREMENT,
        zip int(5) NOT NULL,
        tract varchar(255) NOT NULL,
        usps_zip_pref_city varchar(255) NOT NULL,
        usps_zip_pref_state varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    dbDelta( $sql );

    $table_name = $wpdb->prefix . "city_to_geoid";

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        geoid int(10) unsigned NOT NULL AUTO_INCREMENT,
        type varchar(255) NOT NULL,
        name varchar(255) NOT NULL,
        PRIMARY KEY  (geoid)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    dbDelta( $sql );

    //removing tv and provider tables as tv table is probably not accurate and we only use provider table for tv table
    $table_name = $wpdb->prefix . 'tv_zip';
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);

    $table_name = $wpdb->prefix . 'provider';
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);


    //create providers table if it doesnt exist
    $table_name = $wpdb->prefix . "population";

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id int(10) unsigned NOT NULL AUTO_INCREMENT,
        city varchar(255) NOT NULL,
        state varchar(255) NOT NULL,
        population varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    dbDelta( $sql );

    $zip_to_tract = get_template_directory_uri().'/zip_to_tract.csv';
    $table_name = $wpdb->prefix . "zip_tract";
    $sql_check_query = "SELECT (id) FROM $table_name LIMIT 1;";
    $sql_check = $wpdb -> get_results($sql_check_query);
    if (!$sql_check){
        $sql="
            LOAD DATA LOCAL INFILE '$zip_to_tract' INTO TABLE $table_name
            FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
            LINES TERMINATED BY '\n'
            IGNORE 1 LINES
            (zip,tract,usps_zip_pref_city,usps_zip_pref_state,@dummy,@dummy,@dummy,@dummy);
            ";
        $query = $wpdb->query($sql);
    }
    $zip_to_tract = get_template_directory_uri().'/city_to_geoid.csv';
    $table_name = $wpdb->prefix . "city_to_geoid";
    $sql_check_query = "SELECT (geoid) FROM $table_name LIMIT 1;";
    $sql_check = $wpdb -> get_results($sql_check_query);
    if (!$sql_check){
        $sql="
            LOAD DATA LOCAL INFILE '$zip_to_tract' INTO TABLE $table_name
            FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
            LINES TERMINATED BY '\n'
            IGNORE 1 LINES
            (@dummy,geoid,type,name,@dummy,@dummy,@dummy);
            ";
        $query = $wpdb->query($sql);
    }

    $population_zip = get_template_directory_uri().'/population.csv';
    $table_name = $wpdb->prefix . "population";
    $sql_check_query = "SELECT (id) FROM $table_name LIMIT 1;";
    $sql_check = $wpdb -> get_results($sql_check_query);
    if (!$sql_check){
        $sql="
            LOAD DATA LOCAL INFILE '$population_zip' INTO TABLE $table_name
            FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
            LINES TERMINATED BY '\n'
            IGNORE 1 LINES
            (@dummy,@dummy,@dummy,@dummy,@dummy,@dummy,@dummy,@dummy,city,state,@dummy,@dummy,@dummy,@dummy,@dummy,@dummy,@dummy,@dummy,@dummy,@dummy,@dummy,@dummy,@dummy,population);
            ";
        $query = $wpdb->query($sql);
    }

    //Create table for state data since the queries were so large
    $state_table = false;

    if($state_table) {

        $table_name = $wpdb->prefix . "state_provider";

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            geoid int(10) unsigned NOT NULL,
            state varchar(255) NOT NULL,
            stateabbr varchar(255) NOT NULL,
            PRIMARY KEY  (geoid)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        dbDelta( $sql );

        //Populate table with state geoids
        $table_insert = $wpdb->prefix . "state_provider";
        $table_name = $wpdb->prefix . "city_to_geoid";
        // $sql_insert = "INSERT INTO $table_insert (geoid,state)
        //     SELECT geoid,name FROM $table_name WHERE type = 'state';";

        // $query = $wpdb->query($sql_insert);

        $get_states = "SELECT stateabbr from $table_insert";
        $states = $wpdb -> get_results($get_states);
        //error_log( print_r($states, TRUE) );

        $tract_arr = [];
        foreach($states as $state) {
            $state_abbr = $state->stateabbr;
            $table_name = $wpdb->prefix . "zip_tract";
            $tracts_query = "SELECT tract FROM $table_name WHERE usps_zip_pref_state = '$state_abbr'";
            $row = $wpdb -> get_results($tracts_query);
            
            $tracts = [];
            foreach($row as $tract){
                $tracts[] = "'".$tract->tract."'";
            }
            $tracts = array_unique($tracts);
            //$tract_arr[$state_abbr] = $tracts;
            //$tracts = [];

            //GET PROVIDER COUNT
            $tract_where = implode(',', $tracts);
            $provider_table_name = $wpdb->prefix . "broadband_hso";
            $sql = "SELECT COUNT(DISTINCT hso_provider) as provider_count FROM $provider_table_name WHERE census_block_fips_code_11 IN ($tract_where) AND state = '$state_abbr'";
            $provider_count = $wpdb -> get_results($sql);
            $locations_provider_count = $provider_count[0]->provider_count;

            $sql_insert = "UPDATE $table_insert SET provider_count = $locations_provider_count WHERE stateabbr = '$state_abbr'";
            $wpdb->query($sql_insert);

            //GET TOP FIVE PROVIDERS
            $sql = "SELECT COUNT(hso_provider) as hso_provider_count, hso_provider, AVG(max_advertised_downstream_speed_mbps) as avg_dl FROM $provider_table_name WHERE census_block_fips_code_11 IN ($tract_where) AND state = '$state_abbr' GROUP BY hso_provider ORDER BY hso_provider_count DESC";
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

            //remove all providers from fcc prov return that arent in wp backend
            foreach($fcc_provider_return as $key => $fcc_provider){
                if (!in_array($fcc_provider->hso_provider, $all_possible_providers)){
                    unset($fcc_provider_return[$key]);
                }
            }
            $fcc_provider_return = array_values($fcc_provider_return);

            //Slice off top five providers
            $top_five_providers = array_slice($fcc_provider_return, 0, 5);

            $formatted_array = [];

            foreach($top_five_providers as $top) {
                $formatted_array[] = [
                    'name' => $top->hso_provider,
                    'count' => $top->hso_provider_count,
                    'avg_dl' => round($top->avg_dl),
                ];
            }

            $formatted_array = json_encode($formatted_array);
            $sql_insert = "UPDATE $table_insert SET top_five = '$formatted_array' WHERE stateabbr = '$state_abbr'";
            $wpdb->query($sql_insert);
        }
    }

}

function provider_csv_import_page(){
  $form = '
  <form action="" method="post">
    <input type="submit" name="submit" value="Import Provider Table">
  </form>';
  echo $form;
  if (isset($_POST['submit'])){
      global $wpdb;
      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      set_time_limit(0);
      ini_set('memory_limit','450M');
      $path = get_template_directory_uri().'/provider_table.sql';
      if (file_exists($path)){
          $sql = file_get_contents( $path );
          dbDelta( $sql );
      } else {
          echo 'sql file doesn\'t exist';
      }
  }
}