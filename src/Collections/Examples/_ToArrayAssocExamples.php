<?php
declare(strict_types=1);

namespace Collections\Examples;

use Collections\Collections;
use Collections\Exceptions\CollectionsException;
use Throwable;

/**
 * Class _ToArrayAssocExamples
 * @package Collections\Examples
 */
class _ToArrayAssocExamples
{
    /**
     * @throws CollectionsException
     */
    public static function toAssocArray()
    {

        ############ Example of toAssocArray ############

        $manyObjects = [
            new CollectionsSampleObject(1, 13),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(7, 11),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(33, 2),
            new CollectionsSampleObject(33, 11),
            new CollectionsSampleObject(2, 1)
        ];

        try{
            # stict - dont tolerate dupplicated keys
            Collections::toAssocArray($manyObjects, function (CollectionsSampleObject $obj1) {
                return $obj1->getType();
            });
            /*
            <Exception> CollectionsInvalidInputException
            'Not unique key in Collections::toAssocArray.
            Strict mode require every key to be unique.
            Please verify your keyProducer.'
            */
        }catch (Throwable $t) {}


        # non strict - take the latest value in case of dupplicated key
        Collections::toAssocArray($manyObjects, function (CollectionsSampleObject $obj1) {
            return $obj1->getType();
        }, false);
        /*
        [
            14 => CollectionsSampleObject(14, 13),
            11 => CollectionsSampleObject(33, 2),
            2 => CollectionsSampleObject(2, 33),
            1 => CollectionsSampleObject(2, 1)
        ]
        */

        # Assoc array as a source - return the same array (because we have assoc array already)
        $assocArray = Collections::toAssocArray(['a' => 1, 'b' => 2], function () {
            # this is gonna be ignored because we already have
            # assoc array, so we dont modify current keys and values
            return 123;
        }, false);

        var_dump($assocArray);

        /*
        ['a' => 1, 'b' => 2]
        */

    }

}