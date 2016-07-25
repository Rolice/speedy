<?php
namespace Rolice\Speedy\Components;

use Rolice\Speedy\Exceptions\SpeedyException;

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
    }

    public static function createFromSoapResponse($response)
    {
        if (is_object($response)) {
            $response = [$response];
        }

        if (is_array($response)) {
            foreach ($response as $siteEx) {
                if (!isset($siteEx->exactMatch) || !isset($siteEx->site)) {
                    throw new SpeedyException('SiteEx structure appear to be invalid.');
                }

                $site = Site::createFromSoapResponse($siteEx->site);
                return new static($site, $siteEx->exactMatch);
            }
        }
    }

}