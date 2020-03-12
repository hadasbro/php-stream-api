<?php
declare(strict_types=1);

namespace Tests\Unit\Collections;

use Codeception\PHPUnit\TestCase;
use Collections\Collections;
use Collections\Collector;
use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;
use Collections\Exceptions\CollectionsInvalidInputException;

class CollectorsTest extends TestCase
{
    /**
     * @throws CollectionsException
     * @throws CollectionsInvalidInputException
     */
    public function testCollections()
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


        $collector = Collector::of(Collector::JOINING, function (CollectionsSampleObject $obj) { return $obj->getId(); });
        $collected = Collections::collect($manyObjects, $collector);
        $this->assertEquals('1,14,7,14,33,33,2', $collected);

        $collector = Collector::of(Collector::JOINING, function (CollectionsSampleObject $obj) { return ['value' => $obj->getId(), 'separator' => '@']; });
        $collected = Collections::collect($manyObjects, $collector);
        $this->assertEquals('1@14@7@14@33@33@2', $collected);


        $collector = Collector::of(Collector::SUMMING, function (CollectionsSampleObject $obj) {return $obj->getId();});
        $collected = Collections::collect($manyObjects, $collector);

        $this->assertEquals(104, $collected);


        $collector = Collector::of(Collector::MULTIPLYING, function (CollectionsSampleObject $obj) {
            return $obj->getId();
        });
        $collected = Collections::collect($manyObjects, $collector);
        $this->assertEquals( 2988216, $collected);


        $collector = Collector::of(Collector::TO_FLAT_ARRAY, function (CollectionsSampleObject $obj) {
            return $obj->getId();
        });
        $collected = Collections::collect($manyObjects, $collector);
        $this->assertTrue(arrays_have_same_simle_elements($collected, [ 1, 14, 7, 14, 33, 33, 2 ]));



        $collector = Collector::of(Collector::TO_CONCAT_OF_STRINGS, function (CollectionsSampleObject $obj) {
            return $obj->getId();
        });
        $collected = Collections::collect($manyObjects, $collector);
        $this->assertEquals(trim("'1','14','7','14','33','33','2'"), trim($collected));


        $collector = Collector::of(Collector::TO_ASSOC_ARRAY, function (CollectionsSampleObject $obj) {
            return ['key' => $obj->getType(), 'value' => $obj];
        });
        $collected = Collections::collect($manyObjects, $collector);
        $this->assertTrue(arrays_have_same_simle_elements([13,11,2,1], array_keys($collected)));
        $this->assertEquals($collected[13]->getId(), 14);
        $this->assertEquals($collected[11]->getId(), 33);



        $collector = Collector::of(Collector::TO_ASSOC_ARRAY, function (CollectionsSampleObject $obj) {
            return ['key' => $obj->getId(), 'value' => $obj];
        });
        $collected = Collections::collect($manyObjects, $collector);
        $this->assertTrue(arrays_have_same_simle_elements([1,14,7,14,33,2], array_keys($collected)));
        $this->assertEquals($collected[7]->getId(), 7);
        $this->assertEquals($collected[1]->getId(), 1);


    }

}