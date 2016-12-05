<?php
namespace Rolice\Speedy\Components\Param;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Rolice\Speedy\Components\ComponentInterface;
use Rolice\Speedy\Traits\Serializable;

class Pdf implements ComponentInterface
{

    use Serializable;

    const RequestMapping = [];

    const TypeMapping = [];

    /**
     * The document type constant representing waybill PDFs.
     */
    const Waybill = 10;

    /**
     * The document type constant representing labels (only) PDFs.
     */
    const Labels = 20;

    /**
     * The document type constant representing labels with additional barcode PDFs.
     */
    const LabelsPlus = 25;

    /**
     * The document type constant representing return voucher PDFs.
     */
    const ReturnVoucher = 30;

    const DocumentTypes = [
        self::Waybill,
        self::Labels,
        self::LabelsPlus,
        self::ReturnVoucher,
    ];

    /**
     * Constant representing barcode format CODE128.
     */
    const CODE128 = 'CODE128';

    /**
     * Constant representing barcode format EAN13.
     */
    const EAN13 = 'EAN13';

    /**
     * Constant representing barcode format EAN8.
     */
    const EAN8 = 'EAN8';

    /**
     * Constant representing barcode format UPC-A.
     */
    const UPCA = 'UPC-A';

    /**
     * Constant representing barcode format UPC-E.
     */
    const UPCE = 'UPC-E';

    /**
     * The document type (10 - BOL; 20 - labels; 25 - labels with additional barcode; 30 - return voucher)
     * @var int
     */
    public $type;

    /**
     * List of IDs. For types 10 and 30 only the BOL number is needed.
     * For types 20 and 25 one or more parcel IDs are expected (parcels must be of a single BOL).
     * @var Collection<int>
     */
    public $ids;

    /**
     * Specifies if embedded JavaScript code for direct printing to be generated (works for Adobe Acrobat Reader only)
     * @var bool
     */
    public $includeAutoPrintJS;

    /**
     *
     * The printer name. If empty, the default printer is to be used.
     * Only applicable if includeAutoPrintJS = true.
     * @var string
     */
    public $printerName;

    /**
     * Only allowed for type 25.
     * A list of additional (second) barcodes to be printed on the bottom of each label in the PDF document.
     * Note that the additional barcodes take some extra space so the label height for type 25 is greater than the label height for type 20.
     * Each element in the list corresponds to the element of 'ids' with the same index (position).
     * @var Collection<ParamBarcodeInfo>
     */
    public $additionalBarcodes;

    /**
     * Only allowed for type 25.
     * Specifies the barcode format to be used for additionalBarcodes.
     * Accepts the following values: 'CODE128', 'EAN13', 'EAN8', 'UPC-A', 'UPC-E'
     * @var string
     */
    public $additionalBarcodesFormat;


    public static function createFromRequest(array $data)
    {
        $result = new static;

        foreach ($result as $expected => $default) {
            $result->$expected = Arr::has($data, $expected) ? Arr::get($data, $expected) : null;
        }

        foreach (static::RequestMapping as $expected => $mapping) {
            $result->$expected = Arr::has($data, $mapping) ? Arr::get($data, $mapping) : null;
        }

        foreach (static::TypeMapping as $expected => $type) {
            $method = $type . 'val';
            $result->$expected = $method($result->$expected);
        }

        if (!in_array($result->type, static::DocumentTypes)) {
            $result->type = static::Waybill;
        }

        $result->ids = Collection::make([Arr::get($data, 'waybill')])
            ->map(function($id) { return (float)$id; });

        return $result;
    }

}