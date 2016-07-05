<?php
namespace Rolice\Speedy\Components;

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

}