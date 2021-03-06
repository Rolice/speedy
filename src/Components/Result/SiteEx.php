<?php
namespace Rolice\Speedy\Components\Result;

use Illuminate\Support\Collection;
use Rolice\Speedy\Components\ComponentInterface;
use Rolice\Speedy\Exceptions\SpeedyException;
use Rolice\Speedy\Traits\Serializable;

class SiteEx implements ComponentInterface
{

    use Serializable;

    /**
     * Site data
     * @var Site
     */
    public $site;

    /**
     * Specifies if there is an exact match
     * @var bool
     */
    public $exactMatch;

    public function __construct(Site $site, $exactMatch = true)
    {
        $this->site = $site;
        $this->exactMatch = !!$exactMatch;
    }

    public static function createFromSoapResponse($response)
    {
        $result = [];

        if (is_object($response)) {
            $response = [$response];
        }

        if (is_array($response)) {
            foreach ($response as $siteEx) {
                if (!isset($siteEx->exactMatch) || !isset($siteEx->site)) {
                    throw new SpeedyException('SiteEx structure appear to be invalid.');
                }

                $site = Site::createFromSoapResponse($siteEx->site);
                $result[] = new static($site, $siteEx->exactMatch);
            }
        }

        return new Collection($result);
    }

}