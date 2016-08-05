<?php
namespace Rolice\Speedy\Components\Param;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Rolice\Speedy\Components\ComponentInterface;
use Rolice\Speedy\Components\FixedDiscountCardId;
use Rolice\Speedy\Components\Result\CourierService;
use Rolice\Speedy\Components\Size;
use Rolice\Speedy\Exceptions\SpeedyException;
use Rolice\Speedy\Traits\Serializable;

class Picking implements ComponentInterface
{

    use Serializable;

    const RequestMapping = [
        'takingDate' => 'services.date',
        'serviceTypeId' => 'services.service',
        'weightDeclared' => 'shipment.weight',
        'contents' => 'shipment.description',
        'packing' => 'shipment.description',
        'payerType' => 'payment.side',
        'amountCodBase' => 'services.cd',
    ];

    const TypeMapping = [
        'billOfLading' => 'int',
        'serviceTypeId' => 'int',
        'officeToBeCalledId' => 'float',
        'fixedTimeDelivery' => 'int',
        'deferredDeliveryWorkDays' => 'int',
        'backDocumentsRequest' => 'bool',
        'backReceiptRequest' => 'bool',
        'willBringToOffice' => 'bool',
        'willBringToOfficeId' => 'int',
        'amountInsuranceBase' => 'float',
        'amountCodBase' => 'float',
        'payCodToThirdParty' => 'bool',
        'retMoneyTransferReqAmount' => 'float',
        'parcelsCount' => 'int',
        'weightDeclared' => 'float',
        'packId' => 'int',
        'documents' => 'bool',
        'fragile' => 'bool',
        'palletized' => 'bool',
        'payerType' => 'int',
        'payerRefId' => 'int',
        'payerTypeInsurance' => 'int',
        'payerRefInsuranceId' => 'int',
        'payerTypePackings' => 'int',
        'payerRefPackingsId' => 'int',
        'retToClientId' => 'int',
        'retToOfficeId' => 'int',
        'clientSystemId' => 'int',
        'skipAutomaticParcelsCreation' => 'bool',
        'pendingParcelsDescription' => 'bool',
        'pendingShipmentDescription' => 'bool',
        'specialDeliveryId' => 'int',
        'retThirdPartyPayer' => 'bool',
        'deliveryToFloorNo' => 'int',
        'includeShippingPriceInCod' => 'bool',
    ];

    const Nullable = [
        'billOfLading',
        'officeToBeCalledId',
        'fixedTimeDelivery',
        'deferredDeliveryWorkDays',
        'amountInsuranceBase',
        'amountCodBase',
        'retMoneyTransferReqAmount',
        'packId',
        'payerRefId',
        'payerTypeInsurance',
        'payerRefInsuranceId',
        'payerTypePackings',
        'payerRefPackingsId',
        'retToClientId',
        'retToOfficeId',
        'clientSystemId',
        'specialDeliveryId',
        'deliveryToFloorNo',
    ];

    const Optional = [
//        'parcels',
//        'retServicesRequest',
    ];

    /**
     * Waybill (BOL) number of an existing record.
     * This is only used with the web method updateBillOfLading.
     * @var int|null
     */
    public $billOfLading;

    /**
     * The date for shipment pick-up (the "time" component is ignored if it is already passed or is overridden with 09:01).
     * Default value is "today".
     * @var Carbon
     */
    public $takingDate;

    /**
     * Courier service type ID
     * @var int
     */
    public $serviceTypeId;

    /**
     * ID of an office "to be called".
     * Non-null and non-zero value indicates this picking as "to office". Otherwise "to address" is considered.
     * @var int|null
     */
    public $officeToBeCalledId;

    /**
     * Fixed time for delivery ("HHmm" format, i.e., the number "1315" means "13:15", "830" means "8:30" etc.)
     * Depending on the courier service, this property could be required, allowed or banned.
     * @var int|null
     */
    public $fixedTimeDelivery;

    /**
     * In some rare cases users might prefer the delivery to be deferred by a day or two.
     * This parameter allows users to specify by how many (working) days they would like to postpone the shipment delivery.
     * @var int|null
     */
    public $deferredDeliveryWorkDays;

    /**
     * Specifies if the shipment has a "request for return documents".
     * @var bool
     */
    public $backDocumentsRequest;

    /**
     * Specifies if the shipment has a "request for return receipt".
     * @var bool
     */
    public $backReceiptRequest;

    /**
     * Specifies if the sender intends to deliver the shipment to a Speedy office by him/herself instead of ordering a visit by courier.
     * If this flag is true, the picking is considered "from office".
     * Otherwise "from address" is considered.
     * @var bool
     */
    public $willBringToOffice;

    /**
     * Specifies the specific Speedy office, where the sender intends to deliver the shipment by him/herself.
     * If willBringToOfficeId is provided, willBringToOffice flag is considered "true" and the picking "from office",
     * regardless the value provided. If willBringToOfficeId is not provided (0 or null) and willBringToOffice
     * flag is "true", willBringToOfficeId is automatically set with default value configured for caller user profile.
     * The default willBringToOfficeId value could be managed using profile configuration page in client's
     * Speedy web site.
     * @var int
     */
    public $willBringToOfficeId;

    /**
     * Shipment insurance value (if the shipment is insured).
     * @var float|null
     */
    public $amountInsuranceBase;

    /**
     * Cash-on-Delivery (COD) amount.
     * @var float|null
     */
    public $amountCodBase;

    /**
     * Specifies if the COD value is to be paid to a third party.
     * Allowed only if the shipment has payerType = 2 (third party).
     * @var bool
     */
    public $payCodToThirdParty;

    /**
     * Return money-transfer request amount.
     * @var float|null
     */
    public $retMoneyTransferReqAmount;

    /**
     * Parcels count (must be equal to the number of parcels described in List parcels).
     * @var int
     */
    public $parcelsCount;

    /**
     * Size of shipment.
     * @deprecated This field is greyed out in Speedy docs. Be careful, it might be missing as well (always null).
     * @var Size
     */
    public $size;

    /**
     * Declared weight (the greater of "volume" and "real" weight values).
     * @var float
     */
    public $weightDeclared;

    /**
     * Contents?
     * Max length is 100.
     * @var string
     */
    public $contents;

    /**
     * Packing?
     * Max length is 50.
     * @var string
     */
    public $packing;

    /**
     * Packing ID (number).
     * @var int|null
     */
    public $packId;

    /**
     * Specifies whether the shipment consists of documents.
     * @var bool
     */
    public $documents;

    /**
     * Specifies whether the shipment is fragile - necessary when the price of insurance is being calculated.
     * @var bool
     */
    public $fragile;

    /**
     * Specifies whether the shipment is palletized.
     * @var bool
     */
    public $palletized;

    /**
     *
     * @var
     */
    public $sender;

    /**
     *
     * @var
     */
    public $receiver;

    /**
     * Payer type (0=sender, 1=receiver or 2=third party).
     * @var int
     */
    public $payerType;

    /**
     * Payer ID
     * @var int|null
     */
    public $payerRefId;

    /**
     * Insurance payer type (0=sender, 1=receiver or 2=third party).
     * @var int|null
     */
    public $payerTypeInsurance;

    /**
     * Insurance payer ID.
     * @var int|null
     */
    public $payerRefInsuranceId;

    /**
     * Packings payer type (0=sender, 1=receiver or 2=third party).
     * @var int|null
     */
    public $payerTypePackings;

    /**
     * Packings payer ID.
     * @var int|null
     */
    public $payerRefPackingsId;

    /**
     * Client's note.
     * Max length is 200.
     * @var string
     */
    public $noteClient;

    /**
     * Card/Coupon/Voucher number for fixed discount.
     * @var FixedDiscountCardId
     */
    public $discCalc;

    /**
     * ID of the client who is to receive the return receipt and/or the return documents.
     * If payer is "third party" then this client has to be payer's contract member.
     * Otherwise the client has to be sender's contract member.
     * @var int|null
     */
    public $retToClientId;

    /**
     * ID of the office which is to receive the return receipt and/or the return documents.
     * @var int|null
     */
    public $retToOfficeId;

    /**
     * An optional reference code.
     * @var string
     */
    public $ref1;

    /**
     * An optional reference code.
     * @var string
     */
    public $ref2;

    /**
     * An optional value used to identify user's client software.
     * Please verify the allowed values with Speedy's IT Department.
     * @var int|null
     */
    public $clientSystemId;

    /**
     * Data for parcels. Pallet shipments require full list of parcels (pallets) in this property.
     * For non-pallet shipments the server will automatically generate parcels according to the value specified in
     * parcelsCount property if no data for parcels is provided. If data for parcels is available,
     * the number of provided parcels (including first parcel) should be equal to the value of parcelsCount property.
     *
     * For non-pallet shipments, the first parcel could be omitted (list of parcels could start from seqNo = 2)
     * and server will create automatically first parcel for you.
     * For all shipments the number of the first parcel is always auto-generated and is equal to the BOL number.
     *
     * @var Collection<ParcelInfo>
     */
//    public $parcels;

    /**
     * When parcelsCount > 1 and non-pallet service is used and no explicit data has been set in the parcels property
     * during the creation, then parcels will be created automatically by default.
     * This parameter allows users to control this behaviour.
     * @deprecated This field is greyed out in Speedy docs. Be careful, it might be missing as well (always null).
     * @var bool
     */
    public $skipAutomaticParcelsCreation;

    /**
     * Specifies if the service/system should allow parcels to be added to the shipment at a later stage.
     * @var bool
     */
    public $pendingParcelsDescription;

    /**
     * Specifies if the service/system should allow BOL's modification at a later stage.
     * @var bool
     */
    public $pendingShipmentDescription;

    /**
     * A special delivery ID
     * @var int|null
     */
    public $specialDeliveryId;

    /**
     * Optional services, allowed before payment, when cash on delivery or money transfer is enabled for the picking.
     * @var OptionsBeforePayment
     */
    public $optionsBeforePayment;

    /**
     * Specifies the list of return services request.
     * @var Collection<ReturnServiceRequest>
     */
//    public $retServicesRequest;

    /**
     * Specifies the return shipment request.
     * @var ReturnShipmentRequest
     */
    public $retShipmentRequest;

    /**
     * Specifies if the payer of the return receipt and/or the return documents is the same third party,
     * which is also the payer of the courier service.
     * @var bool
     */
    public $retThirdPartyPayer;

    /**
     * Packings details.
     * @todo Review this property as it seems it is required not to be passed when generating BOLs.
     * @var Collection<Packing>
     */
    // public $packings;

    /**
     * Specifies details for return voucher.
     * @var ReturnVoucher
     */
    public $returnVoucher;

    /**
     * Indicates the floor, which the shipment should be delivered to.
     * @var int|null
     */
    public $deliveryToFloorNo;

    /**
     * Flag indicating whether the shipping price should be included into the cash on delivery price.
     * @var bool
     */
    public $includeShippingPriceInCod;

    public static function createFromRequest(array $data)
    {
        $result = new static;

        foreach ($result as $expected => $default) {
            $result->$expected = Arr::has($data, $expected) ? Arr::get($data, $expected) : null;
        }

        foreach (static::RequestMapping as $expected => $mapping) {
            $result->$expected = Arr::has($data, $mapping) ? Arr::get($data, $mapping) : null;
        }

        switch ($result->payerType) {

            case 'sender':
                $result->payerType = 0;
                break;
            case 'receiver':
                $result->payerType = 1;
                break;
            case 'third-party':
                $result->payerType = 2;
                break;

            default:
                throw new SpeedyException('Invalid payer type detected.');
        }

        foreach (static::TypeMapping as $expected => $type) {
            $method = $type . 'val';
            $result->$expected = $method($result->$expected);
        }

        $result->mapSides();
        $result->mapBeforePaymentOptions($data);
        $result->mapNullable();

        if (!$result->serviceTypeId) {
            $result->serviceTypeId = CourierService::DefaultServiceId;
        }

        if (!$result->parcelsCount) {
            $result->parcelsCount = 1;
        }

        if (0 < $result->amountCodBase) {
            $result->includeShippingPriceInCod = true;
        }

        if (Arr::has($data, 'services.oc') && 0 < ($oc = (float)Arr::get($data, 'services.oc'))) {
            $result->amountInsuranceBase = $oc;
            $result->payerTypeInsurance = $result->payerType;
        }

        if (Arr::has($data, 'services.dp.documents')) {
            $result->backDocumentsRequest = !!Arr::get($data, 'services.dp.documents');
        }

        if (Arr::has($data, 'services.dp.receipt')) {
            $result->backReceiptRequest = !!Arr::get($data, 'services.dp.receipt');
        }

        foreach (static::Optional as $optional) {
            if (!$result->$optional) {
                unset($result->$optional);
            }
        }

        return $result;
    }

    protected function mapNullable()
    {
        foreach (static::Nullable as $expected) {
            if (!$this->$expected) {
                $this->$expected = null;
            }
        }
    }

    protected function mapSides()
    {
        $this->sender = ClientData::createFromRequest($this->sender, request()->get('id'));
        $this->receiver = ClientData::createFromRequest($this->receiver);
    }

    protected function mapBeforePaymentOptions(array $data)
    {
        $this->optionsBeforePayment = OptionsBeforePayment::createFromRequest($data);
    }

}