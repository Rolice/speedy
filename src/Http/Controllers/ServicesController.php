<?php
namespace Rolice\Speedy\Http\Controllers;

use Illuminate\Http\Request;
use Rolice\Speedy\Components\Client;
use Rolice\Speedy\Components\Param\Language;
use Rolice\Speedy\Components\Result\CourierService;
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

        $services = $speedy->listServices(Language::create());

        if (!isset($services->return) || !$services->return) {
            throw new SpeedyException('Invalid Speedy services response detected.');
        }

        $services = CourierService::createFromSoapResponse($services->return);

        return $services;
    }

    public function index(Request $request)
    {
        return response()->json($this->loadServices($request->all()));
    }

    public function autocomplete(Request $request)
    {
        $result = [];

        foreach ($this->loadServices($request->all()) as $service) {
            if (!$service instanceof CourierService) {
                continue;
            }

            $entry = ['id' => $service->typeId, 'name' => $service->name];
            $entry['ref'] = $service->name;

            $result[] = (object)$entry;
        }

        return [
            'results' => $result,
            'more' => false,
        ];
    }

}