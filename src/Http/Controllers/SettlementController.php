<?php
namespace Rolice\Speedy\Http\Controllers;

use App;
use App\Http\Controllers\Controller;
use Input;
use Rolice\Speedy\Components\Client;
use Rolice\Speedy\Components\FilterSite;
use Rolice\Speedy\Components\Language;
use Rolice\Speedy\Components\SiteEx;
use Rolice\Speedy\Exceptions\SpeedyException;
use Rolice\Speedy\Speedy;

class SettlementController extends Controller
{

    const MIN_AUTOCOMPLETE_LENGTH = 3;

    public function index()
    {
    }

    public function autocomplete()
    {
        $name = htmlentities(Input::get('query'), ENT_QUOTES, 'UTF-8', false);

        if (self::MIN_AUTOCOMPLETE_LENGTH > mb_strlen($name)) {
            return ['results' => [], 'more' => false];
        }

        $client = Client::createFromArray(Input::all());

        if (!$client) {
            return ['results' => [], 'more' => false];
        }

        $filter = new FilterSite();

        $filter->searchString = $name;

        $speedy = new Speedy();
        $speedy->user($client);

        $sites = $speedy->listSitesEx($filter, new Language(App::getLocale()));

        if (!isset($sites->return)) {
            throw new SpeedyException('Error while searching for Speedy sites.');
        }

        $result = [];
        $settlements = SiteEx::createFromSoapResponse($sites->return);

        foreach ($settlements as $settlement) {
            if (!$settlement instanceof SiteEx) {
                continue;
            }

            $entry = ['id' => $settlement->site->id, 'name' => $settlement->site->formatted];
            $entry['ref'] = $settlement->site->name;

            $result[] = (object)$entry;
        }

        return [
            'results' => $result,
            'more' => false,
        ];
    }

}