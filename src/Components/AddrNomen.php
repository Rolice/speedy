<?php
namespace Rolice\Speedy\Components;

class AddrNomen
{

    use Enum;

    /**
     * Speedy has no address nomenclature (streets, quarters etc.) for this site.
     */
    const NO = 'NO';

    /**
     * Speedy has full address nomenclature (streets, quarters etc.) for this site.
     */
    const FULL = 'FULL';

    /**
     * Speedy has partial address nomenclature (streets, quarters etc.) for this site.
     */
    const PARTIAL = 'PARTIAL';

}