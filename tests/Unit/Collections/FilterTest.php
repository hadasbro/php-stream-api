<?php
declare(strict_types=1);

namespace Tests\Unit\Collections;
use PHPUnit\Framework\TestCase;
use Collections\Collections;
use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;


class FilterTest extends TestCase
{
    /**
     * filter
     *
     * @throws CollectionsException
     */
    public function testFilter()
    {


        ############ Example 1 (get only objects with the ID < 11) ############

        $manyObjects = [
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7),
            new CollectionsSampleObject(1),
            new CollectionsSampleObject(33)
        ];

        $filteredResult = Collections::filter($manyObjects, function (CollectionsSampleObject $obj) {
            return $obj->getId() < 11;
        });

        $asString = Collections::reduce($filteredResult, function ($str, CollectionsSampleObject $obj) {
            $str ??= '';
            $str .= serialize($obj);
            return $str;
        });

        $expected = 'C:44:"Collections\Examples\CollectionsSampleObject":17:{{"id":7,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":17:{{"id":1,"type":1}}';

        $this->assertEquals($expected, $asString);


    }

    /**
     * filter2
     *
     * @throws CollectionsException
     */
    public function testFilter2()
    {

        ############ Example 2 (get only arrays with numeric values) ############

        $manyObjects = [
            ['aa', 'bb'],
            array(12, new class() {
            }),
            [12, 1234],
            [43.23, 12],
        ];

        $filteredResult = Collections::filter($manyObjects, function (array $obj) {
            foreach ($obj as $v) {
                if (!is_numeric($v))
                    return false;
            }
            return true;

        });


        $asString = Collections::reduce($filteredResult, function ($str, array $obj) {
            $str ??= '';
            $str .= implode('@', $obj);
            return $str;
        });

        $expected = '12@123443.23@12';

        $this->assertEquals($expected, $asString);

    }

}