<?php
namespace ZipSearch;

class PostgreSQLConnection {

    /**
     * PDO object
     * @var $pdo
     */
    private $pdo;

    /**
     * init the object with a \PDO object
     */
    public function __construct($auth) {

        $host = 'sandbox.adactionplatform.com';
        if('staging' === wp_get_environment_type() || 'development' === wp_get_environment_type()) {
            $host = 'dev-hso-servicesdb.adactionplatform.com';
        }
        if('production' === wp_get_environment_type()) {
            $host = 'hso-servicesdb.adactionplatform.com';
        }
        // connect to the postgresql database
        $conStr = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s", 
                $host, 
                '5432', 
                'default', 
                sanitize_text_field($auth['primary.datasource.username']), 
                sanitize_text_field($auth['primary.datasource.password']));

        $pdo = new \PDO($conStr);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo = $pdo;
    }

    /**
     * return table in the database
     */
    public function getTable($table) {

        if (!$this->pdo){
            return "Error establishing connection with database";
        }

        $stmt = $this->pdo->query("SELECT * 
                               FROM $table");
        $table_arr = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $table_arr[] = $row;
        }

        return $table_arr;
    }

    /**
     * return table in the database
     */
    public function getByTableAndZip($table, $zipcode) {

        if (!$this->pdo){
            return "Error establishing connection with database";
        }

        $stmt = $this->pdo->query("SELECT provider_id, id
                               FROM $table WHERE zip='$zipcode'");
        $table_arr = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $table_arr[] = $row;
        }

        return $table_arr;
    }

    /**
     * return table in the database
     */
    public function getInternetProvidersByZip($zipcode) {

        if (!$this->pdo){
            return "Error establishing connection with database";
        }
        //query providers and their info but return only the most recent result for each provider
        $stmt = $this->pdo->query("SELECT  provider_id, download_speed, upload_speed, cost, connection_type_id
                FROM    (
                        SELECT  provider_id, download_speed, upload_speed, cost, connection_type_id, ROW_NUMBER() OVER (PARTITION BY provider_id ORDER BY id DESC) rn
                        FROM  internet_info WHERE zip='$zipcode'
                        ) x
                WHERE   x.rn = 1");
        $table_arr = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $table_arr[] = $row;
        }

        return $table_arr;
    }

    /**
     * return table in the database
     */
    public function getTVProvidersByZip($zipcode) {

        if (!$this->pdo){
            return "Error establishing connection with database";
        }

        $stmt = $this->pdo->query("SELECT tv_zip.provider_id
        FROM tv_zip
        INNER JOIN tv_plan ON tv_zip.provider_id=tv_plan.provider_id WHERE tv_zip.zip='$zipcode'");

        $table_arr = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $table_arr[] = $row;
        }

        return $table_arr;
    }

    /**
     * return table in the database
     */
    public function getBundleCost(int $provider_id) {

        if (!$this->pdo){
            return "Error establishing connection with database";
        }

         $stmt = $this->pdo->query("SELECT cost
                               FROM bundle_plan WHERE provider_id='$provider_id'");

        $table_arr = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $table_arr[] = $row;
        }

        return $table_arr;
    }

    public function getConnectionInfo() {

        if (!$this->pdo){
            return "Error establishing connection with database";
        }

        $stmt = $this->pdo->query("SELECT *
                               FROM connection_type");
        $table_arr = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $table_arr[] = $row;
        }

        return $table_arr;
    }

    /**
     * return table in the database
     */
    public function getProviderName($provider_id) {

        if (!$this->pdo){
            return "Error establishing connection with database";
        }

        $stmt = $this->pdo->query("SELECT * 
                               FROM provider WHERE id='$provider_id'");
        $table_arr = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $table_arr[] = $row;
        }

        return $table_arr;
    }

    /**
     * return an instance of the Connection object
     */
    public static function getConnection() {
        if (!$this->pdo){
            return "Error establishing connection with database";
        }
        return $this->pdo;
    }
}