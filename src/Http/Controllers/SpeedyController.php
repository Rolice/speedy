<?php
namespace Rolice\Speedy\Http\Controllers;

use Illuminate\Http\Request;
use Rolice\Speedy\Components\Client;
use Rolice\Speedy\Components\CourierService;
use Rolice\Speedy\Components\Language;
use Rolice\Speedy\Exceptions\SpeedyException;
use Rolice\Speedy\Speedy;

class SpeedyController extends Controller
{

    public function test(Request $request)
    {
        $speedy = new Speedy();
        return response()->json($speedy->login($request->get('username'), $request->get('password')));
    }

    public function session(Request $request)
    {
        $speedy = new Speedy();

        return response()->json($speedy->activeSession($request->get('session')));
    }

    public function services(Request $request)
    {
        $speedy = new Speedy();
        $speedy->user(Client::createFromSessionId($request->get('session')));

        $services = $speedy->listServices(Language::create());

        if (!isset($services->return) || !$services->return) {
            throw new SpeedyException('Invalid Speedy services response detected.');
        }

        $services = CourierService::createFromSoapResponse($services->return);

        return response()->json($services);
    }

    public function calculate(Request $request)
    {
        $speedy = new Speedy();
        $calculation = $speedy->calculate($request->all());

        return response()->json($calculation);
    }

}