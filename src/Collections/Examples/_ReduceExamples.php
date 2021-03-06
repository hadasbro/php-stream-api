<?php
declare(strict_types=1);

namespace Collections\Examples;

use Collections\Collections;
use Collections\Exceptions\CollectionsException;

/**
 * Class _ReduceExamples
 * @package Collections\Examples
 */
class _ReduceExamples
{
    /**
     * reduce
     *
     * @throws CollectionsException
     */
    public static function reduce()
    {
        ############ Example 1 (reduce array of objects info arrayof their a bit modified IDs) ############


        $manyObjects = [
            new CollectionsSampleObject(0),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(-1),
            new CollectionsSampleObject(14),
            new CollectionsSampleObject(-22)
        ];

        # reduce array of objects to array if [ID#objects_id, ..., ID#objects_id]
        $reduced = Collections::reduce($manyObjects, function ($ids, CollectionsSampleObject $obj) {
            # reducer is methos as the following
            # function ($reducedElement, $nextElement) { return $reducedElement; }
            # in reducet we should perform any kind of changes on $reducedElement in terms of any next $nextElement
            # e.g. sum/add next integer to already existing sum, add another element to array etc.

            $ids ??= [];
            $ids[] = 'ID#' . $obj->getId();
            return $ids;
        });

        var_dump($reduced);
        #[
        #    "ID#0"
        #    "ID#14"
        #    "ID#-1"
        #    "ID#14"
        #    "ID#-22"
        #]

    }

    /**
     * reduce2
     *
     * @throws CollectionsException
     */
    public static function reduce2()
    {

        ############ Example 2 (reduce array of strings to concatenation of all of them) ############


        $manyObjects = ['aaa', 'bb', 'abc', 'bb'];

        $reduced = Collections::reduce($manyObjects, function ($concatOfStrings, string $str) {
            $concatOfStrings ??= '';
            $concatOfStrings .= $str;
            return $concatOfStrings;
        });

        var_dump($reduced); # aaabbabcbb

    }

    /**
     * reduce3
     *
     * @throws CollectionsException
     */
    public static function reduce3()
    {

        ############ Example 3 (reduce array of numbers to their sum) ############


        $manyObjects = [2, 11.03, -12, 5, 19.2, -19];

        $reduced = Collections::reduce($manyObjects, function ($sum, $el) {
            $sum ??= 0;
            $sum += $el;
            return $sum;
        });

        var_dump($reduced); # float(6.23)
    }

}