<?php
namespace Rolice\Speedy\Components;

use Carbon\Carbon;

class Client
{
    /**
     * The ID of the logged in client inside Speedy database.
     * @var int
     */
    protected $id;

    /**
     * The ID of the session that is registered with Speedy webservice.
     * @var string
     */
    protected $session;

    /**
     * The login time assigned with the server administering the login operation.
     * @var Carbon
     */
    protected $time;

    /**
     * Client constructor. Initializes the object with the data passed with a login response.
     * @param object $response Response object from successful login operation.
     */
    public function __construct($response)
    {
        if (!is_object($response)) {
            return;
        }

        $this->id = isset($response->return->clientId) ? $response->return->clientId : null;
        $this->session = isset($response->return->sessionId) ? $response->return->sessionId : null;
        $this->time = isset($response->return->serverTime) ? $response->return->serverTime : null;

        if ($this->time) {
            $this->time = new Carbon($this->time);
        }
    }

    /**
     * Returns the client ID assigned with this client instance.
     * @return int
     */
    public function clientId()
    {
        return $this->id;
    }

    /**
     * Returns the session ID assigned with this client instance.
     * @return string
     */
    public function sessionId()
    {
        return $this->session;
    }

    /**
     * Returns the server time of login assigned with this client instance.
     * @return Carbon
     */
    public function serverTime()
    {
        return $this->time;
    }
}