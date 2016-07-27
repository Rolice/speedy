<?php
namespace Rolice\Speedy\Components;

use Rolice\Speedy\Traits\Enum;

class ComplementaryServiceAllowance
{

    use Enum;

    /**
     * The complementary service is not allowed.
     */
    const BANNED = 'BANNED';

    /**
     * The complementary service is allowed (but not required).
     */
    const ALLOWED = 'ALLOWED';

    /**
     * The complementary service is required.
     */
    const REQUIRED = 'REQUIRED';

}