<?php
namespace Rolice\Speedy\Components;

use ReflectionClass;
use Rolice\Speedy\Exceptions\SpeedyException;
use SimpleXMLElement;

/**
 * Class Serializable
 * Trait providing, common, basic, uni-purpose serialization of ComponentInterface object to XML
 * @package Rolice\Speedy\Components
 * @verion 1.0
 */
trait Serializable
{

    /**
     * Default implementation of ComponentInterface::tag. Returns current class name (w/o namespace).
     * @return string
     */
    public function tag()
    {
        return (new ReflectionClass($this))->getShortName();
    }

    /**
     * Serializes an object for subsequent XML communication.
     * @return SimpleXMLElement
     */
    public function serialize()
    {
        $result = new SimpleXMLElement("<{$this->tag()}/>");
        $this->build($result, $this);

        return $result;
    }

    public function toArray()
    {
        $result = [];
        $this->buildArray($result, $this);

        return $result;
    }

    /**
     * Builds an Speedy-compatible XML request
     * @param SimpleXMLElement $xml Currently scoped XML representation object.
     * @param ComponentInterface|array $data A user-defined, custom request structure for the XML file.
     * @throws SpeedyException
     */
    protected function build(SimpleXMLElement $xml, $data)
    {
        if (!is_object($data) && !is_array($data)) {
            throw new SpeedyException('Invalid entity for serialization. An implementation of ComponentInterface or array required.');
        }

        if (is_object($data) && !($data instanceof ComponentInterface)) {
            throw new SpeedyException('An object given is not an implementation of class ComponentInterface.');
        }

        $reflected = !is_array($data);
        $iterator = $reflected ? (new ReflectionClass($data))->getProperties() : $data;

        foreach ($iterator as $key => $value) {
            if ($reflected) {
                if (0 === strpos($value->getName(), '_')) {
                    continue;
                }

                $value->setAccessible(true);
            }

            $key = $reflected ? $value->getName() : $key;
            $value = $reflected ? $value->getValue($data) : $value;

            if (null !== $value && !is_scalar($value)) {
                if (is_object($value) && !($value instanceof ComponentInterface)) {
                    continue;
                }

                $nested = $xml->addChild($key);
                $this->build($nested, $value);
                continue;
            }

            $xml->addChild($key, $value);
        }
    }

    protected function buildArray(array &$array, $data)
    {
        if (!is_object($data) && !is_array($data)) {
            throw new SpeedyException('Invalid entity for serialization. An implementation of ComponentInterface or array required.');
        }

        if (is_object($data) && !($data instanceof ComponentInterface)) {
            throw new SpeedyException('An object given is not an implementation of class ComponentInterface.');
        }

        $reflected = !is_array($data);
        $iterator = $reflected ? (new ReflectionClass($data))->getProperties() : $data;

        foreach ($iterator as $key => $value) {
            if ($reflected) {
                if (0 === strpos($value->getName(), '_')) {
                    continue;
                }

                $value->setAccessible(true);
            }

            $key = $reflected ? $value->getName() : $key;
            $value = $reflected ? $value->getValue($data) : $value;

            if (null !== $value && !is_scalar($value)) {
                if (is_object($value) && !($value instanceof ComponentInterface)) {
                    continue;
                }

                $nested = [];
                $this->buildArray($nested, $value);
                $array[$key] = $nested;
                continue;
            }

            $array[$key] = $value;
        }
    }

}