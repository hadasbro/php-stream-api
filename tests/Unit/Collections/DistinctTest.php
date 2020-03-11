<?php
declare(strict_types=1);

namespace Tests\Unit\Collections;
use Collections\Collections;
use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;
use PHPUnit\Framework\TestCase;

class DistinctTest extends TestCase
{
    /**
     * distinct
     *
     * @throws CollectionsException
     */
    public function testDistinct()
    {
        ############ Example 1 (get only objects with different IDs) ############

        $manyObjects = [
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(33),
            new CollectionsSampleObject(33),
        ];

        /**
         * @var $unique CollectionsSampleObject[]
         */
        $unique = Collections::distinct($manyObjects, function (CollectionsSampleObject $obj1, CollectionsSampleObject $obj2) {
            # comparator should return TRUE if $obj1 is equal $obj2
            # or FALSE if we consider $obj1 and $obj2 as unequal
            # this is in terms of any criteria we want to apply
            return $obj1->getId() == $obj2->getId();
        });


        $asString = Collections::reduce($unique, function ($str, CollectionsSampleObject $obj) {
            $str ??= '';
            $str .= serialize($obj);
            return $str;
        });

        $expected = 'C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":14,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":17:{{"id":7,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":33,"type":1}}';

        $this->assertEquals($expected, $asString);

    }

    /**
     * @throws CollectionsException
     */
    public function testDistinct2()
    {

        ############ Example 1 (get only unique string) ############


        $manyObjects = ['aaa', 'aaa', 'bb', 'aas', 'abc', 'bb'];

        /**
         * @var $unique string[]
         */
        $unique = Collections::distinct($manyObjects, function (string $obj1, string $obj2) {
            return $obj1 == $obj2;
        });

        $this->assertTrue(arrays_have_same_simle_elements($unique, ['aaa', 'bb', 'aas', 'abc']));

    }

}