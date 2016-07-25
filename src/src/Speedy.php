<?php
namespace Rolice\Speedy;

use Illuminate\Support\Facades\Config;
use Rolice\Speedy\Components\Calculation;
use Rolice\Speedy\Components\Client;
use Rolice\Speedy\Components\ComponentInterface;
use Rolice\Speedy\Components\FilterSite;
use Rolice\Speedy\Components\Language;
use Rolice\Speedy\Exceptions\InvalidUsernameOrPasswordException;
use Rolice\Speedy\Exceptions\NoUserPermissionsException;
use Rolice\Speedy\Exceptions\SpeedyException;
use SoapClient;
use SoapFault;

class Speedy
{

    /**
     * The instance of SOAP client used in communication with Speedy Webservice.
     * @var SoapClient
     */
    protected $client = null;

    /**
     * A user instance, representing logged in client account.
     * @var Client
     */
    protected $user = null;

    /**
     * Speedy constructor. Initializes the SOAP client with the configuration WSDL URL.
     */
    public function __construct()
    {
        $this->client = new SoapClient(Config::get('speedy.service.wsdl'));
    }

    /**
     * Handles regular SoapFault exception and extracts predefined logical exceptions transmitted with it.
     * @param SoapFault $fault The fault exception to be parsed for specific errors.
     * @throws SoapFault
     * @throws SpeedyException
     */
    protected function handle(SoapFault $fault)
    {
        if (!isset($fault->detail) || !$fault->detail) {
            throw new SpeedyException($fault->getMessage(), $fault->getCode());
        }

        foreach ($fault->detail as $exception => $message) {
            if (is_object($message)) {
                if (!isset($message->errorDescription)) {
                    $message = '';
                }

                if (isset($message->errorDescription)) {
                    $message = $message->errorDescription;
                }
            }

            if (class_exists("\\Rolice\\Speedy\\Exceptions\\{$exception}")) {
                $class = "\\Rolice\\Speedy\\Exceptions\\{$exception}";
                throw new $class($message);
            }
        }

        throw $fault;
    }

    protected function call($name, $arguments)
    {
        $response = null;

        if ($arguments instanceof ComponentInterface) {
            $arguments = $arguments->toArray();
        }

        try {
            $response = $this->client->$name($arguments);
        } catch (SoapFault $e) {
            $this->handle($e);
        }

        return $response;
    }

    /**
     * Performs a login with Speedy webservice.
     * @param string $username The username of the client.
     * @param string $password The password of the client.
     * @return Client
     * @throws SoapFault
     * @throws InvalidUsernameOrPasswordException
     * @throws NoUserPermissionsException
     */
    public function login($username, $password)
    {
        if ($this->user instanceof Client && $this->active()) {
            return $this->user;
        }

        $response = $this->call('login', ['username' => $username, 'password' => $password]);
        $this->user = Client::createFromSoapResponse($response, $username, $password);

        return $this->user;
    }

    public function createClient($id, $username, $password, $session_id, $time = null)
    {
        return new Client($id, $username, $password, $session_id, $time);
    }

    /**
     * Checks if current/previous session is still active and ready for use and refreshes it if required.
     * @param bool $refresh Whether to refresh the expiration of the session, if it is still active. Does nothing otherwise.
     * @return bool True if session is still active and ready-to-use, false otherwise.
     * @internal param Client $client The currently logged in client from which to extract session ID and to perform check against.
     */
    public function active($refresh = true)
    {
        if ($this->user instanceof Client) {
            return $this->activeSession($this->user->sessionId(), $refresh);
        }

        return false;
    }

    /**
     * Checks if session is still active and ready for use for a given Client object and refreshes it if required.
     * @param Client $client The currently logged in client from which to extract session ID and to perform check against.
     * @param bool $refresh Whether to refresh the expiration of the session, if it is still active. Does nothing otherwise.
     * @return bool True if session is still active and ready-to-use, false otherwise.
     * @throws SoapFault
     */
    public function activeClient(Client $client = null, $refresh = true)
    {
        return $this->activeSession($client->sessionId(), $refresh);
    }

    /**
     * Checks if current/previous session is still active and ready for use and refreshes it if required.
     * @param string $session_id The session ID of a session to be checked if it is active or not.
     * @param bool $refresh Whether to refresh the expiration of the session, if it is still active. Does nothing otherwise.
     * @return bool True if session is still active and ready-to-use, false otherwise.
     * @throws SoapFault
     */
    public function activeSession($session_id, $refresh = true)
    {
        $response = $this->call('isSessionActive', [
            'sessionId' => $session_id,
            'refreshSession' => !!$refresh
        ]);

        return isset($response->result) ? !!$response->result : false;
    }

    public function user($data)
    {
        if ($data instanceof Client) {
            return $this->user = $data;
        }

        if (!$this->user instanceof Client && $this->active()) {
            return $this->user;
        }

        $client = Client::createFromArray($data);

        if ($this->activeClient($client)) {
            $this->user = $client;
        }

        if (!$this->user) {
            $this->login($client->username(), $client->password());
        }

        return $this->user;
    }

    public function listSitesEx(FilterSite $filter, Language $language)
    {
        $response = $this->call('listSitesEx', [
            'sessionId' => $this->user->sessionId(),
            'filter' => $filter->toArray(),
            'language' => $language->get(),
        ]);

        return $response;
    }

    public function calculate($input)
    {
        $this->user($input);

        $data = new Calculation;
        $data->serviceTypeId = 1;
        $data->broughtToOffice = false;
        $data->parcelsCount = 3;
        $data->weightDeclared = 3.650;
        $data->documents = false;
        $data->fragile = false;
        $data->palletized = false;
        $data->senderCountryId = 100;

        $response = $this->call('calculate', [
            'sessionId' => $this->user->sessionId(),
            'calculation' => $data->toArray()
        ]);

        return $response->return;
    }

}