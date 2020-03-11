<?php
declare(strict_types=1);

namespace Tests\Unit\CollectionsPhp74Arrows;

use Collections\Collections;

use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;
use PHPUnit\Framework\TestCase;



class OrElseTest extends TestCase
{
    /**
     * orElse
     *
     * @throws CollectionsException
     * @throws \Collections\Exceptions\CollectionsException
     */
    public function testOrElse()
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
        $collection = Collections::orElse($manyObjects, fn(CollectionsSampleObject $obj1) => $obj1->getId() <= 0, new CollectionsSampleObject(100));


        $asString = Collections::reduce($collection, function ($str, CollectionsSampleObject $obj) {
            $str ??= '';
            $str .= serialize($obj);
            return $str;
        });

        $expected = 'C:44:"Collections\Examples\CollectionsSampleObject":19:{{"id":100,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":14,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":19:{{"id":100,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":14,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":19:{{"id":100,"type":1}}';

        $this->assertEquals($expected, $asString);

    }

}