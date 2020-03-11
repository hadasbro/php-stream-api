<?php
declare(strict_types=1);

namespace Collections\Examples;

use Collections\Collections;
use Collections\Exceptions\CollectionsException;

/**
 * Class _OtherMethodsExamples
 * @package Collections\Examples
 */
class _OtherMethodsExamples
{

    /**
     * @throws CollectionsException
     */
    public static function test()
    {

        ############ Example (emty, notEmpty) ############

        $manyObjects = [
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(7),
            new CollectionsSampleObject(1),
            new CollectionsSampleObject(33)
        ];

        $empty = Collections::isEmpty($manyObjects);
        $notEmpty = Collections::isNotEmpty($manyObjects);

        var_dump([$empty, $notEmpty]); #   [ true, false ]


    }

}