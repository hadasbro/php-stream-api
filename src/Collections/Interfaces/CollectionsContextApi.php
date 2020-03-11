<?php


namespace Collections\Interfaces;


interface CollectionsContextApi
{


    # map elements in the collection to any other elements or just transforrm
    public function map(callable $mapper): self;

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
    public function count(): int;

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
    public function groupBy(callable $keyProducer): self;

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

    # flat elements [TODO]
    public function flatMap(callable $flatterFunction): self;

    # collect to any required data structure [TODO]
    public function collect(callable $collector);

    # add element to collection [TODO]
    public function append($element): self;

    # shuffle collection [TODO]
    public function shuffle(): self;

    # skip x - elements in a collection [TODO]
    public function skip(int $skipElements): self;

    # take only x - elements from collection [TODO]
    public function limit(int $limit): self;

    # reverse collection [TODO]
    public function reverse(): self;

}