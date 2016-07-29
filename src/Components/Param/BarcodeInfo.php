<?php
namespace Rolice\Speedy\Components\Param;

use Rolice\Speedy\Components\ComponentInterface;
use Rolice\Speedy\Traits\Serializable;

class BarcodeInfo implements ComponentInterface
{

    use Serializable;

    /**
     * Barcode value. For barcode formats other than 'CODE128' it must contain digits only.
     * @var string
     */
    public $barcodeValue;

    /**
     * Barcode label. It is printed just below the barcode image. For barcode formats other than 'CODE128'
     * barcodeLabel must be equal to barcodeValue.
     * @var string
     */
    public $barcodeLabel;

}