<?php
declare(strict_types=1);

namespace Tests\Unit\CollectionsPhp74Arrows;

use Collections\Collections;

use Collections\Exceptions\CollectionsException;
use PHPUnit\Framework\TestCase;



class ReduceTest extends TestCase
{

    /**
     * reduce2
     *
     * @throws CollectionsException
     * @throws \Collections\Exceptions\CollectionsException
     */
    public function testReduce2()
    {

        ############ Example 2 (reduce array of strings to concatenation of all of them) ############


        $manyObjects = ['aaa', 'bb', 'abc', 'bb'];

        $reduced = Collections::reduce($manyObjects, fn ($concatOfStrings, string $str)  => ($concatOfStrings .= $str) ? $concatOfStrings : $concatOfStrings);

        $this->assertEquals($reduced, 'aaabbabcbb');

    }

}