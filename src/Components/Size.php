<?php
namespace Rolice\Speedy\Components;

use Rolice\Speedy\Exceptions\SpeedyException;

class Size implements ComponentInterface
{

    use Serializable;

    /**
     * Width (cm)
     * Limit 9999
     * @var int
     */
    public $width;

    /**
     * Height (cm)
     * Limit 9999
     * @var int
     */
    public $height;

    /**
     * Depth (cm)
     * Limit 9999
     * @var int
     */
    public $depth;

    public function __construct($width, $height, $depth)
    {
        $this->width = (int) $width;
        $this->height = (int) $height;
        $this->depth = (int) $depth;
    }

    public static function createFromSoapResponse($response)
    {
        $width = isset($response->width) ? (int)$response->width : null;
        $height = isset($response->width) ? (int)$response->width : null;
        $depth = isset($response->width) ? (int)$response->width : null;

        if (is_null($width) || is_null($height) || is_null($depth)) {
            throw new SpeedyException('Invalid size is passed.');
        }

        if (0 >= $width || 0 >= $height || 0 >= $depth) {
            throw new SpeedyException('Invalid size dimensions passed.');
        }

        return new static($width, $height, $depth);
    }

}