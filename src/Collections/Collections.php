<?php
declare(strict_types=1);

namespace Collections;


use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;
use Collections\Exceptions\CollectionsInvalidInputException;
use Collections\Exceptions\CollectionsNotImplementedException;
use Collections\Exceptions\CollectionsNullObjectException;
use Collections\Interfaces\CollectionsStaticApi;

/**
 * Class Collections
 * @package App\Components\Utils
 */
class Collections extends CollectionsUtils implements CollectionsStaticApi
{

    /**
     * map
     *
     * map collection of items to array of other/modified items
     *
     * @param iterable $source
     * @param callable $mapper - mapper takes object, transform/map to any other object and return
     *      example:
     *      function (int $obj1) { return $obj1 + 100;};
     *      function (string $obj1) { return $obj1 . '-SUFIX';};
     *
     * @return array
     * @throws CollectionsException
     * @see CollectionsExamples::map()
     */
    public static function map(iterable $source, callable $mapper): array
    {
        $_src = self::sourceAsArray($source);

        $result = [];

        foreach ($_src AS $k => $el) {
            $result[$k] = $mapper($el);
        }

        return $result;

    }

    /**
     * transform
     *
     * This is jsut an alias to Collections::map()
     *
     * @param iterable $source
     * @param callable $mapper
     * @return array
     * @throws CollectionsException
     * @see Collections::map()
     */
    public static function transform(iterable $source, callable $mapper): array
    {
        return Collections::map($source, $mapper);
    }

    /**
     * filter
     *
     * Filter collection and leave only matching elements
     *
     * @param iterable $source
     * @param callable $predicate - predicate must return TRUE if we want to keep element of FALSE if remove
     *      example:
     *      function (int $obj1) { return obj1 < 11 > ? true : false;};
     *      function (string $obj1) { return strpos($obj1, 'vulgar-word') === false ? true : false;};
     *
     * @return array
     * @throws CollectionsException
     * @see CollectionsExamples::filter()
     */
    public static function filter(iterable $source, callable $predicate): array
    {

        $_src = self::sourceAsArray($source);

        $result = [];

        foreach ($_src AS $k => $el) {
            if ($predicate($el)) {
                $result[$k] = $el;
            }
        }

        return $result;

    }

    /**
     * reject
     *
     * Remove elements matching to predicate
     *
     * @param iterable $source
     * @param callable $predicate
     * @return array
     * @throws CollectionsException
     * @see Collections::filter - oposite method
     */
    public static function reject(iterable $source, callable $predicate): array
    {
        $_src = self::sourceAsArray($source);

        $result = [];

        foreach ($_src AS $k => $el) {
            if (!$predicate($el)) {
                $result[$k] = $el;
            }
        }

        return $result;

    }

    /**
     * min
     *
     * Get the lowest/smallest/youngest element from any collection (e.g. from array)
     *
     * @param iterable $source - elements e.g. array of objects, strings, numbers or \Traversable
     * @param callable $firstIsLowerComparator - comparator is a function which should compare 2 objects from $source
     * and return TRUE if first one is lower/smaller/younger than the second one in terms of any criteria
     *      example:
     *      function ($obj1, $obj2) { return obj1 < obj2 ? true : false;};
     *      function (string $obj1, string $obj2) { return obj1 < obj2 ? true : false;};
     *      function (array $obj1, array $obj2) { return count(obj1) < count(obj2) ? true : false;};
     *      function (int $obj1, int $obj2) { return obj1 < obj2 ? true : false;};
     *
     * @return mixed
     * @throws CollectionsException
     * @see Collections::max() - analogical method if you need to take max element
     * @see CollectionsExamples::minMax()
     */
    public static function min(iterable $source, callable $firstIsLowerComparator)
    {

        $_src = self::sourceAsArray($source);

        $initialized = false;

        return array_reduce($_src, function ($reduced, $element) use (&$initialized, $firstIsLowerComparator) {

            if (!$initialized) {
                $reduced = $element;
                $initialized = true;
            }

            return $firstIsLowerComparator($reduced, $element) ? $reduced : $element;

        });
    }

    /**
     * max
     *
     * Get the highest/biggest/oldest element from any collection (e.g. from array)
     *
     * @param iterable $source - elements e.g. array of objects, strings, numbers or \Traversable
     * @param callable $firstIsLowerComparator - exactly like in self::min() method - comparator is a function which should
     * compare 2 objects from $source and return TRUE if first one is lower/smaller/younger than the second one
     * in terms of any criteria
     * example:
     *      function ($obj1, $obj2) { return obj1 < obj2 ? true : false;};
     *      function (string $obj1, string $obj2) { return obj1 < obj2 ? true : false;};
     *      function (array $obj1, array $obj2) { return count(obj1) < count(obj2) ? true : false;};
     *      function (int $obj1, int $obj2) { return obj1 < obj2 ? true : false;};
     * @return mixed
     * @throws CollectionsException
     * @see Collections::min() - analogical method if you need to take min element,
     * @see CollectionsExamples::minMax()
     */
    public static function max(iterable $source, callable $firstIsLowerComparator)
    {
        /**
         * Reverse self::min() comparator (true -> false, false -> true)
         * to reverse order of items
         *
         * @param $obj1
         * @param $obj2
         * @return bool
         */
        $comparatorNegative = function ($obj1, $obj2) use ($firstIsLowerComparator) {
            return !$firstIsLowerComparator($obj1, $obj2);
        };

        return self::min($source, $comparatorNegative);
    }

    /**
     * count
     *
     * Cunt elements in a collection
     *
     * @param iterable $source
     * @return int
     * @throws CollectionsException
     */
    public static function count(iterable $source): int
    {
        $_src = self::sourceAsArray($source);

        return count($_src);
    }

    /**
     * isEmpty
     *
     * @param iterable $source
     * @return bool
     * @throws CollectionsException
     */
    public static function isEmpty(iterable $source): bool
    {
        return !empty(self::sourceAsArray($source));
    }

    /**
     * isNotEmpty
     *
     * @param iterable $source
     * @return bool
     * @throws CollectionsException
     */
    public static function isNotEmpty(iterable $source): bool
    {
        return empty(self::sourceAsArray($source));
    }

    /**
     * countBy
     *
     * @param iterable $source
     * @param callable $keyProducer
     * @return array
     * @throws CollectionsException
     */
    public static function countBy(iterable $source, callable $keyProducer): array
    {

        # count with groupping
        $groupped = Collections::groupBy(
            $source,
            function (CollectionsSampleObject $obj) {
                return $obj->getType();
            }
        );

        return self::map($groupped, function (array $obj) {
            return count($obj);
        });

    }

    /**
     * distinct
     *
     * leave only unique elements
     * (uniqunes is defined in predicate)
     *
     * @param iterable $source
     * @param callable $biPredicate - bipredicate should return TRUE if $obj1 is equal $obj2
     * or FALSE if we consider $obj1 and $obj2 as unequal. This is in terms of any criteria we want to apply.
     *      example:
     *      function (string $obj1, string $obj2) { return obj1 == obj2;};
     *      function (int $obj1, int $obj2) { return obj1 == obj2;};
     *
     * @return array
     * @throws CollectionsException
     */
    public static function distinct(iterable $source, callable $biPredicate): array
    {

        $_src = self::sourceAsArray($source);

        $res = [];

        if (!empty($_src)) {
            # add first element
            $res = array_slice($_src, 0, 1);
        }

        foreach ($_src AS $k => $item) {

            $potentialyUnique = false;

            foreach ($res AS $vres) {

                if ($biPredicate($item, $vres)) {
                    $potentialyUnique = false;
                    break;
                }

                $potentialyUnique = true;

            }

            if ($potentialyUnique) {
                $res[$k] = $item;
            }
        }

        return $res;
    }

    /**
     * allMatch
     *
     * All elements from our collection match to criteria
     *
     * @param iterable $source
     * @param callable $predicate
     * @return bool
     * @throws CollectionsException
     *
     * @see Collections::noneMatch()
     * @see Collections::anyMatch()
     * @see Collections::allMatch()
     *
     * @see CollectionsExamples::allMatch()
     */
    public static function allMatch(iterable $source, callable $predicate): bool
    {

        $_src = self::sourceAsArray($source);

        foreach ($_src as $item) {
            if (!$predicate($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * anyMatch
     *
     * check if any element from collection match to
     * predicate and meet required criteria
     *
     * @param iterable $source
     * @param callable $predicate - predicate method should return TRUE if we consider element
     * as matching to our criteria or FALSE if element doesnt match
     *      example:
     *      function ($obj1) { return obj1->getId() > 0; };
     *      function (string $obj1) { return $obj1 == 'my_string';};
     *      function (array $obj1) { return count(obj1) > 0;};
     * @return bool
     * @throws CollectionsException
     *
     * @see Collections::noneMatch()
     * @see Collections::anyMatch()
     * @see Collections::allMatch()
     *
     * @see CollectionsExamples::anyMatch()
     */
    public static function anyMatch(iterable $source, callable $predicate): bool
    {

        $_src = self::sourceAsArray($source);

        foreach ($_src as $item) {
            if ($predicate($item)) {
                return true;
            }
        }

        return false;

    }

    /**
     * noneMatch
     *
     * No one from our collection's elements match to criteria
     *
     * @param iterable $source
     * @param callable $predicate - predicate method should return TRUE if we consider element
     * as matching to our criteria or FALSE if element doesnt match
     *      example:
     *      function ($obj1) { return obj1->getId() > 0; };
     *      function (string $obj1) { return $obj1 == 'my_string';};
     *      function (array $obj1) { return count(obj1) > 0;};
     * @return bool
     * @throws CollectionsException
     *
     * @see Collections::noneMatch()
     * @see Collections::anyMatch()
     * @see Collections::allMatch()
     *
     * @see CollectionsExamples::noneMatch()
     *
     */
    public static function noneMatch(iterable $source, callable $predicate): bool
    {
        /**
         * No one match = !(any element match)
         */
        return !self::anyMatch($source, $predicate);
    }

    /**
     * sort
     *
     * sort collections using provided predicate
     * (rules how do we want to compare objects)
     *
     * @param iterable $source
     * @param callable $biSorter - sorter should return TRUE if $obj1 is < $obj2,
     * so if we expect order [$obj1, $obj2] or oposite - FALSE - if we
     * expect order [$obj1, $obj2] this is in terms of any criteria we want to apply
     *      example:
     *      function (string $obj1, string $obj2) { return strcmp($obj1, $obj2) < 0;};
     *      function (int $obj1, int $obj2) { return $obj1 < $obj2;};
     * @return array
     * @throws CollectionsException
     */
    public static function sort(iterable $source, callable $biSorter): array
    {
        $_src = self::sourceAsArray($source);

        usort($_src, function ($obj1, $obj2) use ($biSorter) {
            return !$biSorter($obj1, $obj2);
        });

        return $_src;
    }

    /**
     * orElse
     *
     * get collection of object but if anyone from them is NULL (in terms of our predicate)
     * then replace that particular object to default one provided in parameter $defaultElement
     *
     * @param iterable $source
     * @param callable $isNullPredicate
     * @param $defaultElement
     * @return array
     * @throws CollectionsException
     */
    public static function orElse(iterable $source, callable $isNullPredicate, $defaultElement): array
    {
        $_src = self::sourceAsArray($source);

        return Collections::map($_src, function ($obj) use ($isNullPredicate, $defaultElement) {

            if ($isNullPredicate($obj)) {
                # object is considered as NULL in terms of our rules
                return $defaultElement;
            }

            return $obj;
        });

    }

    /**
     * orElseThrow
     *
     * get collection of object but if anyone from them is NULL (in terms of our predicate)
     * then throw an Exception CollectionsException("NULL_OBJECT_IN_ORELSE_THROW");
     *
     * @param iterable $source
     * @param callable $isNullPredicate
     * @return array
     * @throws CollectionsException
     */
    public static function orElseThrow(iterable $source, callable $isNullPredicate): array
    {
        $_src = self::sourceAsArray($source);

        return Collections::map($_src, function ($obj) use ($isNullPredicate) {

            if ($isNullPredicate($obj)) {
                # object is considered as NULL in terms of our rules
                throw new CollectionsNullObjectException("NULL_OBJECT_IN_ORELSE_THROW");
            }

            return $obj;
        });
    }

    /**
     * reduce
     *
     * reduce collection of elements into 1 element
     * (it can be e.g. array of elements or just single, final element)
     *
     * @param iterable $source
     * @param callable $reducer
     * @return mixed
     * @throws CollectionsException
     */
    public static function reduce(iterable $source, callable $reducer)
    {
        $_src = self::sourceAsArray($source);
        return array_reduce($_src, $reducer);
    }

    /**
     * groupBy
     *
     * @param iterable $source
     * @param callable $keyProducer
     * @return array
     * @throws CollectionsInvalidInputException
     */
    public static function groupBy(iterable $source, callable $keyProducer): array
    {

        $groupped = [];

        foreach ($source as $manyObject) {

            $key = $keyProducer($manyObject);

            if (!(is_int($key) || is_string($key))) {
                throw new CollectionsInvalidInputException(
                    "KeyProducer function must generate string or integer to be array's key"
                );
            }

            if (!isset($groupped[$key])) {
                $groupped[$key] = [];
            }

            $groupped[$key][] = $manyObject;

        }

        return $groupped;

    }

    /**
     * contains
     *
     * check if collection contains element
     * (in terms of our predicate - function define how do we compare elements)
     *
     * @param iterable $source
     * @param callable $predicate
     * @return bool
     * @throws CollectionsException
     */
    public static function contains(iterable $source, callable $predicate): bool
    {
        $_src = self::sourceAsArray($source);

        foreach ($_src AS $k => $item) {

            if ($predicate($item)) {
                return true;
            }

        }

        return false;

    }

    /**
     * search
     *
     * find all elements matching  to predicate
     *
     * @param iterable $source
     * @param callable $predicate
     * @return array
     * @throws CollectionsException
     */
    public static function search(iterable $source, callable $predicate): array
    {
        $_src = self::sourceAsArray($source);

        $matchingElements = [];

        foreach ($_src AS $k => $item) {

            if ($predicate($item)) {
                $matchingElements[] = $item;
            }

        }

        return $matchingElements;

    }

    /**
     * forEach
     *
     * consume elements
     *
     * @param iterable $source
     * @param callable $consumer
     * @throws CollectionsException
     */
    public static function forEach(iterable $source, callable $consumer): void
    {
        $_src = self::sourceAsArray($source);

        foreach ($_src AS $k => $item) {
            $consumer($item);
        }

    }

    /**
     * toAssocArray
     *
     * @param iterable $source
     * @param callable $keyProducer
     * @param bool $strict
     * @return array
     * @throws CollectionsException
     */
    public static function toAssocArray(iterable $source, callable $keyProducer, $strict = true): array
    {
        $_src = self::sourceAsArray($source);

        $res = [];

        if (self::isAssocArray($source))
            return (array)$source;

        foreach ($_src AS $k => $item) {

            if (isset($res[$keyProducer($item)]) && $strict) {
                throw new CollectionsInvalidInputException(
                    "Not unique key in Collections::toAssocArray. 
                    Strict mode require every key to be unique. 
                    Please verify your keyProducer."
                );
            }

            $res[$keyProducer($item)] = $item;

        }

        return $res;
    }

    /**
     * flatMap
     *
     * TODO
     *
     * @param iterable $source
     * @param callable $flatterFunction
     * @return array
     * @throws CollectionsNotImplementedException
     */
    public static function flatMap(iterable $source, callable $flatterFunction): array
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * collect
     *
     * @param iterable $source
     * @param Collector $collector
     * @return null |null
     * @throws CollectionsException
     */
    public static function collect(iterable $source, Collector $collector)
    {
        return $collector->collect($source);
    }

    /**
     * append
     *
     * @param iterable $source
     * @param $element
     * @return array
     * @throws CollectionsException
     */
    public static function append(iterable $source, $element): array
    {
        $_src = self::sourceAsArray($source);

        array_push($_src, $element);

        return $_src;
    }

    /**
     * prepend
     *
     * @param iterable $source
     * @param $element
     * @return array
     * @throws CollectionsException
     */
    public static function prepend(iterable $source, $element): array
    {
        $_src = self::sourceAsArray($source);

        array_unshift($_src, $element);

        return $_src;

    }

    /**
     * shuffle
     *
     * @param iterable $source
     * @return array
     * @throws CollectionsException
     */
    public static function shuffle(iterable $source): array
    {
        $_src = self::sourceAsArray($source);

        shuffle($_src);

        return $_src;
    }

    /**
     * skip
     *
     * @param iterable $source
     * @param int $skipElements
     * @return array
     * @throws CollectionsException
     */
    public static function skip(iterable $source, int $skipElements): array
    {
        $_src = self::sourceAsArray($source);

        return array_slice ($_src, $skipElements);

    }

    /**
     * limit
     *
     * @param iterable $source
     * @param int $limit
     * @return array
     * @throws CollectionsException
     */
    public static function limit(iterable $source, int $limit): array
    {
        $_src = self::sourceAsArray($source);

        return array_slice ($_src, 0, $limit);

    }

    /**
     * reverse
     *
     * @param iterable $source
     * @return array
     * @throws CollectionsException
     */
    public static function reverse(iterable $source): array
    {
        $_src = self::sourceAsArray($source);

        return array_reverse($_src);

    }

}
