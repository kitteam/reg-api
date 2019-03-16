<?php

namespace RegApi\Services;

use GuzzleHttp\Client;
use RegApi\Exceptions\RegExceptions;

class RegApi
{
    protected $username = '';

    protected $password = '';

    public function __construct()
    {
        if ($account = config('reg.default')) {
            $this->account($account);
        }
    }

    /**
     * @param string $server
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function account($account = '')
    {
        if (empty($account)) {
            throw new \Exception('Account is not specified');
        }
        $allAccounts = config('reg.accounts');

        if (!isset($allAccounts[$account])) {
            throw new \Exception('Specified account not found in config');
        }

        if ($this->accountCheck($account, $allAccounts)) {
            throw new \Exception('Specified server config does not contain host or key');
        }

        $this->username = (string) $allAccounts[$account]['username'];
        $this->password = (string) $allAccounts[$account]['password'];

        return $this;
    }

    /**
     * @param string $account
     * @param array  $config
     *
     * @return bool
     */
    private function accountCheck($account, $config)
    {
        return !isset($config[$account]['username']) || !isset($config[$account]['password']);
    }

    /**
     * @param string $cmd
     *
     * @throws RegExceptions
     *
     * @return string
     */
    public function send($cmd = 'nop', $params = [])
    {
        $config = config('reg.client');

        if (!isset($config)) {
            throw new \Exception('Specified client params not found in config');
        }

        $client = new Client($config);

        $params = array_merge($params, [
            'username' => $this->username,
            'password' => $this->password
        ]);

        $json = $client->post($cmd, ['form_params' => $params])
            ->getBody()
            ->getContents();

        if (!$data = json_decode($json, true)) {
            throw new RegExceptions('JSON decodin error');
        }

        if (!isset($data['result'])) {
            throw new RegExceptions('The answer does not contain the result');
        }

        if ('error' === $data['result']) {
            throw new RegExceptions("{$data['error_code']}: {$data['error_text']}");
        }

        if (isset($data['answer'])) {
            return $data['answer'];
        }

        return $data['result'];
    }

}
