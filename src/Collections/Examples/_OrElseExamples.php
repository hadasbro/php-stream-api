<?php
declare(strict_types=1);

namespace Collections\Examples;

use Collections\Collections;
use Collections\Exceptions\CollectionsException;

/**
 * Class _OrElseExamples
 * @package Collections\Examples
 */
class _OrElseExamples
{
    /**
     * orElse
     *
     * @throws CollectionsException
     */
    public static function orElse()
    {

        ############ Example 1 (get collection with objects but replace each one with ID <= 0 to default one) ############


        $manyObjects = [
            new CollectionsSampleObject(0),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(-1),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(-22)
        ];

        # order ASCE (from the smallest object's IDs)
        $collection = Collections::orElse($manyObjects, function (CollectionsSampleObject $obj1) {
            # return TRUE if we consider that particular object as "NULL" in any meaning
            # (if we want to replace that kind of object to any other - default one)
            # here - every object with ID <= 0 we consider as "NULL one" and we want
            # to use default object (CollectionsSampleObject(100)) instead
            return $obj1->getId() <= 0;
        }, new CollectionsSampleObject(100));

        var_dump($collection);
        #[
        #    new CollectionsSampleObject(100),
        #    new CollectionsSampleObject(14),
        #    new CollectionsSampleObject(100),
        #    new CollectionsSampleObject(14),
        #    new CollectionsSampleObject(100)
        #]


    }

    /**
     * @throws CollectionsException
     */
    public static function orElse2()
    {

        ############ Example 2 (get collection of strings, if any number occur, replace it to be string 'xyz') ############


        $manyObjects = ['aaa', 123, 'bb', 33, 'abc', 'bb'];

        $collection = Collections::orElse($manyObjects, function ($obj1) {
            return is_numeric($obj1) ? true : false;
        }, 'xyz');

        var_dump($collection); # [ 'aaa', 'xyz', 'bb', 'xyz', 'abc', 'bb' ]

    }

    /**
     * @throws CollectionsException
     */
    public static function orElse3()
    {

        ############ Example 3 (get collection of positive numbers, any negative one replace to default 0) ############


        $manyObjects = [2, 11.03, -12, 5, 19.2, -19];

        $collection = Collections::orElse($manyObjects, function ($obj1) {
            return $obj1 <= 0;
        }, 0);

        var_dump($collection); # [ 2, 11.03, 0, 5, 19.2, 0 ];

    }

}