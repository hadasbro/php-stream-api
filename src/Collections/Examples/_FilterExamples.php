<?php
declare(strict_types=1);

namespace Collections\Examples;

use Collections\Collections;
use Collections\Exceptions\CollectionsException;

/**
 * Class _FilterExamples
 * @package Collections
 */
class _FilterExamples
{
    /**
     * filter
     *
     * @throws CollectionsException
     */
    public static function filter()
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

        var_dump($filteredResult); #   [ CollectionsSampleObject(7), CollectionsSampleObject(1) ]


    }

    /**
     * filter2
     *
     * @throws CollectionsException
     */
    public static function filter2()
    {

        ############ Example 2 (get only arrays with numeric values) ############

        $manyObjects = [
            ['aa', 'bb'],
            [12, new class() {}],
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

        var_dump($filteredResult);  # [ [12, 1234], [43.23, 12] ]

    }

}