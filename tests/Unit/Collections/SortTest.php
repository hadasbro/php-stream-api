<?php
declare(strict_types=1);

namespace Tests\Unit\Collections;

use Collections\Collections;
use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;

use PHPUnit\Framework\TestCase;

class SortTest extends TestCase
{
    /**
     * sort
     *
     * @throws CollectionsException
     */
    public function testSort()
    {

        ############ Example 1 (sort collection of objects to start from objects with the lowest ID) ############


        $manyObjects = [
            new CollectionsSampleObject(1),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(33),
            new CollectionsSampleObject(33),
            new CollectionsSampleObject(2)
        ];

        # order ASCE (from the smallest object's IDs)
        $sortedCollection = Collections::sort($manyObjects, function (CollectionsSampleObject $obj1, CollectionsSampleObject $obj2) {
            # sorter should return TRUE if $obj1 is < $obj2, so if we expect order [$obj1, $obj2]
            # or oposite - FALSE - if we expect order [$obj1, $obj2]
            # this is in terms of any criteria we want to apply
            return $obj1->getId() < $obj2->getId();
        });

        $asString = Collections::reduce($sortedCollection, function ($str, CollectionsSampleObject $obj) {
            $str ??= '';
            $str .= serialize($obj);
            return $str;
        });

        $expected = 'C:44:"Collections\Examples\CollectionsSampleObject":17:{{"id":1,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":17:{{"id":2,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":17:{{"id":7,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":14,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":14,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":33,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":33,"type":1}}';

        $this->assertEquals($expected, $asString);

    }

    /**
     * sort
     *
     * @throws CollectionsException
     */
    public function testSort2()
    {

        ############ Example 2 (sort collection of objects to start from objects with the highest ID) ############

        $manyObjects = [
            new CollectionsSampleObject(1),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(33),
            new CollectionsSampleObject(33),
            new CollectionsSampleObject(2)
        ];


        # oposite order so DESC
        $sortedCollection = Collections::sort($manyObjects, function (CollectionsSampleObject $obj1, CollectionsSampleObject $obj2) {
            return $obj1->getId() > $obj2->getId();
        });


        $asString = Collections::reduce($sortedCollection, function ($str, CollectionsSampleObject $obj) {
            $str ??= '';
            $str .= serialize($obj);
            return $str;
        });

        $expected = 'C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":33,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":33,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":14,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":14,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":17:{{"id":7,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":17:{{"id":2,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":17:{{"id":1,"type":1}}';

        $this->assertEquals($expected, $asString);

    }

    /**
     * sort
     *
     * @throws CollectionsException
     */
    public function testSort3()
    {

        ############ Example 3 (sort collection of strings to be like in dictionary) ############


        $manyObjects = ['aaa', 'aaa', 'bb', 'aas', 'abc', 'bb'];

        /**
         * @var $unique string[]
         */
        $sortedCollection = Collections::sort($manyObjects, function (string $obj1, string $obj2) {
            return strcmp($obj1, $obj2) < 0;
        });


        $asString = Collections::reduce($sortedCollection, function ($str, string $obj) {
            $str ??= '';
            $str .= $obj;
            return $str;
        });

        $expected = 'aaaaaaaasabcbbbb';

        $this->assertEquals($expected, $asString);
    }

    /**
     * sort
     *
     * @throws CollectionsException
     */
    public function testSort4()
    {

        ############ Example 4 (sort collection numbers ASC ) ############


        $manyObjects = [2, 11.03, -12, 5, 19.2, -19];

        /**
         * @var $unique string[]
         */
        $sortedCollection = Collections::sort($manyObjects, function ($obj1, $obj2) {
            return $obj1 < $obj2;
        });

        $sortedCollectionDesc = Collections::sort($manyObjects, function ($obj1, $obj2) {
            return $obj1 > $obj2;
        });

        $this->assertTrue(arrays_have_same_simle_elements($sortedCollection, [ -19, -12, 2, 5, 11.03, 19.2 ]));

        $this->assertTrue(arrays_have_same_simle_elements($sortedCollectionDesc, [ 19.2, 11.03, 5, 2, -12, -19 ]));


    }

}