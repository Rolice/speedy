<?php
namespace Rolice\Speedy;

use Illuminate\Support\Facades\Config;
use SoapClient;

class Speedy
{

    protected $client = null;

    public function __construct()
    {
        $this->client = new SoapClient(Config::get('speedy.service.wsdl'));
    }

    public function login($username, $password)
    {
        $result = $this->client->login($username, $password);
    }

}