<?php


namespace Collections\Interfaces;


use Collections\Collector;

interface CollectionsStaticApi
{

    public static function map(iterable $source, callable $mapper): array;

    public static function transform(iterable $source, callable $mapper): array;

    public static function filter(iterable $source, callable $predicate): array;

    public static function reject(iterable $source, callable $predicate): array;

    public static function min(iterable $source, callable $firstIsLowerComparator);

    public static function max(iterable $source, callable $firstIsLowerComparator);

    public static function count(iterable $source): int;

    public static function isEmpty(iterable $source): bool;

    public static function isNotEmpty(iterable $source): bool;

    public static function countBy(iterable $source, callable $keyProducer): array;

    public static function distinct(iterable $source, callable $biPredicate): array;

    public static function allMatch(iterable $source, callable $predicate): bool;

    public static function anyMatch(iterable $source, callable $predicate): bool;

    public static function noneMatch(iterable $source, callable $predicate): bool;

    public static function sort(iterable $source, callable $biSorter): array;

    public static function orElse(iterable $source, callable $isNullPredicate, $defaultElement): array;

    public static function orElseThrow(iterable $source, callable $isNullPredicate): array;

    public static function reduce(iterable $source, callable $reducer);

    public static function groupBy(iterable $source, callable $keyProducer): array;

    public static function contains(iterable $source, callable $predicate): bool;

    public static function search(iterable $source, callable $predicate): array;

    public static function forEach(iterable $source, callable $consumer): void;

    public static function toAssocArray(iterable $source, callable $keyProducer, $strict = true): array;

    public static function flatMap(iterable $source, callable $flatterFunction): array;

    public static function collect(iterable $source, Collector $collector);

    public static function append(iterable $source, $element): array;

    public static function prepend(iterable $source, $element): array;

    public static function shuffle(iterable $source): array;

    public static function skip(iterable $source, int $skipElements): array;

    public static function limit(iterable $source, int $limit): array;

    public static function reverse(iterable $source): array;

}