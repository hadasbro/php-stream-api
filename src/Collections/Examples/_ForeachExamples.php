<?php
declare(strict_types=1);

namespace Collections\Examples;

use Collections\Collections;
use Collections\Exceptions\CollectionsException;

/**
 * Class _ForeachExamples
 * @package Collections\Examples
 */
class _ForeachExamples
{

    /**
     * sort
     *
     * @throws CollectionsException
     */
    public static function sort2()
    {

        ############ Example of consumer ############

        $manyObjects = [
            new CollectionsSampleObject(1, 13),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(7, 11),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(33, 2),
            new CollectionsSampleObject(33, 11),
            new CollectionsSampleObject(2, 1)
        ];

        Collections::forEach($manyObjects, function (CollectionsSampleObject $obj1) {
            echo ' We just want to pring object ID whic is # ' . $obj1->getId() . PHP_EOL;
        });

        /*
            We just want to pring object ID whic is # 1
            We just want to pring object ID whic is # 14
            We just want to pring object ID whic is # 7
            We just want to pring object ID whic is # 14
            We just want to pring object ID whic is # 33
            We just want to pring object ID whic is # 33
            We just want to pring object ID whic is # 2
      */

    }

}