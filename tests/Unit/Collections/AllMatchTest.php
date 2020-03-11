<?php
declare(strict_types=1);

namespace Tests\Unit\Collections;

use Collections\Collections;
use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;
use PHPUnit\Framework\TestCase;

class AllMatchTest extends TestCase
{

    /**
     * @throws CollectionsException
     */
    public function testAllMatch()
    {

        ############ Example 1 (all objects from our array are also in another array) ############


        $manyObjects = [1, 2, 3, 4, 5];
        $objects = [21, 17, 1, 2, 3, 4, 5, 666];

        /**
         * @var $allMatch bool
         */
        $allMatch = Collections::allMatch($manyObjects, function (int $obj1) use ($objects) {
            # predicate method should return TRUE if we consider element as
            # matching to our criteria or FALSE if element doesnt match

            return in_array($obj1, $objects);
        });


        $this->assertTrue($allMatch);

    }

    /**
     * @throws CollectionsException
     */
    public function testAllMatch3()
    {

        ############ Example 2 (all object we in array have ID > 15) ############

        $manyObjects = [
            new CollectionsSampleObject(16),
            new CollectionsSampleObject(33)
        ];

        ############ Example 2 (All objects have ID > 150) ############

        /**
         * @var $allMatch bool
         */
        $allMatch = Collections::allMatch($manyObjects, function (CollectionsSampleObject $obj1) {
            return $obj1->getId() > 150;
        });

        $this->assertFalse($allMatch);

    }

}