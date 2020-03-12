<?php
declare(strict_types=1);

namespace Collections;

use Collections\Exceptions\CollectionsInvalidInputException;

/**
 * Class CollectorOperations
 *
 * Collector methods
 *
 * @package Collections
 */
class CollectorOperations
{

    /**
     * UNINITIALISED acumulator
     */
    const UNINIT = 'UNINITIALISED_ACUMULATOR';

    /**
     * JOINING
     *
     * @param $acumulated
     * @param $obj
     * @param string $separator
     * @return string
     */
    protected static function joining(&$acumulated, $obj, $separator = ','): void
    {

        if ($acumulated === self::UNINIT) {
            $acumulated = $obj;
        } else {
            $acumulated .= $separator . $obj;

        }
    }

    /**
     * SUMMING
     *
     * @param $acumulated
     * @param $obj
     * @return mixed
     */
    protected static function summing(&$acumulated, $obj): void
    {

        if ($acumulated === self::UNINIT) {
            $acumulated = $obj;
        } else {
            $acumulated += $obj;

        }
    }

    /**
     * MULTIPLYING
     *
     * @param $acumulated
     * @param $obj
     * @return float|int
     */
    protected static function multiplying(&$acumulated, $obj): void
    {

        if ($acumulated === self::UNINIT) {
            $acumulated = $obj;
        } else {
            $acumulated *= $obj;

        }
    }

    /**
     * TO_ARRAY
     *
     * @param $acumulated
     * @param $obj
     */
    protected static function toArray(&$acumulated, $obj): void
    {

        if ($acumulated === self::UNINIT) {
            $acumulated = [$obj];
        } else {
            $acumulated[] = $obj;

        }
    }

    /**
     * toListOfStrings
     *
     * @param $acumulated
     * @param $obj
     */
    protected static function toListOfStrings(&$acumulated, $obj): void
    {

        if (strpos(strval($obj), "'") !== false) {
            $obj = addslashes($obj);
        }

        if ($acumulated === self::UNINIT) {
            $acumulated = [];
        }

        $acumulated[] = "'" . $obj . "'";

    }

    /**
     * toAssocArray
     *
     * @param $acumulated
     * @param $obj
     * @param callable $objKeyProducer
     * @param callable $objValueProducer
     * @throws CollectionsInvalidInputException
     */
    protected static function toAssocArray(&$acumulated, $obj, callable $objKeyProducer, callable $objValueProducer): void
    {

        if ($acumulated === self::UNINIT) {
            $acumulated = [];
        }

        try {

            $acumulated[$objKeyProducer($obj)] = $objValueProducer($obj);

        } catch (\Throwable $t) {
            throw new CollectionsInvalidInputException($t->getMessage(), $t->getCode(), $t);
        }

    }


}