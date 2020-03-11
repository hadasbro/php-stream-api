<?php
declare(strict_types=1);

namespace Collections\Examples;

use Collections\Collections;
use Collections\Exceptions\CollectionsException;

/**
 * Class _ContainsSearchExamples
 * @package Collections\Examples
 */
class _ContainsSearchExamples
{
    /**
     * @throws CollectionsException
     */
    public static function contains()
    {

        ############ Example of Collections::contains ############

        $manyObjects = [
            new CollectionsSampleObject(1, 13),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(7, 11),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(33, 2),
            new CollectionsSampleObject(33, 11),
            new CollectionsSampleObject(2, 1)
        ];

        $contains = Collections::contains($manyObjects, function (CollectionsSampleObject $obj1) {
            return $obj1->getId() == 14;
        });

        $contains2 = Collections::contains($manyObjects, function (CollectionsSampleObject $obj1) {
            return $obj1->getId() == 144;
        });

        var_dump($contains); // true
        var_dump($contains2); // false
    }

    /**
     * @throws CollectionsException
     */
    public static function search()
    {
        ############ Example of Collections::search ############

        $manyObjects = [
            new CollectionsSampleObject(1, 13),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(7, 11),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(33, 2),
            new CollectionsSampleObject(33, 11),
            new CollectionsSampleObject(2, 1)
        ];

        $search = Collections::search($manyObjects, function (CollectionsSampleObject $obj1) {
            return $obj1->getId() == 144;
        });

        $search2 = Collections::search($manyObjects, function (CollectionsSampleObject $obj1) {
            return $obj1->getType() == 13;
        });

        var_dump($search); // []
        var_dump($search2); // [ CollectionsSampleObject(1, 13), CollectionsSampleObject(14, 13), CollectionsSampleObject(14, 13) ]

    }

}