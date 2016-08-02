<?php
namespace Rolice\Speedy\Components;

use Rolice\Speedy\Traits\Enum;

class CargoType
{

    use Enum;

    const Parcel = 'CARGO_TYPE_PARCEL';

    const Pallet = 'CARGO_TYPE_PALLET';

}