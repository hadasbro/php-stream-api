<?php
declare(strict_types=1);

namespace Collections;

use Collections\Exceptions\CollectionsException;
use Traversable;

/**
 * CollectionsUtils
 *
 * Class CollectionsUtils
 * @package App\Components\Utils
 */
class CollectionsUtils
{

    /**
     * sourceAsArray
     *
     * @param iterable $source
     * @return array
     * @throws CollectionsException
     */
    public static function sourceAsArray(iterable $source) {

        if (self::_isArray($source)) {

            /**
             * @var array $source
             */
            $_src = $source;

        } else if (self::_isTraversable($source)) {

            /**
             * @var Traversable $source
             */
            $_src = iterator_to_array($source);

        } else {
            throw new CollectionsException("Source must be iterable (array or Traversable)");
        }

        return $_src;
    }

    /**
     * _isIterable
     *
     * technical private method
     *
     * @param $obj
     * @return bool
     */
    protected static function _isIterable($obj)
    {
        return is_array($obj) || (is_object($obj) && ($obj instanceof Traversable));
    }

    /**
     * _isTraversable
     *
     * technical private method
     *
     * @param $obj
     * @return bool
     */
    protected static function _isTraversable($obj)
    {
        return !is_array($obj) && (is_object($obj) && ($obj instanceof Traversable));
    }

    /**
     * _isArray
     *
     * technical private method
     *
     * @param $obj
     * @return bool
     */
    protected static function _isArray($obj)
    {
        return is_array($obj);
    }

    /**
     * @param iterable $obj
     * @return bool
     */
    protected static function isAssocArray(iterable $obj)
    {
        if (!is_array($obj))
            return false;

        if (array() === $obj)
            return false;

        return array_keys($obj) !== range(0, count($obj) - 1);
    }

}