<?php
namespace Rolice\Speedy\Http\Controllers;

use Input;
use Rolice\Speedy\Components\Client;
use Rolice\Speedy\Components\Language;
use Rolice\Speedy\Components\OfficeEx;
use Rolice\Speedy\Components\Site;
use Rolice\Speedy\Exceptions\SpeedyException;
use Rolice\Speedy\Speedy;

class OfficeController extends Controller
{

    const MIN_AUTOCOMPLETE_LENGTH = 3;

    public function index()
    {
    }

    public function autocomplete()
    {
        $client = Client::createFromArray(Input::all());

        if (!$client) {
            return ['results' => [], 'more' => false];
        }

        /**
         * @var Speedy $speedy
         */
        $speedy = app('speedy');
        $speedy->user($client);

        $settlement = Input::get('settlement');

        if ((string)(int)$settlement != $settlement) {
            $site = Site::find($settlement);

            if ($site instanceof Site) {
                $settlement = $site->id;
            }
        }

        $settlement = (int) $settlement;

        $name = htmlentities(Input::get('query'), ENT_QUOTES, 'UTF-8', false);

        if (0 >= $settlement) {
            return ['results' => [], 'more' => false];
        }

        $offices = $speedy->listOfficesEx($settlement, Language::create(), $name);

        if (!$offices || !isset($offices->return)) {
            return ['results' => [], 'more' => false];
        }

        $result = [];
        $offices = OfficeEx::createFromSoapResponse($offices->return);

        foreach ($offices as $office) {
            if (!$office instanceof OfficeEx) {
                continue;
            }

            $entry = ['id' => $office->id, 'name' => $office->name];
            $entry['ref'] = $office->name;

            $result[] = (object)$entry;
        }

        return [
            'results' => $result,
            'more' => false,
        ];
    }

}