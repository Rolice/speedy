<?php
namespace Rolice\Speedy\Components;

use Carbon\Carbon;
use JsonSerializable;
use Rolice\Speedy\Exceptions\InvalidSessionException;
use Rolice\Speedy\Exceptions\SpeedyException;
use Exception;

class Client implements JsonSerializable
{
    /**
     * The ID of the logged in client inside Speedy database.
     * @var int
     */
    protected $id;

    /**
     * The username used in the login process for this client.
     * @var string
     */
    protected $username;

    /**
     * the password used in the login process for this client.
     * @todo Improve security of these operations.
     * @var string
     */
    protected $password;

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
     * Client constructor. Initializes the object with the data passed to clients with a previous login response.
     * The constructor may be called when an already logged in client is going to use the service. Since the API is
     * stateless the client receive the primal data and when it passes it back this constructor will restore the client.
     * @param mixed $id The ID of client to be set.
     * @param string $username The username used in the logon process.
     * @param string $password The password used in the logon process.
     * @param string $session The session ID to be set for the new client.
     * @param Carbon $time The server timestamp of issued login.
     */
    public function __construct($id, $username, $password, $session, Carbon $time = null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->session = $session;
        $this->time = $time;
    }

    /**
     * Creates new client from SOAP login response.
     * @param object $response Response object from successful login operation.
     * @param string $username The username used to login and to receive the response.
     * @param string $password The password used to login and to receive the response.
     * @return static The resulting client from the SOAP response given.
     * @throws InvalidSessionException
     * @throws SpeedyException
     */
    public static function createFromSoapResponse($response, $username, $password)
    {
        if (!is_object($response)) {
            throw new SpeedyException('SOAP response does not contain logged in client.');
        }

        $id = isset($response->return->clientId) ? $response->return->clientId : null;
        $session = isset($response->return->sessionId) ? $response->return->sessionId : null;
        $time = isset($response->return->serverTime) ? $response->return->serverTime : null;

        if (!$id || !$username || !$password || !$session) {
            throw new InvalidSessionException('Cannot create client with insufficient information.');
        }

        if ($time) {
            try {
                $time = new Carbon($time);
            }
            catch(Exception $e)
            {
                $time = null;
            }
        }

        return new static($id, $username, $password, $session, $time);
    }

    public static function createFromArray($array)
    {
        if (!is_array($array) || empty($array)) {
            throw new SpeedyException('SOAP request does not contain logged in client.');
        }

        $id = isset($array['id']) ? $array['id'] : null;
        $username = isset($array['username']) ? $array['username'] : null;
        $password = isset($array['password']) ? $array['password'] : null;
        $session = isset($array['session_id']) ? $array['session_id'] : null;
        $time = isset($array['time']) ? $array['time'] : null;

        if (!$id || !$username || !$password) {
            throw new InvalidSessionException('Cannot create client with insufficient information.');
        }

        if ($time) {
            try {
                $time = new Carbon($time);
            }
            catch(Exception $e)
            {
                $time = null;
            }
        }

        return new static($id, $username, $password, $session, $time);
    }

    public static function createFromSessionId($session_id)
    {
        return new static(0, '', '', $session_id);
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
     * Returns the logon username of this instance.
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * Returns the logon password of this instance.
     * @todo Expand this functionality to remove this password back-and-forward calls (security).
     * @tag Security
     * @return string
     */
    public function password()
    {
        return $this->password;
    }

    /**
     * Returns the server time of login assigned with this client instance.
     * @return Carbon
     */
    public function serverTime()
    {
        return $this->time;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->clientId(),
            'username' => $this->username(),
            'session' => $this->sessionId(),
            'time' => $this->serverTime()->toIso8601String(),
        ];
    }
}