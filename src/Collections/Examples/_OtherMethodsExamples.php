<?php
declare(strict_types=1);

namespace Collections\Examples;

use Collections\Collections;
use Collections\Exceptions\CollectionsException;

/**
 * Class _OtherMethodsExamples
 * @package Collections\Examples
 */
class _OtherMethodsExamples
{

    /**
     * @throws CollectionsException
     */
    public static function test()
    {

        ############ Example (emty, notEmpty) ############

        $manyObjects = [
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7),
            new CollectionsSampleObject(1),
            new CollectionsSampleObject(33)
        ];

        $empty = Collections::isEmpty($manyObjects);
        $notEmpty = Collections::isNotEmpty($manyObjects);

        var_dump([$empty, $notEmpty]); #   [ true, false ]


    }

    /**
     * @throws CollectionsException
     * @throws \Collections\Exceptions\CollectionsNotImplementedException
     */
    public static function test2()
    {

        $manyObjects = [
            new CollectionsSampleObject(1, 13),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(7, 11),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(33, 2),
            new CollectionsSampleObject(33, 11),
            new CollectionsSampleObject(2, 1)
        ];

        $reversed = Collections::reverse($manyObjects);

        var_dump($reversed);

        /*
            [
                new CollectionsSampleObject(2, 1),
                new CollectionsSampleObject(33, 11),
                new CollectionsSampleObject(33, 2),
                new CollectionsSampleObject(14, 13),
                new CollectionsSampleObject(7, 11),
                new CollectionsSampleObject(14, 13),
                new CollectionsSampleObject(1, 13),
            ]
        */


        $skipped = Collections::skip($reversed, 2);

        var_dump($skipped);

        /*
            [
                new CollectionsSampleObject(33, 2),
                new CollectionsSampleObject(14, 13),
                new CollectionsSampleObject(7, 11),
                new CollectionsSampleObject(14, 13),
                new CollectionsSampleObject(1, 13),
            ]
        */


        $limitted = Collections::limit($skipped, 4);

        var_dump($limitted);

        /*
            [
                new CollectionsSampleObject(33, 2),
                new CollectionsSampleObject(14, 13),
                new CollectionsSampleObject(7, 11),
                new CollectionsSampleObject(14, 13),
            ]
        */

        $added = Collections::append($limitted, new CollectionsSampleObject(67, 3));

        var_dump($added);

        /*
            [
                new CollectionsSampleObject(33, 2),
                new CollectionsSampleObject(14, 13),
                new CollectionsSampleObject(7, 11),
                new CollectionsSampleObject(14, 13),
            ]
        */


        $added = Collections::prepend($limitted, new CollectionsSampleObject(61, 1));

        var_dump($added);

        /*
            [
                new CollectionsSampleObject(11, 61),
                new CollectionsSampleObject(33, 2),
                new CollectionsSampleObject(14, 13),
                new CollectionsSampleObject(7, 11),
                new CollectionsSampleObject(14, 13),
            ]
        */


        $manyObjects = [1, 2, 3, 4, 5];
        $reversed = Collections::reverse($manyObjects);
        var_dump($reversed); // [5, 4, 3, 2, 1];


        $manyObjects = ['a', 'b', 'c'];
        $reversed = Collections::reverse($manyObjects);
        var_dump($reversed); // ['c', 'b', 'a']


        $manyObjects = ['a', 'b', 'c'];
        $reversed = Collections::shuffle($manyObjects);
        var_dump($reversed); // ['c', 'b', 'a']


    }


}