# PHP Streams

![PHP Composer](https://github.com/hadasbro/php-stream-api/workflows/PHP%20Composer/badge.svg)

![PHP 7.4+](https://img.shields.io/badge/PHP-%3E%3D%207.4-brightgreen)
![License MIT](https://img.shields.io/badge/License-MIT-brightgreen)
![Ver 1.0.4](https://img.shields.io/badge/version-1.0.4-blue) 


This is just Collections API in PHP.
Library helps to handle collections such as `arrays` or 
in general `iterable` elements and perform 
operations on them. Operations on Collection/Stream can be chained. 

See usage examples in `/Examples` and unit tests in the project.

Project status: project is in progress, curent basic version is `ver 1.0.4`. 
Next versions will be using generators (`yield`) instead of normal looping and arrays.

## Install
Installation via Composer
```php
composer require hadasbro/php-streams
```


## Usage Examples

### Stream usage

#### Init / Construct options

```php
 
# Init via constructor
$stream = new CollectionsStream([1, 2, 3, 4, 5, 6, 7]);

# Init via CollectionsStream::fromIterable
$stream = CollectionsStream::fromIterable([1, 2, 3, 4, 5]);

# Init via CollectionsStream::fromStartValueAndTransformer
$stream = CollectionsStream::fromStartValueAndTransformer(1, 15, function ($value) {return $value + 1;});

# Init via CollectionsStream::fromStartValueAndTransformer - PHP 7.4 arrow functions
$stream = CollectionsStream::fromStartValueAndTransformer(1, 15, fn ($value) => $value + 1);

# Init via CollectionsStream::fromProducer
$stream = CollectionsStream::fromProducer(function () { 
    $data = []; for($i = 1; $i < 20; $i++){ $data[] = $i; }; return $data; 
});

# CollectionsStream::fromParams
$stream = CollectionsStream::fromParams(1, 2, 3, 4, 5);

```
#### Example of chaned operations on stream

##### Sample Object

```php
class SampleObj
{
    private int $id;
    private int $type = 1;

    public function __construct($lineId, $type = 1){
        $this->id = $lineId;
        $this->type = $type;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

}
```

##### Example - arrow functions used for operations (PHP >= 7.4)

See more [Arrow Functions PHP 7.4](https://wiki.php.net/rfc/arrow_functions)
```php
class StreamTest extends TestCase
{
    public function testStream()
    {

        $manyObjects = [
            new SampleObj(0, 13),
            new SampleObj(1, 13),
            new SampleObj(14, 13),
            new SampleObj(7, 11),
            new SampleObj(14, 13),
            new SampleObj(33, 2),
            new SampleObj(33, 11),
            new SampleObj(2, 1),
            new SampleObj(21, 777),
            new SampleObj(0, 134),
        ];

        /**
         * @var $stream CollectionsStream
         */
        $stream = CollectionsStream::fromIterable($manyObjects);
        
        $reducedStream = 
            
            $stream

            ->map(fn(SampleObj $obj) => ($obj->getType() == 13 && $obj->setType(444)) ? $obj : $obj)
            
            ->filter(fn(SampleObj $obj) => $obj->getId() != 1)
            
            ->reject(fn(SampleObj $obj) => $obj->getType() == 777)
            
            ->distinct(fn(SampleObj $obj1, SampleObj $obj2) => $obj1->getType() == $obj2->getType())
            
            ->orElse(fn(SampleObj $obj1) => $obj1->getId() == 0, new SampleObj(1, 1))
            
            ->sort(fn(SampleObj $obj1, SampleObj $obj2) => $obj1->getId() >= $obj2->getId())
            
            ->groupBy(fn(SampleObj $obj) => $obj->getType())

            ->reduce(
                fn ($acumulated, array $obj) => ($acumulated = array_merge([], is_array($acumulated) ? $acumulated : [], array_values($obj))) ? $acumulated : $acumulated
            );

        var_dump($reducedStream);
        /*
            [
                SampleObj(33, 2),
                SampleObj(7, 11),
                SampleObj(2, 1),
                SampleObj(1, 1),
                SampleObj(1, 1)
            ]
        */

    }

}
```

##### Example - lambda functions used for operations 

```php
class _StreamExamples
{
    public static function stream()
    {
        $manyObjects = [
            new SampleObj(0, 13),
            new SampleObj(1, 13),
            new SampleObj(14, 13),
            new SampleObj(7, 11),
            new SampleObj(14, 13),
            new SampleObj(33, 2),
            new SampleObj(33, 11),
            new SampleObj(2, 1),
            new SampleObj(21, 777),
            new SampleObj(0, 134),
        ];

        /**
         * @var $stream CollectionsStream
         */
        $stream = CollectionsStream::fromIterable($manyObjects);
        
        $stream

            # change type from 13 => 444
            ->map(function (SampleObj $obj) {
                if ($obj->getType() == 13) $obj->setType(444);
                return $obj;
            })

            # get only objects with ID != 1
            ->filter(function (SampleObj $obj) {
                return $obj->getId() != 1;
            })

            # remove objects with type = 777
            ->reject(function (SampleObj $obj) {
                return $obj->getType() == 777;
            })

            # get only unique ones (unique = with different type)
            ->distinct(function (SampleObj $obj1, SampleObj $obj2) {
                return $obj1->getType() == $obj2->getType();
            })

            # consider every object with ID = 0 as "empty"/null object we dont want
            # and replace such object to default one SampleObj(1, 1);
            ->orElse(function (SampleObj $obj1) {
                return $obj1->getId() == 0;
            }, new SampleObj(1, 1))

            # sort objects from the highest ID to the lowest
            ->sort(function (SampleObj $obj1, SampleObj $obj2) {
                return $obj1->getId() >= $obj2->getId();
            })

            # group our collection by object's type
            # after groupping in the stream we will have array like below one
            # [2 => [ SampleObj(33, 2) ], 11 => [ SampleObj(7, 11)]]
            ->groupBy(function (SampleObj $obj) {
                return $obj->getType();
            });


        # now we can e.g. transform that array of elements back
        # to simple list of objects by using reducer
        # [ SampleObj(33, 2), SampleObj(7, 11) ...]

        $reducedStream = $stream
            ->reduce(function ($acumulated, array $obj) {
                $acumulated ??= [];
                $acumulated = array_merge($acumulated, array_values($obj));
                return $acumulated;
            });

        var_dump($reducedStream);
        /*
            [
                SampleObj(33, 2),
                SampleObj(7, 11),
                SampleObj(2, 1),
                SampleObj(1, 1),
                SampleObj(1, 1)
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
```
### Raw examples, static usage - lambda functions used for operations


##### Example of `Collections::collect` (predefined collectors)

Examples:
```php
class _CollectorsExamples
{

    public static function generalTest() {

        try {

            $manyObjects = [
                new SampleObj(1, 13),
                new SampleObj(14, 13),
                new SampleObj(7, 11),
                new SampleObj(14, 13),
                new SampleObj(33, 2),
                new SampleObj(33, 11),
                new SampleObj(2, 1)
            ];

            # join all IDs into 1 string separated by commas
            $collector = Collector::of(Collector::JOINING, function (CollectionsSampleObject $obj) { return $obj->getId(); });
            $collected = Collections::collect($manyObjects, $collector);
            va($collected); // '1,14,7,14,33,33,2'
    
            # Join with custom separator - @
            $collector = Collector::of(Collector::JOINING, function (CollectionsSampleObject $obj) { return ['value' => $obj->getId(), 'separator' => '@']; });
            $collected = Collections::collect($manyObjects, $collector);
            va($collected); // 1@14@7@14@33@33@2

            # sum all elements
            $collector = Collector::of(Collector::SUMMING, function (SampleObj $obj) { return $obj->getId(); });
            $collected = Collections::collect($manyObjects, $collector);
            va($collected);  // 104

            # multiply all elements
            $collector = Collector::of(Collector::MULTIPLYING, function (SampleObj $obj) { return $obj->getId(); });
            $collected = Collections::collect($manyObjects, $collector);
            va($collected);  // 2 988 216

            # put all elements to array
            $collector = Collector::of(Collector::TO_FLAT_ARRAY, function (SampleObj $obj) { return $obj->getId(); });
            $collected = Collections::collect($manyObjects, $collector);
            va($collected);  // [ 1, 14, 7, 14, 33, 33, 2 ]

            # return string with all alements wrapped by single quote
            $collector = Collector::of(Collector::TO_CONCAT_OF_STRINGS, function (SampleObj $obj) { return $obj->getId(); });
            $collected = Collections::collect($manyObjects, $collector);
            va($collected);  // '1','14','7','14','33','33','2'

            # generate and return associative array of elements
            $collector = Collector::of(Collector::TO_ASSOC_ARRAY, function (SampleObj $obj) { 
                # here, in extractor, we need to define how do we want to 
                # generate key and value for each element
                return ['key' => $obj->getType(), 'value' => $obj]; 
            });

            $collected = Collections::collect($manyObjects, $collector);

            va($collected);
            /*
                [
                    13 => new SampleObj(14, 13),
                    11 => new SampleObj(33, 11),
                    2 => new SampleObj(2, 2)
                    1 => new SampleObj(2, 1)
                ]
            */


        } catch (\Throwable $t) {
            vae($t);
        }


    }

}
```

Predefined collectors
```php
class Collector extends CollectorOperations {
    
    const JOINING = 1; # [a, b, c] -> a,b,c
    const SUMMING = 2; # [a, b, c] -> a + b + c
    const MULTIPLYING = 3; # [a, b, c] -> a * b * c
    const TO_FLAT_ARRAY = 4; # [obj1, obj2, obj3] -> [ obj1->getId(), obj2->getId(), obj3->getId()]
    const TO_CONCAT_OF_STRINGS = 5; # [obj1, obj2] -> ['{obj1->getId()}', '{obj2->getId()}']
    
    /*
     * Note: if you use this collector, then your extractor must
     * specify how do you want to generate keys and values from your data
     * Your extractor must return [key => _how_to_obtain_key_, value => _how_to_obtain_value_ ]
     *
     * for example:
     *
     *      $collector = Collector::of(
     *          Collector::TO_ASSOC_ARRAY,
     *          function ($obj) { return ['key' => $obj->getId(), 'value' => $obj]; }
     *      );
     *
     */
    const TO_ASSOC_ARRAY = 6; # [obj1, obj2] -> [obj1->getId() => obj1, obj2->getId() => obj2]
}
```

##### Example of `Collections::allMatch` (all objects from our array are also in another array)

```php
class _AllMatchExamples
{
    public static function allMatch()
    {
        $manyObjects = [1, 2, 3, 4, 5];
        $objects = [21, 17, 1, 2, 3, 4, 5, 666];

        $allMatch = Collections::allMatch($manyObjects, function (int $obj1) use ($objects) {
            # predicate method should return TRUE if we consider element as
            # matching to our criteria or FALSE if element doesnt match
            return in_array($obj1, $objects);
        });

        var_dump($allMatch); // true

    }
}
```


##### Example of `Collections::distinct` (get only objects with different IDs) ############

```php
class _CollectionsExamples
{
    public static function distinct()
    {
        $manyObjects = [
            new SampleObj(14),
            new SampleObj(7),
            new SampleObj(14),
            new SampleObj(33),
            new SampleObj(33),
        ];

        $unique = Collections::distinct($manyObjects, function (SampleObj $obj1, SampleObj $obj2) {
            # comparator should return TRUE if $obj1 is equal $obj2
            # or FALSE if we consider $obj1 and $obj2 as unequal
            # this is in terms of any criteria we want to apply
            return $obj1->getId() == $obj2->getId();
        });

        var_dump($unique); # [ SampleObj(14),  SampleObj(7),  SampleObj(33), ]

    }

}
```


##### Example of `Collections::filter` (get only objects with the ID < 11)

```php

class _FilterExamples
{
    public static function filter()
    {
        $manyObjects = [
            new SampleObj(14),
            new SampleObj(7),
            new SampleObj(1),
            new SampleObj(33)
        ];

        $filteredResult = Collections::filter($manyObjects, function (SampleObj $obj) {
            return $obj->getId() < 11;
        });

        var_dump($filteredResult); #   [ SampleObj(7), SampleObj(1) ]

    }
}
```

##### Example of `Collections::reject` (remove objects with ID < 11)

```php

class _RejectExamples
{
    public static function reject()
    {
        $manyObjects = [
            new SampleObj(14),
            new SampleObj(7),
            new SampleObj(1),
            new SampleObj(33)
        ];

        $filteredResult = Collections::reject($manyObjects, function (SampleObj $obj) {
            return $obj->getId() < 11;
        });

        var_dump($filteredResult); # [ SampleObj(14), SampleObj(33) ]

    }

}

```
##### Example of `Collections::map`, `Collections::transform` (modify every objeect's ID to be + 100) 
```php

class _MapExamples
{
    public static function map()
    {
        $manyObjects = [
            new SampleObj(14),
            new SampleObj(7)
        ];

        $mappedResult = Collections::map($manyObjects, function (SampleObj $obj) {
            $obj->setId($obj->getId() + 100);
            return $obj;
        });

        var_dump($mappedResult); #   [ SampleObj(114), SampleObj(107) ]

    }

}
```

##### Example of `Collections::minMax` (get object with the lowest ID)
```php

class _MinMaxExamples
{
    public static function minMax()
    {
        $manyObjects = [
            new SampleObj(14),
            new SampleObj(7),
            new SampleObj(1),
            new SampleObj(33)
        ];

        $minElement = Collections::min($manyObjects, function (SampleObj $obj1, SampleObj $obj2) {
            # comparator should return TRUE if $obj1 < $obj2
            # in terms of any criteria we want to apply
            return $obj1->getId() < $obj2->getId();
        });

        var_dump($minElement); # new SampleObj(1),
        var_dump($minElement->getId()); # 1

    }

}
```

##### Example of Collections::sort (sort collection of objects to start from objects with the lowest ID) 

```php
class _SortExamples
{
    public static function sort()
    {
        $manyObjects = [
            new SampleObj(1),
            new SampleObj(14),
            new SampleObj(7),
            new SampleObj(14),
            new SampleObj(33),
            new SampleObj(33),
            new SampleObj(2)
        ];

        # order ASC (from the smallest object's IDs)
        $sortedCollection = Collections::sort($manyObjects, function (SampleObj $obj1, SampleObj $obj2) {
            # sorter should return TRUE if $obj1 is < $obj2, so if we expect order [$obj1, $obj2]
            # or oposite - FALSE - if we expect order [$obj1, $obj2]
            # this is in terms of any criteria we want to apply
            return $obj1->getId() < $obj2->getId();
        });

        var_dump($sortedCollection);
        /* 
            [
                new SampleObj(1),
                new SampleObj(2),
                new SampleObj(7),
                new SampleObj(14),
            ] 
        */

    }
}
```

##### Example of `Collections::orElse` (get collection with objects but replace each one with ID <= 0 to default one) 

```php
class _OrElseExamples
{
    public static function orElse()
    {
        $manyObjects = [
            new SampleObj(0),
            new SampleObj(14),
            new SampleObj(-1),
            new SampleObj(14),
            new SampleObj(-22)
        ];

        # order ASCE (from the smallest object's IDs)
        $collection = Collections::orElse($manyObjects, function (SampleObj $obj1) {
            # return TRUE if we consider that particular object as "NULL" in any meaning
            # (if we want to replace that kind of object to any other - default one)
            # here - every object with ID <= 0 we consider as "NULL one" and we want
            # to use default object (SampleObj(100)) instead
            return $obj1->getId() <= 0;
        }, new SampleObj(100));

        var_dump($collection);
        /* 
            [
                new SampleObj(100),
                new SampleObj(14),
                new SampleObj(100),
                new SampleObj(14),
                new SampleObj(100)
            ]
        */


    }
}
```

##### Example of Collections::reduce (reduce array of objects info arrayof their a bit modified IDs)

```php
class _ReduceExamples
{
    public static function reduce()
    {
        $manyObjects = [
            new SampleObj(0),
            new SampleObj(14),
            new SampleObj(-1),
            new SampleObj(14),
            new SampleObj(-22)
        ];

        # reduce array of objects to array if [ID#objects_id, ..., ID#objects_id]
        $reduced = Collections::reduce($manyObjects, function ($ids, SampleObj $obj) {
            
            # reducer is methos as the following
            # function ($reducedElement, $nextElement) { return $reducedElement; }
            # in reducet we should perform any kind of changes on $reducedElement in terms of any next $nextElement
            # e.g. sum/add next integer to already existing sum, add another element to array etc.

            $ids ??= [];
            $ids[] = 'ID#' . $obj->getId();
            return $ids;
        });

        var_dump($reduced);
        /* ["ID#0","ID#14","ID#-1", "ID#14", "ID#-22"] */

    }
}
```

##### Example of `Collections::groupBy` (group objects by type) 

```php

class _GroupByExamples
{
    public static function group()
    {
        $manyObjects = [
            new SampleObj(1, 13),
            new SampleObj(14, 13),
            new SampleObj(7, 11),
            new SampleObj(14, 13),
            new SampleObj(33, 2),
            new SampleObj(33, 11),
            new SampleObj(2, 1)
        ];

        # normal count
        $groupped = Collections::groupBy(
            $manyObjects,
            function (SampleObj $obj) {
                return $obj->getType();
            }
        );

        var_dump($groupped);
        /*
            [
                13 => [SampleObj(1, 13), SampleObj(14, 13), SampleObj(14, 13)],
                11 => [SampleObj(7, 11), SampleObj(33, 11)],
                2 => [SampleObj(33, 2)],
                1 => [SampleObj(2, 1)]
            ]
        */

    }

}
```



##### Example of `Collections::countBy` and `Collections::count` (group objects by type) 

```php

class _CountExamples
{
    public static function countBy()
    {
        $manyObjects = [
            new SampleObj(1, 13),
            new SampleObj(14, 13),
            new SampleObj(7, 11),
            new SampleObj(14, 13),
            new SampleObj(33, 2),
            new SampleObj(33, 11),
            new SampleObj(2, 1)
        ];

        # normal count
        $count = Collections::count($manyObjects);
        var_dump($count); # 7


        # count with groupping by object's type
        $counted = Collections::countBy(
            $manyObjects,
            function (SampleObj $obj) {
                return $obj->getType();
            }
        );

        var_dump($counted);
        /*
        [
            13 => 3
            11 => 2
            2 => 1
            1 => 1
        ]
        */

    }

}
```

##### Example of `Collections::contains` and `Collections::search` (search elements in a collection or just check if collection contains element)

```php
class _ContainsSearch
{
    public static function contains()
    {
        $manyObjects = [
            new SampleObj(1, 13),
            new SampleObj(14, 13),
            new SampleObj(7, 11),
            new SampleObj(14, 13),
            new SampleObj(33, 2),
            new SampleObj(33, 11),
            new SampleObj(2, 1)
        ];

        $contains = Collections::contains($manyObjects, function (SampleObj $obj1) {
            return $obj1->getId() == 14;
        });

        $contains2 = Collections::contains($manyObjects, function (SampleObj $obj1) {
            return $obj1->getId() == 144;
        });

        $search = Collections::search($manyObjects, function (SampleObj $obj1) {
            return $obj1->getId() == 144;
        });

        $search2 = Collections::search($manyObjects, function (SampleObj $obj1) {
            return $obj1->getType() == 13;
        });

        var_dump($contains); // true
        var_dump($contains2); // false
        var_dump($search); // []
        var_dump($search2); // [ SampleObj(1, 13), SampleObj(14, 13), SampleObj(14, 13) ]

    }

}
```

##### Example of `Collections::forEach` (consuming collection's elements)

```php
class _ForeachExamples
{
    public static function foreach()
    {
        $manyObjects = [
            new SampleObj(1, 13),
            new SampleObj(14, 13),
            new SampleObj(7, 11),
            new SampleObj(14, 13),
            new SampleObj(33, 2),
            new SampleObj(33, 11),
            new SampleObj(2, 1)
        ];

        Collections::forEach($manyObjects, function (SampleObj $obj1) {
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


```
##### Example of `Collections::toAssocArray` (transform collection to associative array of elements)

```php

class _ToArrayAssocExamples
{
    public static function toAssocArray()
    {       
        $manyObjects = [
            new SampleObj(1, 13),
            new SampleObj(14, 13),
            new SampleObj(7, 11),
            new SampleObj(14, 13),
            new SampleObj(33, 2),
            new SampleObj(33, 11),
            new SampleObj(2, 1)
        ];

        try{
            # stict - dont tolerate dupplicated keys
            $assocArray = Collections::toAssocArray($manyObjects, function (SampleObj $obj1) {
                return $obj1->getType();
            });
            /*
                <Exception> CollectionsInvalidInputException
                'Not unique key in Collections::toAssocArray.
                Strict mode require every key to be unique.
                Please verify your keyProducer.'
            */
        }catch (\Throwable $t) {}


        # non strict - take the latest value in case of dupplicated key
        $assocArray = Collections::toAssocArray($manyObjects, function (SampleObj $obj1) {
            return $obj1->getType();
        }, false);
        /*
            [
                14 => SampleObj(14, 13),
                11 => SampleObj(33, 2),
                2 => SampleObj(2, 33),
                1 => SampleObj(2, 1)
            ]
        */

        # Assoc array as a source - return the same array (because we have assoc array already)
        $assocArray = Collections::toAssocArray(['a' => 1, 'b' => 2], function ($obj1) {
            # this is gonna be ignored because we already have
            # assoc array, so we dont modify current keys and values
            return 123;
        }, false);
        /*
            ['a' => 1, 'b' => 2]
        */

    }

}
```


##### Other examples - `Collections::isEmpty`, `Collections::isNotEmpty` and others

```php

class _OtherMethodsExamples
{
    public static function test()
    {
        
        $manyObjects = [
            new SampleObj(14),
            new SampleObj(7),
            new SampleObj(1),
            new SampleObj(33)
        ];

        $empty = Collections::isEmpty($manyObjects);
        $notEmpty = Collections::isNotEmpty($manyObjects);

        var_dump([$empty, $notEmpty]); #   [ true, false ]

    }

}
```



### Raw examples, static usage - arrow functions used for operations (PHP >= 7.4)

##### Example of `Collections::allMatch`, `Collections::aanyMatch` (all objects from our array are also in another array)

```php
class _AllMatchExamples
{

    public static function allMatch()
    {

        $manyObjects = [1, 2, 3, 4, 5];
        $objects = [21, 17, 1, 2, 3, 4, 5, 666];

        /**
         * @var $allMatch bool
         */
        $allMatch = Collections::allMatch($manyObjects, fn(int $obj1) => in_array($obj1, $objects));

        var_dump($allMatch); // true

    }
    
    public static function anyMatch(){

        $manyObjects = [1, 2, 3, 4, 5];
        $objects = [21, 17];

        /**
         * @var $anyMatch bool
         */
        $anyMatch = Collections::anyMatch($manyObjects, fn(int $obj1) => in_array($obj1, $objects));

        var_dump($anyMatch); // false
    }

}
```

##### Example of `Collections::filter` (get only objects with the ID < 11)
```php

class _FilterExamples
{

    public static function filter()
    {
        $manyObjects = [
            new SampleObj(14),
            new SampleObj(7),
            new SampleObj(1),
            new SampleObj(33)
        ];

        $filteredResult = Collections::filter($manyObjects, fn(SampleObj $obj) => $obj->getId() < 11);

        var_dump($filteredResult); #   [ SampleObj(7), SampleObj(1) ]

    }
}
```

##### Example of `Collections::reject` (remove objects with ID < 11)

```php

class _RejectExamples
{

    public static function reject()
    {
        $manyObjects = [
            new SampleObj(14),
            new SampleObj(7),
            new SampleObj(1),
            new SampleObj(33)
        ];

        $filteredResult = Collections::reject($manyObjects, fn (SampleObj $obj) => $obj->getId() < 11);

        var_dump($filteredResult); #   [ SampleObj(14), SampleObj(33) ]

    }

}

```
##### Example of `Collections::map`, `Collections::transform` (modify every objeect's ID to be + 100) 
```php

class _MapExamples
{

    public static function map()
    {
        $manyObjects = [
            new SampleObj(14),
            new SampleObj(7)
        ];

        $mappedResult = Collections::map($manyObjects, fn(SampleObj $obj) => $obj->setId($obj->getId() + 100) && $obj ? $obj : $obj);

        var_dump($mappedResult); #   [ SampleObj(114), SampleObj(107) ]

    }

}
```

##### Example of `Collections::minMax` (get object with the lowest ID)
```php

class _MinMaxExamples
{

    public static function minMax()
    {
        $manyObjects = [
            new SampleObj(14),
            new SampleObj(7),
            new SampleObj(1),
            new SampleObj(33)
        ];

        /**
         * @var $minElement SampleObj
         */
        $minElement = Collections::min($manyObjects, fn(SampleObj $obj1, SampleObj $obj2) => $obj1->getId() < $obj2->getId());

        var_dump($minElement); # new SampleObj(1),

    }

}
```
##### Example of `Collections::distinct` (get only objects with different IDs)

```php
class _DistinctExamples
{

    public static function distinct()
    {
        $manyObjects = [
            new SampleObj(14),
            new SampleObj(7),
            new SampleObj(14),
            new SampleObj(33),
            new SampleObj(33),
        ];

        /**
         * @var $unique SampleObj[]
         */
        $unique = Collections::distinct($manyObjects, fn(SampleObj $obj1, SampleObj $obj2) => $obj1->getId() == $obj2->getId());

        var_dump($unique); # [ SampleObj(14),  SampleObj(7),  SampleObj(33), ]

    }

}
```
##### Example of Collections::sort (sort collection of objects to start from objects with the lowest ID) 

```php
class _SortExamples
{

    public static function sort()
    {
        $manyObjects = [
            new SampleObj(1),
            new SampleObj(14),
            new SampleObj(7),
            new SampleObj(14),
            new SampleObj(33),
            new SampleObj(33),
            new SampleObj(2)
        ];

        # order ASCE (from the smallest object's IDs)
        $sortedCollection = Collections::sort($manyObjects, fn (SampleObj $obj1, SampleObj $obj2) => $obj1->getId() < $obj2->getId());

        var_dump($sortedCollection);
        /*
            [
                new SampleObj(1),
                new SampleObj(2),
                new SampleObj(7),
                new SampleObj(14)
            ]
        */

    }
}
```

##### Example of `Collections::orElse` (get collection with objects but replace each one with ID <= 0 to default one) 

```php
class _OrElseExamples
{

    public static function orElse()
    {
        $manyObjects = [
            new SampleObj(0),
            new SampleObj(14),
            new SampleObj(-1),
            new SampleObj(14),
            new SampleObj(-22)
        ];

        # order ASCE (from the smallest object's IDs)
        $collection = Collections::orElse($manyObjects, fn(SampleObj $obj1) => $obj1->getId() <= 0, new SampleObj(100));

        var_dump($collection);
        /* 
            [
                new SampleObj(100),
                new SampleObj(14),
                new SampleObj(100),
                new SampleObj(14),
                new SampleObj(100)
            ] 
        */
    }
}
```

##### Example of Collections::reduce (reduce array of objects info arrayof their a bit modified IDs)

```php
class _ReduceExamples
{

    public static function reduce()
    {
        $manyObjects = ['aaa', 'bb', 'abc', 'bb'];

        $reduced = Collections::reduce($manyObjects, fn ($concatOfStrings, string $str)  => ($concatOfStrings .= $str) ? $concatOfStrings : $concatOfStrings);
        
        var_dump($reduced); //aaabbabcbb

    }
}
```

##### Example of `Collections::groupBy` (group objects by type) 

```php

class _GroupByExamples
{

    public static function group()
    {
        $manyObjects = [
            new SampleObj(1, 13),
            new SampleObj(14, 13),
            new SampleObj(7, 11),
            new SampleObj(14, 13),
            new SampleObj(33, 2),
            new SampleObj(33, 11),
            new SampleObj(2, 1)
        ];

        # normal count
        $groupped = Collections::groupBy(
            $manyObjects,
            fn (SampleObj $obj) => $obj->getType()
        );

        var_dump($groupped);
        /*
            [
                13 => [SampleObj(1, 13), SampleObj(14, 13), SampleObj(14, 13)],
                11 => [SampleObj(7, 11), SampleObj(33, 11)],
                2 => [SampleObj(33, 2)],
                1 => [SampleObj(2, 1)]
            ]
        */

    }

}
```

##### Example of `Collections::contains` and `Collections::search` (search elements in a collection or just check if collection contains element)

```php
class _ContainsSearch
{

    public static function contains()
    {
        $manyObjects = [
            new SampleObj(1, 13),
            new SampleObj(14, 13),
            new SampleObj(7, 11),
            new SampleObj(14, 13),
            new SampleObj(33, 2),
            new SampleObj(33, 11),
            new SampleObj(2, 1)
        ];

        $contains = Collections::contains($manyObjects, fn(SampleObj $obj1) => $obj1->getId() == 14);

        var_dump($contains); // true

    }

}
```

##### Example of `Collections::collect` (predefined collectors)

Examples:
```php
class _CollectorsExamples
{

    public static function generalTest() {

        try {

            $manyObjects = [
                new SampleObj(1, 13),
                new SampleObj(14, 13),
                new SampleObj(7, 11),
                new SampleObj(14, 13),
                new SampleObj(33, 2),
                new SampleObj(33, 11),
                new SampleObj(2, 1)
            ];
            
            # join all IDs into 1 string separated by commas
            $collector = Collector::of(Collector::JOINING, fn (CollectionsSampleObject $obj) => $obj->getId());
            $collected = Collections::collect($manyObjects, $collector);
            va($collected); // '1,14,7,14,33,33,2'
            
            # Join with custom separator - @
            $collector = Collector::of(Collector::JOINING, fn (CollectionsSampleObject $obj) => ['value' => $obj->getId(), 'separator' => '@']);
            $collected = Collections::collect($manyObjects, $collector);
            va($collected); // 1@14@7@14@33@33@2
            
            # sum all elements
            $collector = Collector::of(Collector::SUMMING, fn (CollectionsSampleObject $obj) => $obj->getId());
            $collected = Collections::collect($manyObjects, $collector);
            va($collected);  // 104
            
            # multiply all elements
            $collector = Collector::of(Collector::MULTIPLYING, fn (CollectionsSampleObject $obj) =>  $obj->getId());
            $collected = Collections::collect($manyObjects, $collector);
            va($collected);  // 2 988 216
            
            # put all elements to array
            $collector = Collector::of(Collector::TO_FLAT_ARRAY, fn (CollectionsSampleObject $obj) => $obj->getId());
            $collected = Collections::collect($manyObjects, $collector);
            va($collected);  // [ 1, 14, 7, 14, 33, 33, 2 ]
            
            # return string with all alements wrapped by single quote
            $collector = Collector::of(Collector::TO_CONCAT_OF_STRINGS, fn (CollectionsSampleObject $obj) => $obj->getId());
            $collected = Collections::collect($manyObjects, $collector);
            va($collected);  // '1','14','7','14','33','33','2'
            
            # generate and return associative array of elements
            $collector = Collector::of(Collector::TO_ASSOC_ARRAY, fn (CollectionsSampleObject $obj) => ['key' => $obj->getType(), 'value' => $obj]);
            $collected = Collections::collect($manyObjects, $collector);

            va($collected);
            /*
                [
                    13 => new SampleObj(14, 13),
                    11 => new SampleObj(33, 11),
                    2 => new SampleObj(2, 2)
                    1 => new SampleObj(2, 1)
                ]
            */

        } catch (\Throwable $t) {
            vae($t);
        }


    }

}
```

_____

### Contract for stream methods (non static ones)
Below interface show full contract of all stream methods can be used.

```php
interface CollectionsContextApi
{


    # map elements in the collection to any other elements or just transforrm
    public function map(callable $mapper): self ;
    
    # filter elements in the collection
    public function filter(callable $predicate): self;
    
    # alias to filter
    public function transform(callable $predicate): self;
    
    # remove only elements matching to predicate
    public function reject(callable $predicate): self;
    
    # get the smallest/lowest/youngest element
    public function min(callable $firstIsLowerComparator);
    
    # get the biggest/highest/oldest element
    public function max(callable $firstIsLowerComparator);
    
    # check if collection is empty
    public function isEmpty(): bool;
    
    # check if collection is not empty
    public function isNotEmpty(): bool;
    
    # sort colelction
    public function sort(callable $biSorter): self;
    
    # count elements in the collection
    public function count() : int;
    
    # count elements and group by provided keys
    public function countBy(callable $keyProducer): array;
    
    # reduce collection into 1 element
    public function reduce(callable $reducer);
    
    # get distinct sub-collection
    public function distinct(callable $biPredicate): self;
    
    # check if all elements match to criteria
    public function allMatch(callable $predicate): bool;
    
    # check if any elements match to criteria
    public function anyMatch(callable $predicate): bool;
    
    # check if every element doesnt match to criteria
    public function noneMatch(callable $predicate): bool;
    
    # get sub-collection groupped by required criteria
    public function groupBy(callable $keyProducer): self ;
    
    # get sub-collection with all "not-null elements", every "null-element" replace to default element
    # "null element" is an element which match to predicate
    public function orElse(callable $isNullPredicate, $defaultElement): self;
    
    # get sub-collection with all "not-null elements" or throw an Exception if any "null element" is inside
    public function orElseThrow(callable $isNullPredicate): self;
    
    # check if collection contains element
    public function contains(callable $predicate): bool;
    
    # search element in the collection
    public function search(callable $predicate): self;
    
    # call function on each element from collection (consume collection's elements)
    public function forEach(callable $consumer): void;
    
    # tansform colection to associative array with required keys (keys are produced by keyProducer)
    public function toAssocArray(callable $keyProducer, $strict = true): self;
    
    # get current collection (after all modifications). This is alias for self::getCollection
    public function get(): iterable;
    
    # get current collection (after all modifications)
    public function getCollection(): iterable;
    
    # flat elements [FUTURE
    public function flatMap(callable $flatterFunction): self;
    
    # collect to any required data structure
    public function collect(Collector $collector);
    
    # add element to collection
    public function append($element): self;
    
    # add element to at the beginning of collection
    public function prepend($element): self;
    
    # shuffle collection
    public function shuffle(): self;
    
    # skip x - elements in a collection
    public function skip(int $skipElements): self;
    
    # take only x - elements from collection
    public function limit(int $limit): self ;
    
    # reverse collection
    public function reverse(): self;

}
```

_____

### Contract for static methods
Below interface show full contract of all static methods can be used.

```php
interface CollectionsStaticApi {
    
    # map elements in the collection to any other elements or just transforrm
    public static function map(iterable $source, callable $mapper): array;
    
    # filter elements in the collection
    public static function filter(iterable $source, callable $predicate): array;
    
    # alias to filter
    public static function transform(iterable $source, callable $predicate): array;
        
    # remove only elements matching to predicate
    public static function reject(iterable $source, callable $predicate): array;
    
    # get the smallest/lowest/youngest element
    public static function min(iterable $source, callable $firstIsLowerComparator);
    
    # get the biggest/highest/oldest element
    public static function max(iterable $source, callable $firstIsLowerComparator);
    
    # check if collection is empty
    public static function isEmpty(iterable $source): bool;
    
    # check if collection is not empty
    public static function isNotEmpty(iterable $source): bool;
    
    # sort colelction
    public static function sort(iterable $source, callable $biSorter): array;
    
    # count elements in the collection
    public static function count(iterable $source) : int;
    
    # count elements and group by provided keys
    public static function countBy(iterable $source, callable $keyProducer): array;
    
    # reduce collection into 1 element
    public static function reduce(iterable $source, callable $reducer);
    
    # get distinct sub-collection
    public static function distinct(iterable $source, callable $biPredicate): array;
    
    # check if all elements match to criteria
    public static function allMatch(iterable $source, callable $predicate): bool;
    
    # check if any elements match to criteria
    public static function anyMatch(iterable $source, callable $predicate): bool;
    
    # check if every element doesnt match to criteria
    public static function noneMatch(iterable $source, callable $predicate): bool;
    
    # get sub-collection groupped by required criteria
    public static function groupBy(iterable $source, callable $keyProducer): array;
    
    # get sub-collection with all "not-null elements", every "null-element" replace to default element
    # "null element" is an element which match to predicate
    public static function orElse(iterable $source, callable $isNullPredicate, $defaultElement): array;
    
    # get sub-collection with all "not-null elements" or throw an Exception if any "null element" is inside
    public static function orElseThrow(iterable $source, callable $isNullPredicate): array;
    
    # check if collection contains element
    public static function contains(iterable $source, callable $predicate): bool;
    
    # search element in the collection
    public static function search(iterable $source, callable $predicate): array;
    
    # call function on each element from collection (consume collection's elements)
    public static function forEach(iterable $source, callable $consumer): void;
    
    # tansform colection to associative array with required keys (keys are produced by keyProducer)
    public static function toAssocArray(iterable $source, callable $keyProducer, $strict = true): array;
    
    # flat elements [FUTURE]
    public static function flatMap(iterable $source, callable $flatterFunction): array;
    
    # collect to any required data structure
    public static function collect(iterable $source, Collector $collector);
    
    # add element to collection
    public static function append(iterable $source, $element): array;
    
    # add element to at the beginning of collection
    public function prepend($element): self;
    
    # shuffle collection
    public static function shuffle(iterable $source): array;
    
    # skip x - elements in a collection
    public static function skip(iterable $source, int $skipElements): array;
    
    # take only x - elements from collection
    public static function limit(iterable $source, int $limit): array;
    
    # reverse collection
    public static function reverse(iterable $source): array;

}
```
### Informations
Curently library needs PHP >= 7.4. Soon it will be redesigned to support also older versions of PHP.

Author - Slawomir Hadas
