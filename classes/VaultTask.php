<?php
namespace ZipSearch;

use Violuke\Vault\Exception\ServerException;
use Violuke\Vault\ServiceFactory as VaultFactory;
use Dotenv\Dotenv as DotEnv;

/**
 * Vault API and Process Manager
 *
 */
class VaultTask
{

    /**
     * Vault API Factory
     *
     * @var Violuke\Vault\ServiceFactory
     */
    protected $factory;

    /**
     * Get the "sys" service from the Vault Factory
     *
     * @return Violuke\Vault\Services\Sys
     */
    public function sys()
    {
        return $this->factory()->get('sys');
    }

    /**
     * Get the "data" service from the Vault Factory
     *
     * @return Violuke\Vault\Services\Data
     */
    public function data()
    {
        return $this->factory()->get('data');
    }

    /**
     * Get the Vault Service Factory
     *
     * If the ~/.bownty_vault_keys file exist, the X-Vault-Token header
     * will automatically be sent in all requests
     *
     * @return Violuke\Vault\ServiceFactory
     */
    public function factory()
    {
        if (!$this->factory) {
            $options = [];
            $options['headers']['X-Vault-Token'] = $this->getVaultToken();
            $this->factory = new VaultFactory($options);
        }

        return $this->factory;
    }

    public function getVaultToken()
    {
        $file = $this->getVaultKeyFile();
        return json_decode($file, true)['root_token'];
    }

    /**
     * Return the path to the Vault Key file
     *
     * @return string
     */
    public function getVaultKeyFile()
    {
        $dotenv = DotEnv::createUnsafeImmutable(ABSPATH);
        $dotenv->load();
        return base64_decode(getenv('VAULT_AUTH'));
    }

    /**
     * Wait for Vault health to return the key/value $pairs specified
     *
     * @see    https://www.vaultproject.io/docs/http/sys-health.html
     * @param  array  $pairs     Key/Value to expect health to return
     * @param  array  $arguments Additional arguments for health endpoint
     * @return boolean
     */
    public function waitFor(array $pairs = [])
    {
        $allowedPairs = ['initialized' => null, 'sealed' => null, 'standby' => null];
        $pairs = array_intersect_key($pairs, $allowedPairs);

        $this->out('Waiting for health returning ' . json_encode($pairs) . ': ', 0);

        $count = 0;
        while (true) {
            try {
                $res = $this->sys()->health(['standbyok' => 1, 'standbycode' => 200, 'sealedcode' => 200])->json();
                $this->info('.', 0);
            } catch (ServerException $e) {
                $res = $e->response()->json();
                $this->err('.', 0);
            }

            if (!array_diff_assoc($pairs, $res)) {
                $this->success(' Done!');
                return true;
            }

            $count++;
            if ($count > 120) {
                $this->err(' Failed after 120 attempts...');
                return false;
            }

            sleep(1);
        }
    }

    /**
     * Unseal the vault using the keys provided
     *
     * @return void
     */
    public function unseal()
    {
        $file = $this->getVaultKeyFile();

        $sysService = $this->sys();

        $sealed = $sysService->sealed();
        if (!$sealed) {
            return 'Vault is unsealed';
        }

        $content = json_decode($file, true);
        while ($sysService->sealed()) {
            $response = $sysService->unseal(['key' => array_pop($content['keys'])]);
        }

        return $response;
    }

    public function get_auth(){
        $data = $this->data();

        //get auth for bd api
        $response = $data->get('secret/data/zip-search-service');
        $response_body = (string) $response->getBody();
        $response_arr_bd_api = json_decode($response_body, true);

        //get auth for postgres db
        $response = $data->get('secret/data/application');
        $response_body = (string) $response->getBody();
        $response_arr_postgres = json_decode($response_body, true);

        $response_arr = array_merge($response_arr_bd_api['data']['data'], $response_arr_postgres['data']['data']);

        return $response_arr;
        
    }

    /**
     * Seal the Vault
     *
     * @return boolean
     */
    public function seal()
    {
        $this->sys()->seal();
    }

    /**
     * Clear the factory instance
     *
     * @return void
     */
    public function clear()
    {
        $this->factory = null;
    }

}