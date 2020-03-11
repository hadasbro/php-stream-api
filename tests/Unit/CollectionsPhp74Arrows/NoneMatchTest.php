<?php
declare(strict_types=1);

namespace Tests\Unit\CollectionsPhp74Arrows;

use Collections\Collections;

use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;
use PHPUnit\Framework\TestCase;


class NoneMatchTest extends TestCase
{
    /**
     * noneMatch
     *
     * @throws CollectionsException
     * @throws \Collections\Exceptions\CollectionsException
     */
    public function testNoneMatch()
    {


        ############ Example 1 (there is not even one element in our array which is also in another array) ############


        $manyObjects = [1, 2, 3, 4, 5];
        $objects = [21, 17, 1, 2, 3, 4, 5, 666];

        /**
         * @var $noneMatch bool
         */
        $noneMatch = Collections::noneMatch($manyObjects, function (int $obj1) use ($objects) {
            # predicate method should return TRUE if we consider element as
            # matching to our criteria or FALSE if element doesnt match

            return in_array($obj1, $objects);
        });

        $this->assertFalse($noneMatch);

    }

    /**
     * noneMatch2
     *
     * @throws CollectionsException
     */
    public function testNoneMatch2()
    {

        ############ Example 2 (there is no even one vulgar word in our array) ############


        $manyObjects = ['I', 'want', 'to', 'go', 'home'];

        /**
         * @var $noneMatch bool
         */
        $noneMatch = Collections::noneMatch($manyObjects, function (string $obj1) {
            $vulgarWords = ['ImVulgar', 'ImRude'];
            return in_array($obj1, $vulgarWords);
        });

        $noneMatch2 = Collections::noneMatch($manyObjects, function (string $obj1) {
            $vulgarWords = ['xa', 'I', 'want', 'to', 'go', 'home', 'ImVulgar', 'somtething'];
            return in_array($obj1, $vulgarWords);
        });

        $this->assertTrue($noneMatch);

        $this->assertFalse($noneMatch2);

    }

    /**
     * noneMatch3
     *
     * @throws CollectionsException
     */
    public function testNoneMatch3()
    {

        ############ Example 3 (there is no even 1 object with ID > 15) ############

        $manyObjects = [
            new CollectionsSampleObject(16),
            new CollectionsSampleObject(33)
        ];

        /**
         * @var $noneMatch bool
         */
        $noneMatch = Collections::noneMatch($manyObjects, fn(CollectionsSampleObject $obj1) => $obj1->getId() > 15);

        $this->assertFalse($noneMatch);

    }

    /**
     * noneMatch4
     *
     * @throws CollectionsException
     */
    public function testNoneMatch4()
    {

        ############ Example 4 (There is no even 1 object with ID > 150) ############

        $manyObjects = [
            new CollectionsSampleObject(16),
            new CollectionsSampleObject(33)
        ];

        /**
         * @var $noneMatch bool
         */
        $noneMatch = Collections::noneMatch($manyObjects, fn(CollectionsSampleObject $obj1) => $obj1->getId() > 150);

        $this->assertTrue($noneMatch);

    }
}