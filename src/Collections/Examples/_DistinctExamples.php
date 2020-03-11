<?php
declare(strict_types=1);

namespace Collections\Examples;

use Collections\Collections;
use Collections\Exceptions\CollectionsException;

/**
 * Class _DistinctExamples
 * @package Collections\Examples
 */
class _DistinctExamples
{
    /**
     * distinct
     *
     * @throws CollectionsException
     */
    public static function distinct()
    {
        ############ Example 1 (get only objects with different IDs) ############

        $manyObjects = [
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(33),
            new CollectionsSampleObject(33),
        ];

        /**
         * @var $unique CollectionsSampleObject[]
         */
        $unique = Collections::distinct($manyObjects, function (CollectionsSampleObject $obj1, CollectionsSampleObject $obj2) {
            # comparator should return TRUE if $obj1 is equal $obj2
            # or FALSE if we consider $obj1 and $obj2 as unequal
            # this is in terms of any criteria we want to apply
            return $obj1->getId() == $obj2->getId();
        });

        var_dump($unique); # [ CollectionsSampleObject(14),  CollectionsSampleObject(7),  CollectionsSampleObject(33), ]

    }

    /**
     * @throws CollectionsException
     */
    public static function distinct2()
    {

        ############ Example 1 (get only unique string) ############


        $manyObjects = ['aaa', 'aaa', 'bb', 'aas', 'abc', 'bb'];

        /**
         * @var $unique string[]
         */
        $unique = Collections::distinct($manyObjects, function (string $obj1, string $obj2) {
            return $obj1 == $obj2;
        });

        var_dump($unique); # ['aaa', 'bb', 'aas', 'abc'];

        echo 1;exit;


    }

}