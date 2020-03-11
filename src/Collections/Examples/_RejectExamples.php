<?php
declare(strict_types=1);

namespace Collections\Examples;

use Collections\Collections;
use Collections\Exceptions\CollectionsException;

/**
 * Class _RejectExamples
 * @package Collections\Examples
 */
class _RejectExamples
{
    /**
     * reject
     *
     * @throws CollectionsException
     */
    public static function reject()
    {

        ############ Example 1 (get only objects with the ID < 11) ############

        $manyObjects = [
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7),
            new CollectionsSampleObject(1),
            new CollectionsSampleObject(33)
        ];

        $filteredResult = Collections::reject($manyObjects, function (CollectionsSampleObject $obj) {
            return $obj->getId() < 11;
        });

        var_dump($filteredResult); #   [ CollectionsSampleObject(14), CollectionsSampleObject(33) ]

    }

}