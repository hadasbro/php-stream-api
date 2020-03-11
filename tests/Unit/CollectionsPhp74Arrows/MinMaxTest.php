<?php
declare(strict_types=1);

namespace Tests\Unit\CollectionsPhp74Arrows;

use Collections\Collections;

use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;
use PHPUnit\Framework\TestCase;

class MinMaxTest extends TestCase
{

    /**
     * minMax
     *
     * @throws CollectionsException
     * @throws \Collections\Exceptions\CollectionsException
     */
    public function testMinMax()
    {

        ############ Example 1 (get object with the lowest ID) ############

        $manyObjects = [
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7),
            new CollectionsSampleObject(1),
            new CollectionsSampleObject(33)
        ];

        /**
         * @var $minElement CollectionsSampleObject
         */
        $minElement = Collections::min($manyObjects, fn(CollectionsSampleObject $obj1, CollectionsSampleObject $obj2) => $obj1->getId() < $obj2->getId());

        $expected = 'C:44:"Collections\Examples\CollectionsSampleObject":17:{{"id":1,"type":1}}';

        $this->assertEquals($expected, serialize($minElement));

        $this->assertEquals($minElement->getId(), 1);

    }

    /**
     * @throws CollectionsException
     */
    public function testMinMax2()
    {

        ############ Example 2 (get first element in dictionary order) ############


        $manyElements = ['fgh', 'abc', 'j'];

        /**
         * @var string $minElement
         */
        $minElement = Collections::min($manyElements, fn(string $obj1, string $obj2) => $obj1 < $obj2);

        $this->assertEquals(serialize($minElement), 's:3:"abc";');

    }

    /**
     * @throws CollectionsException
     */
    public function testMinMax3()
    {

        ############ Example 3 (get the array with the smallest sum of elements) ############

        $manyElements = array([123, 345, 4334], [1, 2], [0, -30]);

        /**
         * @var array $minElement
         */
        $minElement = Collections::min($manyElements, fn(array $obj1, array $obj2) => array_sum($obj1) < array_sum($obj2));

        $this->assertEquals($minElement, [0, -30]);

    }
}