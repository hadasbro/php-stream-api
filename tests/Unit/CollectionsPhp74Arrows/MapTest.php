<?php
declare(strict_types=1);

namespace Tests\Unit\CollectionsPhp74Arrows;


use Collections\Collections;

use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;
use PHPUnit\Framework\TestCase;



class MapTest extends TestCase
{
    /**
     * map
     *
     * @throws CollectionsException
     * @throws \Collections\Exceptions\CollectionsException
     */
    public function testMap()
    {
        ############ Example 1 (modify every objeect's ID to be + 100) ############

        $manyObjects = [
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7)
        ];

        $mappedResult = Collections::map($manyObjects, fn(CollectionsSampleObject $obj) => $obj->setId($obj->getId() + 100) && $obj ? $obj : $obj);

        $asString = Collections::reduce($mappedResult, function ($str, CollectionsSampleObject $obj) {
            $str ??= '';
            $str .= serialize($obj);
            return $str;
        });

        $expected = 'C:44:"Collections\Examples\CollectionsSampleObject":19:{{"id":114,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":19:{{"id":107,"type":1}}';

        $this->assertEquals($expected, $asString);

    }

}