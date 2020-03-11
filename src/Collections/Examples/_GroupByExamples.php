<?php
declare(strict_types=1);

namespace Collections\Examples;

use Collections\Collections;

/**
 * Class _GroupByExamples
 * @package Collections\Examples
 */
class _GroupByExamples
{

    /**
     * @throws \Collections\Exceptions\CollectionsInvalidInputException
     */
    public static function group()
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

        var_dump($groupped);
        /*
        [
            13 => [CollectionsSampleObject(1, 13), CollectionsSampleObject(14, 13), CollectionsSampleObject(14, 13)],
            11 => [CollectionsSampleObject(7, 11), CollectionsSampleObject(33, 11)],
            2 => [CollectionsSampleObject(33, 2)],
            1 => [CollectionsSampleObject(2, 1)]
        ];
        */

    }

}