<?php
namespace Rolice\Speedy\Components;

use Carbon\Carbon;

class Client
{
    protected $id;
    protected $session;

    protected $time;

    public function __construct($response)
    {
        $this->id = isset($response->return->clientId) ? $response->return->clientId : null;
        $this->session = isset($response->return->sessionId) ? $response->return->sessionId : null;
        $this->time = isset($response->return->serverTime) ? $response->return->serverTime : null;

        if ($this->time) {
            $this->time = new Carbon($this->time);
        }
    }

    public function clientId()
    {
        return $this->id;
    }

    public function sessionId()
    {
        return $this->session;
    }

    public function serverTime()
    {
        return $this->time;
    }
}