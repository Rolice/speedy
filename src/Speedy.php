<?php
namespace Rolice\Speedy;

use Illuminate\Support\Facades\Config;
use Rolice\Speedy\Components\Client;
use SoapClient;
use SoapFault;

class Speedy
{

    protected $client = null;

    public function __construct()
    {
        $this->client = new SoapClient(Config::get('speedy.service.wsdl'));
    }

    protected function handle(SoapFault $fault)
    {
        if (!isset($fault->detail) || !$fault->detail) {
            throw $fault;
        }

        foreach ($fault->detail as $exception => $message) {
            if (class_exists("\\Rolice\\Speedy\\Exceptions\\{$exception}")) {
                $class = "\\Rolice\\Speedy\\Exceptions\\{$exception}";
                throw new $class($message);
            }
        }

        throw $fault;
    }

    public function login($username, $password)
    {
        $response = null;

        try {
            $response = $this->client->login(['username' => $username, 'password' => $password]);
        } catch (SoapFault $e) {
            if ($e->detail) {
                $this->handle($e);
            }
        }

        $result = new Client($response);

        return $result;
    }

}