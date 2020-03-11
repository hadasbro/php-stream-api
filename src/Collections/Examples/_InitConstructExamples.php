<?php
declare(strict_types=1);

namespace Collections\Examples;

use Collections\CollectionsStream;
use Collections\Exceptions\CollectionsException;

/**
 * Class _InitConstructExamples
 * @package Collections\Examples
 */
class _InitConstructExamples
{

    /**
     * @throws CollectionsException
     */
    public static function init()
    {
        /*
         * constructor
         */
        $stream = new CollectionsStream([1, 2, 3, 4, 5, 6, 7]);
        var_dump($stream); # [ 1, 2, 3, 4, 5, 6, 7 ]

        /*
         * fromIterable
         */
        $stream = CollectionsStream::fromIterable([1, 2, 3, 4, 5]);
        var_dump($stream); # [ 1, 2, 3, 4, 5 ]

        /*
         * fromStartValueAndTransformer
         */
        $stream = CollectionsStream::fromStartValueAndTransformer(1, 15, function ($value) {return $value + 1;});
        var_dump($stream); # [ 1, 2, 3, 4, 5, 6, ..., 15]

        /*
         * fromProducer
         */
        $stream = CollectionsStream::fromProducer(function () { $data = []; for($i = 1; $i < 20; $i++){ $data[] = $i; }; return $data; });
        var_dump($stream); # [ 1, 2, 3, 4, 5, 6, ..., 15]

        /*
         * fromParams
         */
        $stream = CollectionsStream::fromParams(1, 2, 3, 4, 5);
        var_dump($stream); # [ 1, 2, 3, 4, 5]
    }

}