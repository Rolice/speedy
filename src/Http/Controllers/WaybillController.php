<?php
namespace Rolice\Speedy\Http\Controllers;

use Rolice\Speedy\Components\Calculation;
use Rolice\Speedy\Components\Client;
use Rolice\Speedy\Http\Requests\CalculateRequest;
use Rolice\Speedy\Http\Requests\WaybillRequest;
use Rolice\Speedy\Speedy;

class WaybillController extends Controller
{

    public function issue(WaybillRequest $request)
    {

    }

    public function calculate(CalculateRequest $request)
    {
        $client = Client::createFromArray($request->all());

        if (!$client) {
            return null;
        }

        $speedy = new Speedy();
        $speedy->user($client);

        $calculation = new Calculation();
        $calculation->serviceTypeId = false;

        $calculation = $speedy->calculate($calculation);

        return $calculation;
    }

}