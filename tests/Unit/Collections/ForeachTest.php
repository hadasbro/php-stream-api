<?php
declare(strict_types=1);

namespace Tests\Unit\Collections;
use PHPUnit\Framework\TestCase;

use Collections\Collections;
use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;

class ForeachTest extends TestCase
{

    /**
     * testForEach
     *
     * @throws CollectionsException
     */
    public function testForEach()
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

        $acumulateResultsFOrTest = 'xyz';

        Collections::forEach($manyObjects, function (CollectionsSampleObject $obj1) use(&$acumulateResultsFOrTest) {
            $acumulateResultsFOrTest .= 'OBJ#' . $obj1->getId();
        });


        $this->assertEquals('xyzOBJ#1OBJ#14OBJ#7OBJ#14OBJ#33OBJ#33OBJ#2', $acumulateResultsFOrTest);



    }

}