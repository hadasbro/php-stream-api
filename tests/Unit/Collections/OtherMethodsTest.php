<?php
declare(strict_types=1);

namespace Tests\Unit\Collections;

use Collections\Collections;
use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;

use PHPUnit\Framework\TestCase;

class OtherMethodsTest extends TestCase
{

    /**
     * @throws CollectionsException
     */
    public function testTest()
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

        $this->assertTrue($empty);
        $this->assertFalse($notEmpty);

    }

}