<?php
declare(strict_types=1);

namespace Tests\Unit\Collections;

use Collections\Collections;
use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;
use PHPUnit\Framework\TestCase;
use Throwable;


class ToArrayAssocTest extends TestCase
{

    /**
     * @throws CollectionsException
     */
    public function testToAssocArray()
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

        $excThrowed = false;

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
        } catch (Throwable $t) {
            $excThrowed = true;
        }

        $this->assertTrue($excThrowed);


        # non strict - take the latest value in case of dupplicated key
        $assocArray = Collections::toAssocArray($manyObjects, function (CollectionsSampleObject $obj1) {
            return $obj1->getType();
        }, false);

        $this->assertTrue(arrays_have_same_simle_elements(array_keys($assocArray), [13, 11, 2, 1]));



        # Assoc array as a source - return the same array (because we have assoc array already)
        $assocArray = Collections::toAssocArray(['a' => 1, 'b' => 2], function () {
            # this is gonna be ignored because we already have
            # assoc array, so we dont modify current keys and values
            return 123;
        }, false);

        $this->assertTrue(arrays_have_same_simle_elements($assocArray, ['a' => 1, 'b' => 2]));

    }

}