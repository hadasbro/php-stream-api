<?php
declare(strict_types=1);

namespace Collections\Examples;

use Collections\Collections;
use Collections\Collector;

class _CollectorsExamples
{

    public static function generalTest() {

        try {

            $manyObjects = [
                new CollectionsSampleObject(1, 13),
                new CollectionsSampleObject(14, 13),
                new CollectionsSampleObject(7, 11),
                new CollectionsSampleObject(14, 13),
                new CollectionsSampleObject(33, 2),
                new CollectionsSampleObject(33, 11),
                new CollectionsSampleObject(2, 1)
            ];

            $collector = Collector::of(Collector::JOINING, function (CollectionsSampleObject $obj) { return $obj->getId(); });
            $collected = Collections::collect($manyObjects, $collector);
            va($collected);   // 1,14,7,14,33,33,2


            $collector = Collector::of(Collector::JOINING, function (CollectionsSampleObject $obj) { return ['value' => $obj->getId(), 'separator' => '@']; });
            $collected = Collections::collect($manyObjects, $collector);
            va($collected);   // 1@14@7@14@33@33@2


            $collector = Collector::of(Collector::SUMMING, function (CollectionsSampleObject $obj) { return $obj->getId(); });
            $collected = Collections::collect($manyObjects, $collector);
            va($collected);  // 104


            $collector = Collector::of(Collector::MULTIPLYING, function (CollectionsSampleObject $obj) { return $obj->getId(); });
            $collected = Collections::collect($manyObjects, $collector);
            va($collected);  // 2 988 216


            $collector = Collector::of(Collector::TO_FLAT_ARRAY, function (CollectionsSampleObject $obj) { return $obj->getId(); });
            $collected = Collections::collect($manyObjects, $collector);
            va($collected);  // [ 1, 14, 7, 14, 33, 33, 2 ]


            $collector = Collector::of(Collector::TO_CONCAT_OF_STRINGS, function (CollectionsSampleObject $obj) { return $obj->getId(); });
            $collected = Collections::collect($manyObjects, $collector);
            va($collected);  // '1','14','7','14','33','33','2'


            $collector = Collector::of(Collector::TO_ASSOC_ARRAY, function (CollectionsSampleObject $obj) { return ['key' => $obj->getType(), 'value' => $obj]; });
            $collected = Collections::collect($manyObjects, $collector);
            va($collected);
            /*
                [
                    13 => new CollectionsSampleObject(14, 13),
                    11 => new CollectionsSampleObject(33, 11),
                    2 => new CollectionsSampleObject(2, 2)
                    1 => new CollectionsSampleObject(2, 1)
                ]
            */


            $collector = Collector::of(Collector::TO_ASSOC_ARRAY, function (CollectionsSampleObject $obj) { return ['key' => $obj->getId(), 'value' => $obj]; });
            $collected = Collections::collect($manyObjects, $collector);
            va($collected);
            /*
                [
                    1 => new CollectionsSampleObject(1, 13),
                    14 => new CollectionsSampleObject(14, 13),
                    7 => new CollectionsSampleObject(7, 11),
                    14 => new CollectionsSampleObject(14, 13),
                    33 => new CollectionsSampleObject(33, 2),
                    33 => new CollectionsSampleObject(33, 11),
                    2 => new CollectionsSampleObject(2, 1)
                ]
            */

        } catch (\Throwable $t) {
            vae($t);
        }


    }


}