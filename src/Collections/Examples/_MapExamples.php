<?php
declare(strict_types=1);

namespace Collections\Examples;


use Collections\Collections;
use Collections\Exceptions\CollectionsException;

/**
 * Class _MapExamples
 * @package Collections
 */
class _MapExamples
{
    /**
     * map
     *
     * @throws CollectionsException
     */
    public static function map()
    {
        ############ Example 1 (modify every objeect's ID to be + 100) ############

        $manyObjects = [
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7)
        ];

        $mappedResult = Collections::map($manyObjects, function (CollectionsSampleObject $obj) {
            $obj->setId($obj->getId() + 100);
            return $obj;
        });

        var_dump($mappedResult); #   [ CollectionsSampleObject(114), CollectionsSampleObject(107) ]

    }

    /**
     * map2
     *
     * @throws CollectionsException
     */
    public static function map2()
    {

        ############ Example 1 (map all integers to be positive) ############

        $manyObjects = [-14, 12, 23, 4, -9];

        $mappedResult = Collections::map($manyObjects, function (int $obj) {
            return abs($obj);
        });

        var_dump($mappedResult); #   [ 14, 12, 23, 4, 9] ]


    }
}