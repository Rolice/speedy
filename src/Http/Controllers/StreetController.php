<?php
namespace Rolice\Speedy\Http\Controllers;

use Input;
use Rolice\Speedy\Components\Client;
use Rolice\Speedy\Components\Param\Language;
use Rolice\Speedy\Components\Result\Street;
use Rolice\Speedy\Exceptions\SpeedyException;
use Rolice\Speedy\Speedy;

class StreetController extends Controller
{

    const MIN_AUTOCOMPLETE_LENGTH = 3;

    public function index()
    {
//        return Street::orderBy('name')->get();
    }

    public function autocomplete()
    {
        $settlement = (int)Input::get('settlement');
        $name = htmlentities(Input::get('query'), ENT_QUOTES, 'UTF-8', false);

        if (0 >= $settlement || self::MIN_AUTOCOMPLETE_LENGTH > mb_strlen($name)) {
            return ['results' => [], 'more' => false];
        }

        $client = Client::createFromArray(Input::all());

        if (!$client) {
            return ['results' => [], 'more' => false];
        }

        /**
         * @var Speedy $speedy
         */
        $speedy = app('speedy');
        $speedy->user($client);

        $streets = $speedy->listStreets($settlement, Language::create(), $name);

        if (!isset($streets->return)) {
            throw new SpeedyException('Error while searching for Speedy streets.');
        }

        $result = [];
        $streets = Street::createFromSoapResponse($streets->return);

        foreach ($streets as $street) {
            if (!$street instanceof Street) {
                continue;
            }

            $entry = ['id' => $street->id, 'name' => $street->formatted];
            $entry['ref'] = $street->name;

            $result[] = (object)$entry;
        }

        return [
            'results' => $result,
            'more' => false,
        ];
    }

}