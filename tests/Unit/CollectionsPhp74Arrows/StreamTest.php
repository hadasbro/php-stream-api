<?php
declare(strict_types=1);

namespace Tests\Unit\CollectionsPhp74Arrows;

use Collections\Collections;
use Collections\CollectionsStream;

use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;
use Collections\Exceptions\CollectionsInvalidInputException;
use PHPUnit\Framework\TestCase;


class StreamTest extends TestCase
{

    /**
     * @throws CollectionsException
     * @throws CollectionsInvalidInputException
     * @throws \Collections\Exceptions\CollectionsException
     */
    public function testStream()
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
            ->map(fn(CollectionsSampleObject $obj) => ($obj->getType() == 13 && $obj->setType(444)) ? $obj : $obj)

            # get only objects with ID != 1
            ->filter(fn(CollectionsSampleObject $obj) => $obj->getId() != 1)

            # remove objects with type = 777
            ->reject(fn(CollectionsSampleObject $obj) => $obj->getType() == 777)

            # get only unique ones (unique = with different type)
            ->distinct(fn(CollectionsSampleObject $obj1, CollectionsSampleObject $obj2) => $obj1->getType() == $obj2->getType())

            # consider every object with ID = 0 as "empty"/null object we dont want
            # and replace such object to default one CollectionsSampleObject(1, 1);
            ->orElse(fn(CollectionsSampleObject $obj1) => $obj1->getId() == 0, new CollectionsSampleObject(1, 1))

            # sort objects from the highest ID to the lowest
            ->sort(fn(CollectionsSampleObject $obj1, CollectionsSampleObject $obj2) => $obj1->getId() >= $obj2->getId())

            # group our collection by object's type
            # after groupping in the stream we will have array like below one
            ->groupBy(fn(CollectionsSampleObject $obj) => $obj->getType());


        # now we can e.g. transform that array of elements back
        # to simple list of objects by using reducer
        # [ CollectionsSampleObject(33, 2), CollectionsSampleObject(7, 11) ...]

        $reducedStream = $stream
            ->reduce(
                fn ($acumulated, array $obj) => ($acumulated = array_merge([], is_array($acumulated) ? $acumulated : [], array_values($obj))) ? $acumulated : $acumulated
            );

        $str = '';

        $asString = Collections::reduce($reducedStream, fn ($str, CollectionsSampleObject $obj) => ($str .= serialize($obj)) ? $str : $str);

        $expected = 'C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":33,"type":2}}C:44:"Collections\Examples\CollectionsSampleObject":18:{{"id":7,"type":11}}C:44:"Collections\Examples\CollectionsSampleObject":17:{{"id":2,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":17:{{"id":1,"type":1}}C:44:"Collections\Examples\CollectionsSampleObject":17:{{"id":1,"type":1}}';

        $this->assertEquals($expected, $asString);

    }

}