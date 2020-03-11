<?php
declare(strict_types=1);

namespace Tests\Unit\CollectionsPhp74Arrows;

use Collections\Collections;

use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;
use PHPUnit\Framework\TestCase;


class SortTest extends TestCase
{
    /**
     * sort
     *
     * @throws CollectionsException
     * @throws \Collections\Exceptions\CollectionsException
     */
    public function testSort()
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
        $sortedCollection = Collections::sort($manyObjects, fn (CollectionsSampleObject $obj1, CollectionsSampleObject $obj2) => $obj1->getId() < $obj2->getId());

        $asString = Collections::reduce($sortedCollection, function ($str, CollectionsSampleObject $obj) {
            $str ??= '';
            $str .= serialize($obj);
            return $str;
        });

        $expected = 'C:44:"Collections\Examples\CollectionsSampleObject":17:{{"id":1,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":17:{{"id":2,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":17:{{"id":7,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":14,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":14,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":33,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":33,"type":1}}';

        $this->assertEquals($expected, $asString);

    }

}