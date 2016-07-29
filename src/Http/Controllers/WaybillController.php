<?php
namespace Rolice\Speedy\Http\Controllers;

use Rolice\Speedy\Components\Client;
use Rolice\Speedy\Components\Param\Calculation;
use Rolice\Speedy\Components\Result\Calculation as Result;
use Rolice\Speedy\Exceptions\SpeedyException;
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
        $data = $request->all();
        $client = Client::createFromArray($data);

        if (!$client) {
            return null;
        }

        /**
         * @var Speedy $speedy
         */
        $speedy = app('speedy');
        $speedy->user($client);


        $calculation = Calculation::createFromRequest($data);

        $calculation = $speedy->calculate($calculation);

        if (!isset($calculation->return) || !$calculation->return) {
            throw new SpeedyException('Invalid calculation detected.');
        }

        $result = Result::createFromSoapResponse($calculation->return);

        return response()->json($result);
    }

}