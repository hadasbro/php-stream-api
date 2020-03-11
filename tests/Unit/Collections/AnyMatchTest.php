<?php
declare(strict_types=1);

namespace Tests\Unit\Collections;
use PHPUnit\Framework\TestCase;

use Collections\Collections;
use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;


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
        $anyMatch = Collections::anyMatch($manyObjects, function (int $obj1) use ($objects) {
            # predicate method should return TRUE if we consider element as
            # matching to our criteria or FALSE if element doesnt match

            return in_array($obj1, $objects);
        });

        $this->assertFalse($anyMatch);

    }

    /**
     * @throws CollectionsException
     */
    public function testAnyMatch2()
    {

        ############ Example 2 (any string from our array is vulgar) ############


        $manyObjects = ['I', 'want', 'to', 'go', 'home', 'ImVulgar'];

        /**
         * @var $anyMatch bool
         */
        $anyMatch = Collections::anyMatch($manyObjects, function (string $obj1) {
            $vulgarWords = ['ImVulgar', 'ImRude'];
            return in_array($obj1, $vulgarWords);
        });

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
        $anyMatch = Collections::anyMatch($manyObjects, function (CollectionsSampleObject $obj1) {
            return $obj1->getId() > 15;
        });

        $this->assertTrue($anyMatch);


        ############ Example 4 (I have at least one object with ID > 150) ############

        /**
         * @var $anyMatch bool
         */
        $anyMatch2 = Collections::anyMatch($manyObjects, function (CollectionsSampleObject $obj1) {
            return $obj1->getId() > 150;
        });

        $this->assertFalse($anyMatch2);

    }

}