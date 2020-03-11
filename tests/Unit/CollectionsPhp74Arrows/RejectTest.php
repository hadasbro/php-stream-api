<?php
declare(strict_types=1);

namespace Tests\Unit\CollectionsPhp74Arrows;

use Collections\Collections;

use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;
use PHPUnit\Framework\TestCase;


class RejectTest extends TestCase
{
    /**
     * reject
     *
     * @throws CollectionsException
     * @throws \Collections\Exceptions\CollectionsException
     */
    public function testReject()
    {

        ############ Example 1 (get only objects with the ID < 11) ############

        $manyObjects = [
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7),
            new CollectionsSampleObject(1),
            new CollectionsSampleObject(33)
        ];

        $filteredResult = Collections::reject($manyObjects, fn (CollectionsSampleObject $obj) => $obj->getId() < 11);

        $asString = Collections::reduce($filteredResult, function ($str, CollectionsSampleObject $obj) {
            $str ??= '';
            $str .= serialize($obj);
            return $str;
        });

        $expected = 'C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":14,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":33,"type":1}}';

        $this->assertEquals($expected, $asString);

    }

}