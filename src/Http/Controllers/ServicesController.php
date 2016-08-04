<?php
namespace Rolice\Speedy\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Rolice\Speedy\Components\Client;
use Rolice\Speedy\Components\Param\Language;
use Rolice\Speedy\Components\Result\CourierService;
use Rolice\Speedy\Components\Result\CourierServiceExt;
use Rolice\Speedy\Exceptions\SpeedyException;
use Rolice\Speedy\Speedy;

class ServicesController extends Controller
{

    protected function loadServices(array $data)
    {
        /**
         * @var Speedy $speedy
         */
        $speedy = app('speedy');
        $speedy->user(Client::createFromArray($data));

        $date = isset($data['date']) ? Carbon::createFromFormat('Y-m-d', $data['date']) : null;

        $services = $speedy->listServices(Language::create(), $date);

        if (!isset($services->return) || !$services->return) {
            throw new SpeedyException('Invalid Speedy services response detected.');
        }

        $services = CourierService::createFromSoapResponse($services->return);

        return $services;
    }

    protected function loadServicesForSites(array $data, $sender_site_id, $receiver_site_id)
    {
        /**
         * @var Speedy $speedy
         */
        $speedy = app('speedy');
        $speedy->user(Client::createFromArray($data));

        $sender_site_id = (int)$sender_site_id;
        $receiver_site_id = (int)$receiver_site_id;
        $date = isset($data['date']) ? Carbon::createFromFormat('Y-m-d', $data['date']) : null;

        if (!$sender_site_id || !$receiver_site_id) {
            throw new SpeedyException('Invalid sender or receiver site ID given for service availability check.');
        }

        $services = $speedy->listServicesForSites($sender_site_id, $receiver_site_id, Language::create(), $date);

        if (!isset($services->return) || !$services->return) {
            throw new SpeedyException('Invalid Speedy services response detected.');
        }

        $services = CourierServiceExt::createFromSoapResponse($services->return);

        return $services;
    }

    public function index(Request $request)
    {
        return response()->json($this->loadServices($request->all()));
    }

    public function autocomplete(Request $request, $sender_site_id, $receiver_site_id)
    {
        $result = [];

        foreach ($this->loadServicesForSites($request->all(), $sender_site_id, $receiver_site_id) as $service) {
            if (!$service instanceof CourierServiceExt) {
                continue;
            }

            $entry = ['id' => $service->typeId, 'name' => $service->name];
            $entry['deadline'] = $service->deliveryDeadline->toIso8601String();
            $entry['ref'] = $service->name;

            $result[] = (object)$entry;
        }

        return [
            'results' => $result,
            'more' => false,
        ];
    }

}