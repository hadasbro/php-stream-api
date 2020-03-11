<?php
declare(strict_types=1);

namespace Tests\Unit\CollectionsPhp74Arrows;

use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;
use PHPUnit\Framework\TestCase;

use Collections\Collections;



class AnyMatchTest extends TestCase
{

    /**
     * @throws CollectionsException
     */
    public function testAnyMatch()
    {

        ############ Example 1 (any object from our array is also in another array) ############

        $manyObjects = [1, 2, 3, 4, 5];
        $objects = [21, 17];

        /**
         * @var $anyMatch bool
         */
        $anyMatch = Collections::anyMatch($manyObjects, fn(int $obj1) => in_array($obj1, $objects));

        $this->assertFalse($anyMatch);

    }

    /**
     * @throws CollectionsException
     * @throws \Collections\Exceptions\CollectionsException
     */
    public function testAnyMatch2()
    {

        ############ Example 2 (any string from our array is vulgar) ############


        $manyObjects = ['I', 'want', 'to', 'go', 'home', 'ImVulgar'];
        $vulgarWords = ['ImVulgar', 'ImRude'];

        /**
         * @var $anyMatch bool
         */
        $anyMatch = Collections::anyMatch($manyObjects, fn(string $obj1) => in_array($obj1, $vulgarWords));

        $this->assertTrue($anyMatch);

    }

    /**
     * @throws CollectionsException
     */
    public function testAnyMatch3()
    {

        ############ Example 3 (I have at least one object with ID > 15) ############

        $manyObjects = array(
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(33),
            new CollectionsSampleObject(33)
        );

        /**
         * @var $anyMatch bool
         */
        $anyMatch = Collections::anyMatch($manyObjects, fn(CollectionsSampleObject $obj1) => $obj1->getId() > 15);

        $this->assertTrue($anyMatch);


        ############ Example 4 (I have at least one object with ID > 150) ############

        /**
         * @var $anyMatch bool
         */
        $anyMatch2 = Collections::anyMatch($manyObjects, fn(CollectionsSampleObject $obj1) => $obj1->getId() > 150);

        $this->assertFalse($anyMatch2);

    }

}