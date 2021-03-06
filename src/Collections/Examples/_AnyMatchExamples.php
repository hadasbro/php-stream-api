<?php
declare(strict_types=1);

namespace Collections\Examples;

use Collections\Collections;
use Collections\Exceptions\CollectionsException;

/**
 * Class _AnyMatchExamples
 * @package Collections
 */
class _AnyMatchExamples
{

    /**
     * anyMatch
     *
     * @throws CollectionsException
     */
    public static function anyMatch()
    {


        ############ Example 1 (any object from our array is also in another array) ############


        $manyObjects = [1, 2, 3, 4, 5];
        $objects = [21, 17];

        /**
         * @var $anyMatch bool
         */
        $anyMatch = Collections::anyMatch($manyObjects, function (int $obj1) use ($objects) {
            # predicate method should return TRUE if we consider element as
            # matching to our criteria or FALSE if element doesnt match

            return in_array($obj1, $objects);
        });

        var_dump($anyMatch); // false

    }


    /**
     * anyMatch2
     *
     * @throws CollectionsException
     */
    public static function anyMatch2()
    {

        ############ Example 2 (any string from our array is vulgar) ############


        $manyObjects = ['I', 'want', 'to', 'go', 'home', 'ImVulgar'];

        /**
         * @var $anyMatch bool
         */
        $anyMatch = Collections::anyMatch($manyObjects, function (string $obj1) {
            $vulgarWords = ['ImVulgar', 'ImRude'];
            return in_array($obj1, $vulgarWords);
        });

        var_dump($anyMatch); // true

    }


    /**
     * anyMatch3
     *
     * @throws CollectionsException
     */
    public static function anyMatch3()
    {

        ############ Example 3 (I have at least one object with ID > 15) ############

        $manyObjects = [
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(33),
            new CollectionsSampleObject(33)
        ];

        /**
         * @var $anyMatch bool
         */
        $anyMatch = Collections::anyMatch($manyObjects, function (CollectionsSampleObject $obj1) {
            return $obj1->getId() > 15;
        });


        ############ Example 4 (I have at least one object with ID > 150) ############

        /**
         * @var $anyMatch2 bool
         */
        $anyMatch2 = Collections::anyMatch($manyObjects, function (CollectionsSampleObject $obj1) {
            return $obj1->getId() > 150;
        });


        var_dump($anyMatch); // false
        var_dump($anyMatch2); // false

    }

}