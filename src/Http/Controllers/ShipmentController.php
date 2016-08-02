<?php
namespace Rolice\Speedy\Http\Controllers;

use Illuminate\Support\Facades\Lang;

class ShipmentController extends Controller
{

    public function index()
    {
        return [
            'results' => [
                ['id' => '1', 'name' => Lang::get('speedy::shipment.type.parcel')],
                ['id' => '2', 'name' => Lang::get('speedy::shipment.type.pallet')],
            ],
            'more' => false,
        ];
    }

}