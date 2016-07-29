<?php
namespace Rolice\Speedy\Components;

class FixedDiscountCardId
{
    /**
     * Agreement (contract) ID.
     * @var int|null
     */
    public $agreementId;

    /**
     * Card ID
     * @var int|null
     */
    public $cardId;

    public function __construct($agreement_id, $card_id)
    {
        $this->agreementId = !is_null($agreement_id) ? (int) $agreement_id : null;
        $this->cardId = !is_null($card_id) ? (int) $card_id : null;
    }
}