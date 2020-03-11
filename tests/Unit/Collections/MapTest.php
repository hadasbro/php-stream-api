<?php
declare(strict_types=1);

namespace Tests\Unit\Collections;

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
     */
    public function testMap()
    {
        ############ Example 1 (modify every objeect's ID to be + 100) ############

        $manyObjects = [
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7)
        ];

        $mappedResult = Collections::map($manyObjects, function (CollectionsSampleObject $obj) {
            $obj->setId($obj->getId() + 100);
            return $obj;
        });

        $asString = Collections::reduce($mappedResult, function ($str, CollectionsSampleObject $obj) {
            $str ??= '';
            $str .= serialize($obj);
            return $str;
        });

        $expected = 'C:44:"Collections\Examples\CollectionsSampleObject":19:{{"id":114,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":19:{{"id":107,"type":1}}';

        $this->assertEquals($expected, $asString);

    }

    /**
     * @throws CollectionsException
     */
    public function testMap2()
    {

        ############ Example 1 (map all integers to be positive) ############

        $manyObjects = [-14, 12, 23, 4, -9];

        $mappedResult = Collections::map($manyObjects, function (int $obj) {
            return abs($obj);
        });

        $asString = Collections::reduce($mappedResult, function ($str, $obj) {
            $str ??= '';
            $str .= serialize($obj);
            return $str;
        });

        $expected = 'i:14;i:12;i:23;i:4;i:9;';
        $this->assertEquals($expected, $asString);
    }
}