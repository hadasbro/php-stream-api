<?php
declare(strict_types=1);

namespace Tests\Unit\Collections;

use Collections\CollectionsStream;
use Collections\Exceptions\CollectionsException;

use PHPUnit\Framework\TestCase;

class InitConstructTest extends TestCase
{

    /**
     * @throws CollectionsException
     */
    public function testInit()
    {
        /*
         * constructor
         */
        $stream = new CollectionsStream([1, 2, 3, 4, 5, 6, 7]);

        $this->assertTrue(arrays_have_same_simle_elements($stream->get(), [1, 2, 3, 4, 5, 6, 7]));

        /*
         * fromIterable
         */
        $stream = CollectionsStream::fromIterable([1, 2, 3, 4, 5]);
        $this->assertTrue(arrays_have_same_simle_elements($stream->get(), [1, 2, 3, 4, 5]));

        /*
         * fromStartValueAndTransformer
         */
        $stream = CollectionsStream::fromStartValueAndTransformer(1, 7, function ($value) {return $value + 1;});
        $this->assertTrue(arrays_have_same_simle_elements($stream->get(), [1, 2, 3, 4, 5, 6, 7]));

        /*
         * fromProducer
         */
        $stream = CollectionsStream::fromProducer(function () {
            $data = []; for($i = 1; $i < 6; $i++){ $data[] = $i; }
            return $data;
        });

        $this->assertTrue(arrays_have_same_simle_elements($stream->get(), [1, 2, 3, 4, 5]));

        /*
         * fromParams
         */
        $stream = CollectionsStream::fromParams(1, 2, 3, 4, 5);

        $this->assertTrue(arrays_have_same_simle_elements($stream->get(), [1, 2, 3, 4, 5]));

    }

}