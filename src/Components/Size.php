<?php
namespace Rolice\Speedy\Components;

use Rolice\Speedy\Exceptions\SpeedyException;
use Rolice\Speedy\Traits\Serializable;

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

    /**
     * Size constructor.
     * @todo There is difference in result and documentation, null might not be valid values for the dimensions.
     * @todo Verify this carefully and update the code in the whole file, that allows or disallows null values.
     * @param int|null $width
     * @param int|null $height
     * @param int|null $depth
     */
    public function __construct($width, $height, $depth)
    {
        $this->width = !is_null($width) ? (int) $width : null;
        $this->height = !is_null($height) ? (int) $height : null;
        $this->depth = !is_null($depth) ? (int) $depth : null;
    }

    public static function createFromSoapResponse($response)
    {
        $width = isset($response->width) && !is_null($response->width) ? (int)$response->width : null;
        $height = isset($response->height) && !is_null($response->height) ? (int)$response->height : null;
        $depth = isset($response->depth) && !is_null($response->depth) ? (int)$response->depth : null;

        if (is_null($width) || is_null($height) || is_null($depth)) {
            throw new SpeedyException('Invalid size is passed.');
        }

        if (0 >= $width || 0 >= $height || 0 >= $depth) {
            throw new SpeedyException('Invalid size dimensions passed.');
        }

        return new static($width, $height, $depth);
    }

}