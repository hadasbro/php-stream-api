<?php
declare(strict_types=1);

namespace Tests\Unit\Collections;

use Collections\Collections;
use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;

use PHPUnit\Framework\TestCase;

class OtherMethodsTest extends TestCase
{

    /**
     * @throws CollectionsException
     */
    public function testTest()
    {

        ############ Example (emty, notEmpty) ############

        $manyObjects = [
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7),
            new CollectionsSampleObject(1),
            new CollectionsSampleObject(33)
        ];

        $empty = Collections::isEmpty($manyObjects);
        $notEmpty = Collections::isNotEmpty($manyObjects);

        $this->assertTrue($empty);
        $this->assertFalse($notEmpty);

    }

    /**
     * @throws CollectionsException
     * @throws \Collections\Exceptions\CollectionsNotImplementedException
     */
    public function testTest2(){

        $manyObjects = [
            new CollectionsSampleObject(1, 13),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(7, 11),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(33, 2),
            new CollectionsSampleObject(33, 11),
            new CollectionsSampleObject(2, 1)
        ];

        /**
         * @var $reversed CollectionsSampleObject[]
         */
        $reversed = Collections::reverse($manyObjects);

        $this->assertTrue($reversed[0]->getId() == 2);
        $this->assertTrue($reversed[1]->getId() == 33);


        $skipped = Collections::skip($reversed, 2);
        $this->assertTrue($skipped[0]->getId() == 33);
        $this->assertTrue($skipped[1]->getId() == 14);


        $limitted = Collections::limit($skipped, 4);
        $this->assertTrue(count($limitted) == 4);
        $this->assertTrue($skipped[0]->getId() == 33);
        $this->assertTrue($skipped[3]->getId() == 14);

        /**
         * @var $reversed CollectionsSampleObject[]
         */
        $added = Collections::append($limitted, new CollectionsSampleObject(67, 3));

        $this->assertTrue($added[4]->getId() == 67);

        /**
         * @var $reversed CollectionsSampleObject[]
         */
        $added = Collections::prepend($limitted, new CollectionsSampleObject(61, 1));
        $this->assertTrue($added[0]->getId() == 61);


        $manyObjects = [1, 2, 3, 4, 5];
        $reversed = Collections::reverse($manyObjects);
        $this->assertTrue(arrays_have_same_simle_elements($manyObjects, $manyObjects));
        $this->assertEquals($reversed[0], 5);

        $manyObjects = ['a', 'b', 'c'];
        $reversed = Collections::shuffle($manyObjects);
        $this->assertTrue(arrays_have_same_simle_elements($manyObjects, $reversed));

    }
}