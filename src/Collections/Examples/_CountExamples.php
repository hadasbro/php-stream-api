<?php
declare(strict_types=1);

namespace Collections\Examples;

use Collections\Collections;
use Collections\Exceptions\CollectionsException;

/**
 * Class _CountExamples
 * @package Collections\Examples
 */
class _CountExamples
{

    /**
     * @throws CollectionsException
     */
    public static function count()
    {
        ############ Example 1 (count) ############

        $manyObjects = [
            new CollectionsSampleObject(1),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(33),
            new CollectionsSampleObject(33),
            new CollectionsSampleObject(2)
        ];

        # normal count
        $sortedCollection = Collections::count($manyObjects);

        var_dump($sortedCollection);

    }

    /**
     * @throws CollectionsException
     */
    public static function count2()
    {

        ############ Example 2 (count with objects groupped by type) ############

        $manyObjects = [
            new CollectionsSampleObject(1, 13),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(7, 11),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(33, 2),
            new CollectionsSampleObject(33, 11),
            new CollectionsSampleObject(2, 1)
        ];

        # count with groupping by object's type
        $counted = Collections::countBy(
            $manyObjects,
            function (CollectionsSampleObject $obj) {
                return $obj->getType();
            }
        );

        var_dump($counted);
        /*
        [
            13 => 3
            11 => 2
            2 => 1
            1 => 1
        ]
        */

    }

}