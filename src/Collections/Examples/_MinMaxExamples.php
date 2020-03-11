<?php
declare(strict_types=1);

namespace Collections\Examples;

use Collections\Collections;
use Collections\Exceptions\CollectionsException;

/**
 * Class _MinMaxExamples
 * @package Collections
 */
class _MinMaxExamples
{

    /**
     * minMax
     *
     * @throws CollectionsException
     */
    public static function minMax()
    {

        ############ Example 1 (get object with the lowest ID) ############

        $manyObjects = [
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7),
            new CollectionsSampleObject(1),
            new CollectionsSampleObject(33)
        ];

        /**
         * @var $minElement CollectionsSampleObject
         */
        $minElement = Collections::min($manyObjects, function (CollectionsSampleObject $obj1, CollectionsSampleObject $obj2) {
            # comparator should return TRUE if $obj1 < $obj2
            # in terms of any criteria we want to apply
            return $obj1->getId() < $obj2->getId();
        });

        var_dump($minElement); # new CollectionsSampleObject(1),
        var_dump($minElement->getId()); # 1

    }


    /**
     * minMax2
     *
     * @throws CollectionsException
     */
    public static function minMax2()
    {

        ############ Example 2 (get first element in dictionary order) ############


        $manyElements = ['fgh', 'abc', 'j'];

        /**
         * @var string $minElement
         */
        $minElement = Collections::min($manyElements, function (string $obj1, string $obj2) {
            # comparator should return TRUE if $obj1 < $obj2
            # in terms of any criteria we want to apply
            return $obj1 < $obj2;
        });

        var_dump($minElement); # abc

    }

    /**
     * minMax3
     *
     * @throws CollectionsException
     */
    public static function minMax3()
    {

        ############ Example 3 (get the array with the smallest sum of elements) ############

        $manyElements = array([123, 345, 4334], [1, 2], [0, -30]);

        /**
         * @var array $minElement
         */
        $minElement = Collections::min($manyElements, function (array $obj1, array $obj2) {
            # comparator should return TRUE if $obj1 < $obj2
            # in terms of any criteria we want to apply
            return array_sum($obj1) < array_sum($obj2);
        });

        var_dump($minElement); # [0, -30]

    }
}