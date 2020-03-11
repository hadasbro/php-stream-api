<?php
declare(strict_types=1);

namespace Tests\Unit\Collections;
use PHPUnit\Framework\TestCase;
use Collections\Collections;
use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;


class CountTest extends TestCase
{

    /**
     * @throws CollectionsException
     */
    public function testCount()
    {
        ############ Example 1 (count) ############

        $manyObjects = [
            new CollectionsSampleObject(1),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(33),
            new CollectionsSampleObject(33),
            new CollectionsSampleObject(2)
        ];

        # normal count
        $sortedCollection = Collections::count($manyObjects);

        $this->assertEquals($sortedCollection, 7);

    }

    /**
     * @throws CollectionsException
     */
    public function testCount2()
    {

        ############ Example 2 (count with objects groupped by type) ############

        $manyObjects = [
            new CollectionsSampleObject(1, 13),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(7, 11),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(33, 2),
            new CollectionsSampleObject(33, 11),
            new CollectionsSampleObject(2, 1)
        ];

        # count with groupping by object's type
        $counted = Collections::countBy(
            $manyObjects,
            function (CollectionsSampleObject $obj) {
                return $obj->getType();
            }
        );

        $this->assertEquals($counted[13], 3);
        $this->assertEquals($counted[11], 2);
        $this->assertEquals($counted[2], 1);
        $this->assertEquals($counted[1], 1);

    }

}