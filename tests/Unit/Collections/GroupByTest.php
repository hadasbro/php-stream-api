<?php
declare(strict_types=1);

namespace Tests\Unit\Collections;

use Collections\Collections;
use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;
use Collections\Exceptions\CollectionsInvalidInputException;
use PHPUnit\Framework\TestCase;


class GroupByTest extends TestCase
{


    /**
     * @throws CollectionsInvalidInputException
     * @throws CollectionsException
     */
    public function testGroup()
    {
        ############ Example 1 (group objects by type) ############

        $manyObjects = [
            new CollectionsSampleObject(1, 13),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(7, 11),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(33, 2),
            new CollectionsSampleObject(33, 11),
            new CollectionsSampleObject(2, 1)
        ];

        # normal count
        $groupped = Collections::groupBy(
            $manyObjects,
            function (CollectionsSampleObject $obj) {
                return $obj->getType();
            }
        );

        $this->assertTrue(arrays_have_same_simle_elements(array_keys($groupped), [13, 11, 2, 1]));

        $reduced = Collections::reduce($groupped, function ($arr, $obj) {
            $arr ??= [];
            $arr[] = serialize($obj);
            return $arr;
        });


        $expected = 'a:3:{i:0;C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":1,"type":13}}i:1;C:44:"Collections\Examples\CollectionsSampleObject":19:{{"id":14,"type":13}}i:2;C:44:"Collections\Examples\CollectionsSampleObject":19:{{"id":14,"type":13}}}a:2:{i:0;C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":7,"type":11}}i:1;C:44:"Collections\Examples\CollectionsSampleObject":19:{{"id":33,"type":11}}}a:1:{i:0;C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":33,"type":2}}}a:1:{i:0;C:44:"Collections\Examples\CollectionsSampleObject":17:{{"id":2,"type":1}}}';

        $this->assertEquals($expected, implode('',$reduced));

    }

}