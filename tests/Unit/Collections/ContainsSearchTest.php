<?php
declare(strict_types=1);

namespace Tests\Unit\Collections;
use PHPUnit\Framework\TestCase;

use Collections\Collections;
use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;


class ContainsSearchTest extends TestCase
{
    /**
     * @throws CollectionsException
     */
    public function testContains()
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

        $this->assertTrue($contains);

        $this->assertFalse($contains2);

    }

    /**
     * @throws CollectionsException
     */
    public function testSearch()
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

        $this->assertEmpty($search);

        $asString = Collections::reduce($search2, function ($str, CollectionsSampleObject $obj) {
            $str ??='';
            $str .= serialize($obj);
            return $str;
        });

        $expected = 'C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":1,"type":13}}C:44:"Collections\Examples\CollectionsSampleObject":19:{{"id":14,"type":13}}C:44:"Collections\Examples\CollectionsSampleObject":19:{{"id":14,"type":13}}';

        $this->assertEquals($expected, $asString);

    }

}