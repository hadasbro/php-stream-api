<?php
declare(strict_types=1);

namespace Tests\Unit\Collections;

use Collections\Collections;
use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;

use PHPUnit\Framework\TestCase;

class OrElseTest extends TestCase
{
    /**
     * orElse
     *
     * @throws CollectionsException
     */
    public function testOrElse()
    {

        ############ Example 1 (get collection with objects but replace each one with ID <= 0 to default one) ############


        $manyObjects = [
            new CollectionsSampleObject(0),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(-1),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(-22)
        ];

        # order ASCE (from the smallest object's IDs)
        $collection = Collections::orElse($manyObjects, function (CollectionsSampleObject $obj1) {
            # return TRUE if we consider that particular object as "NULL" in any meaning
            # (if we want to replace that kind of object to any other - default one)
            # here - every object with ID <= 0 we consider as "NULL one" and we want
            # to use default object (CollectionsSampleObject(100)) instead
            return $obj1->getId() <= 0;
        }, new CollectionsSampleObject(100));


        $asString = Collections::reduce($collection, function ($str, CollectionsSampleObject $obj) {
            $str ??= '';
            $str .= serialize($obj);
            return $str;
        });

        $expected = 'C:44:"Collections\Examples\CollectionsSampleObject":19:{{"id":100,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":14,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":19:{{"id":100,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":14,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":19:{{"id":100,"type":1}}';

        $this->assertEquals($expected, $asString);

    }

    /**
     * @throws CollectionsException
     */
    public function testOrElse2()
    {

        ############ Example 2 (get collection of strings, if any number occur, replace it to be string 'xyz') ############


        $manyObjects = ['aaa', 123, 'bb', 33, 'abc', 'bb'];

        $collection = Collections::orElse($manyObjects, function ($obj1) {
            return is_numeric($obj1) ? true : false;
        }, 'xyz');

        $this->assertTrue(arrays_have_same_simle_elements($collection, [ 'aaa', 'xyz', 'bb', 'xyz', 'abc', 'bb' ]));

    }

    /**
     * @throws CollectionsException
     */
    public function testOrElse3()
    {

        ############ Example 3 (get collection of positive numbers, any negative one replace to default 0) ############


        $manyObjects = [2, 11.03, -12, 5, 19.2, -19];

        $collection = Collections::orElse($manyObjects, function ($obj1) {
            return $obj1 <= 0;
        }, 0);

        $this->assertTrue(arrays_have_same_simle_elements($collection, [ 2, 11.03, 0, 5, 19.2, 0 ]));

    }

}