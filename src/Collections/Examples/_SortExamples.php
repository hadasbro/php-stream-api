<?php
declare(strict_types=1);

namespace Collections\Examples;

use Collections\Collections;
use Collections\Exceptions\CollectionsException;

/**
 * Class _SortExamples
 * @package Collections
 */
class _SortExamples
{
    /**
     * sort
     *
     * @throws CollectionsException
     */
    public static function sort()
    {

        ############ Example 1 (sort collection of objects to start from objects with the lowest ID) ############


        $manyObjects = [
            new CollectionsSampleObject(1),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(33),
            new CollectionsSampleObject(33),
            new CollectionsSampleObject(2)
        ];

        # order ASCE (from the smallest object's IDs)
        $sortedCollection = Collections::sort($manyObjects, function (CollectionsSampleObject $obj1, CollectionsSampleObject $obj2) {
            # sorter should return TRUE if $obj1 is < $obj2, so if we expect order [$obj1, $obj2]
            # or oposite - FALSE - if we expect order [$obj1, $obj2]
            # this is in terms of any criteria we want to apply
            return $obj1->getId() < $obj2->getId();
        });

        var_dump($sortedCollection);
        # [
        # new CollectionsSampleObject(1),
        # new CollectionsSampleObject(2),
        # new CollectionsSampleObject(7),
        # new CollectionsSampleObject(14),
        # ...
        #]

    }

    /**
     * sort
     *
     * @throws CollectionsException
     */
    public static function sort2()
    {

        ############ Example 2 (sort collection of objects to start from objects with the highest ID) ############

        # oposite order so DESC
        $sortedCollection = Collections::sort($manyObjects, function (CollectionsSampleObject $obj1, CollectionsSampleObject $obj2) {
            return $obj1->getId() > $obj2->getId();
        });

        var_dump($sortedCollection);
        # [
        # new CollectionsSampleObject(33),
        # new CollectionsSampleObject(33),
        # new CollectionsSampleObject(14),
        # new CollectionsSampleObject(14),
        # new CollectionsSampleObject(7),
        # new CollectionsSampleObject(2),
        # ...
        #]


    }

    /**
     * sort
     *
     * @throws CollectionsException
     */
    public static function sort3()
    {

        ############ Example 3 (sort collection of strings to be like in dictionary) ############


        $manyObjects = ['aaa', 'aaa', 'bb', 'aas', 'abc', 'bb'];

        /**
         * @var $unique string[]
         */
        $sortedCollection = Collections::sort($manyObjects, function (string $obj1, string $obj2) {
            return strcmp($obj1, $obj2) < 0;
        });

        var_dump($sortedCollection); # [ 'aaa', 'aaa', 'aas', 'abc', 'bb', 'bb' ];

    }

    /**
     * sort
     *
     * @throws CollectionsException
     */
    public static function sort4()
    {

        ############ Example 4 (sort collection numbers ASC ) ############


        $manyObjects = [2, 11.03, -12, 5, 19.2, -19];

        /**
         * @var $unique string[]
         */
        $sortedCollection = Collections::sort($manyObjects, function ($obj1, $obj2) {
            return $obj1 < $obj2;
        });

        $sortedCollectionDesc = Collections::sort($manyObjects, function ($obj1, $obj2) {
            return $obj1 > $obj2;
        });

        var_dump($sortedCollection); # [ -19, -12, 2, 5, 11.03, 19.2 ];
        var_dump($sortedCollectionDesc); # [ 19.2, 11.03, 5, 2, -12, -19 ]

    }

}