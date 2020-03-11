<?php
declare(strict_types=1);

namespace Collections\Examples;

use Collections\CollectionsStream;
use Collections\Exceptions\CollectionsException;
use Collections\Exceptions\CollectionsInvalidInputException as CollectionsInvalidInputExceptionAlias;

/**
 * Class _StreamExamples
 * @package Collections\Examples
 */
class _StreamExamples
{

    /**
     * @throws CollectionsException
     * @throws CollectionsInvalidInputExceptionAlias
     */
    public static function stream()
    {

        ############ Example of consumer ############

        $manyObjects = [
            new CollectionsSampleObject(0, 13),
            new CollectionsSampleObject(1, 13),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(7, 11),
            new CollectionsSampleObject(14, 13),
            new CollectionsSampleObject(33, 2),
            new CollectionsSampleObject(33, 11),
            new CollectionsSampleObject(2, 1),
            new CollectionsSampleObject(21, 777),
            new CollectionsSampleObject(0, 134),
        ];

        /**
         * @var $stream CollectionsStream
         */
        $stream = CollectionsStream::fromIterable($manyObjects);
        $stream

            # change type from 14 => 444
            ->map(function (CollectionsSampleObject $obj) {
                if ($obj->getType() == 13) $obj->setType(444);
                return $obj;
            })

            # get only objects with ID != 1
            ->filter(function (CollectionsSampleObject $obj) {
                return $obj->getId() != 1;
            })

            # remove objects with type = 777
            ->reject(function (CollectionsSampleObject $obj) {
                return $obj->getType() == 777;
            })

            # get only unique ones (unique = with different type)
            ->distinct(function (CollectionsSampleObject $obj1, CollectionsSampleObject $obj2) {
                return $obj1->getType() == $obj2->getType();
            })

            # consider every object with ID = 0 as "empty"/null object we dont want
            # and replace such object to default one CollectionsSampleObject(1, 1);
            ->orElse(function (CollectionsSampleObject $obj1) {
                return $obj1->getId() == 0;
            }, new CollectionsSampleObject(1, 1))

            # sort objects from the highest ID to the lowest
            ->sort(function (CollectionsSampleObject $obj1, CollectionsSampleObject $obj2) {
                return $obj1->getId() >= $obj2->getId();
            })

            # group our collection by object's type
            # after groupping in the stream we will have array like below one
            /*
                [
                    2 => [ CollectionsSampleObject(33, 2) ]
                    11 => [ CollectionsSampleObject(7, 11) ]
                    1 => [ CollectionsSampleObject(2, 1),  CollectionsSampleObject(1, 1),  CollectionsSampleObject(1, 1) ]
                ]
            */
            ->groupBy(function (CollectionsSampleObject $obj) {
                return $obj->getType();
            });


        # now we can e.g. transform that array of elements back
        # to simple list of objects by using reducer
        # [ CollectionsSampleObject(33, 2), CollectionsSampleObject(7, 11) ...]

        $reducedStream = $stream
            ->reduce(function ($acumulated, array $obj) {
                $acumulated ??= [];
                $acumulated = array_merge($acumulated, array_values($obj));
                return $acumulated;
            });

        var_dump($reducedStream);
        /*
            [
                CollectionsSampleObject(33, 2),
                CollectionsSampleObject(7, 11),
                CollectionsSampleObject(2, 1),
                CollectionsSampleObject(1, 1),
                CollectionsSampleObject(1, 1)
            ]
        */

        # we can also reduce it to e.g. concatenation of those objects' IDs
        $reducedToIds = $stream
            ->reduce(function ($acumulated, $obj) {
                $acumulated ??= '';

                foreach ($obj as $item) {
                    $acumulated .= ',' . strval($item->getId());
                }

                return ltrim($acumulated, ',');
            });

        var_dump($reducedToIds); // 33,7,2,1,1

    }

}