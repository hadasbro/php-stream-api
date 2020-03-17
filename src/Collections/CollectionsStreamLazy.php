<?php
/** @noinspection PhpUnusedAliasInspection | PhpUnusedFunctionInspection | PhpUnusedPrivateFieldInspection | PhpUnusedPrivateMethodInspection */

declare(strict_types=1);

namespace Collections;


use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsInvalidInputException;
use Collections\Exceptions\CollectionsNotImplementedException;
use Collections\Interfaces\CollectionsContextApi;

/**
 *
 * CollectionsStreamLazy
 *
 * TODO
 *
 * Class CollectionsStream
 * @package Collections
 */
class CollectionsStreamLazy extends CollectionsUtils implements CollectionsContextApi
{

    /**
     * @var iterable
     */
    public iterable $collection;

    /**
     * lazyMode (default false)
     *
     * @var bool
     */
    public bool $lazyMode = false;

    /**
     * @var string - name of method which terminated stream
     */
    public string $terminatedBy = '';

    /**
     * CollectionsStream constructor.
     *
     * __constructor__
     * @param iterable $collection
     * @param bool $lazyMode
     * @throws CollectionsInvalidInputException
     */
    public function __construct(iterable $collection, $lazyMode = false)
    {

        if (!CollectionsUtils::_isIterable($collection)) {
            throw new CollectionsInvalidInputException(
                "Invalid input for collection stream. Stream require iterable input."
            );
        }

        $this->lazyMode = $lazyMode;
        $this->collection = $collection;
    }

    /**
     * fromIterable
     *
     * __constructor__
     *
     * @param iterable $collection
     * @param bool $lazyMode
     * @return static
     * @throws CollectionsInvalidInputException
     */
    public static function fromIterable(iterable $collection, $lazyMode = false) : self
    {
        return new self($collection, $lazyMode);
    }

    /**
     * fromStartValueAndTransformer
     *
     * __constructor__
     *
     * @param $startValue
     * @param int $numberOfElements
     * @param callable $producter
     * @param bool $lazyMode
     * @return static
     * @throws CollectionsInvalidInputException
     */
    public static function fromStartValueAndTransformer($startValue, $numberOfElements, callable $producter, $lazyMode = false) : self
    {
        $data = [$startValue];

        for ($i = 1; $i < $numberOfElements; $i++) {
            $data[$i] = $producter($data[$i - 1]);
        }

        return new self($data, $lazyMode);
    }

    /**
     * fromProducer
     *
     * __constructor__
     *
     * @param callable $producter
     * @param bool $lazyMode
     * @return $this
     * @throws CollectionsInvalidInputException
     */
    public static function fromProducer(callable $producter, $lazyMode = false) {

        $data = $producter();

        if (!CollectionsUtils::_isIterable($data)) {
            throw new CollectionsInvalidInputException(
                "Invalid input for collection stream. Stream require iterable input."
            );
        }

        return new self($data, $lazyMode);
    }

    /**
     * fromParams
     *
     * __constructor__
     *
     * @param mixed ...$params
     * @return static
     * @throws CollectionsInvalidInputException
     */
    public static function fromParams(...$params) : self
    {
        /**
         * Lazy by the default
         * @see CollectionsStream::fromParamsLazy
         * @see CollectionsStream::fromParamsEager()
         */
        return new self($params, true);
    }

    /**
     * fromParamsLazy
     *
     * __constructor__
     *
     * @param mixed ...$params
     * @return static
     * @throws CollectionsInvalidInputException
     * @see CollectionsStream::fromParams
     * @see CollectionsStream::fromParamsLazy
     * @see CollectionsStream::fromParamsEager
     */
    public static function fromParamsLazy(...$params) : self
    {
        return new self($params, true);
    }

    /**
     * fromParamsEager
     *
     * __constructor__
     *
     * @param mixed ...$params
     * @return static
     * @throws CollectionsInvalidInputException
     * @see CollectionsStream::fromParams
     * @see CollectionsStream::fromParamsLazy
     * @see CollectionsStream::fromParamsEager
     */
    public static function fromParamsEager(...$params) : self
    {
        return new self($params, false);
    }

    /**
     * get
     *
     * alias for self::getCollection
     *
     * @return iterable
     * @see CollectionsStream::getCollection()
     */
    public function get(): iterable
    {
        return $this->getCollection();
    }

    /**
     * @return iterable
     */
    public function getCollection(): iterable
    {
        return $this->collection;
    }

    /**
     * @param iterable $collection
     * @return self
     */
    public function setCollection(iterable $collection): self
    {
        $this->collection = $collection;
        return $this;
    }

    /**
     * @return string
     */
    private function getTerminatedBy(): string
    {
        return $this->terminatedBy;
    }

    /**
     * @param string $terminatedBy
     * @return self
     */
    public function setTerminatedBy(string $terminatedBy): self
    {
        $this->terminatedBy = $terminatedBy;
        return $this;
    }

    /**
     * @param callable $mapper
     * @return CollectionsContextApi
     * @throws CollectionsNotImplementedException
     */
    public function map(callable $mapper): CollectionsContextApi
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $predicate
     * @return CollectionsContextApi
     * @throws CollectionsNotImplementedException
     */
    public function filter(callable $predicate): CollectionsContextApi
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $predicate
     * @return CollectionsContextApi
     * @throws CollectionsNotImplementedException
     */
    public function transform(callable $predicate): CollectionsContextApi
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $predicate
     * @return CollectionsContextApi
     * @throws CollectionsNotImplementedException
     */
    public function reject(callable $predicate): CollectionsContextApi
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $firstIsLowerComparator
     * @throws CollectionsNotImplementedException
     */
    public function min(callable $firstIsLowerComparator)
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $firstIsLowerComparator
     * @throws CollectionsNotImplementedException
     */
    public function max(callable $firstIsLowerComparator)
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @return bool
     * @throws CollectionsNotImplementedException
     */
    public function isEmpty(): bool
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @return bool
     * @throws CollectionsNotImplementedException
     */
    public function isNotEmpty(): bool
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $biSorter
     * @return CollectionsContextApi
     * @throws CollectionsNotImplementedException
     */
    public function sort(callable $biSorter): CollectionsContextApi
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @return int
     * @throws CollectionsNotImplementedException
     */
    public function count(): int
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $keyProducer
     * @return array
     * @throws CollectionsNotImplementedException
     */
    public function countBy(callable $keyProducer): array
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $reducer
     * @throws CollectionsNotImplementedException
     */
    public function reduce(callable $reducer)
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $biPredicate
     * @return CollectionsContextApi
     * @throws CollectionsNotImplementedException
     */
    public function distinct(callable $biPredicate): CollectionsContextApi
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $predicate
     * @return bool
     * @throws CollectionsNotImplementedException
     */
    public function allMatch(callable $predicate): bool
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $predicate
     * @return bool
     * @throws CollectionsNotImplementedException
     */
    public function anyMatch(callable $predicate): bool
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $predicate
     * @return bool
     * @throws CollectionsNotImplementedException
     */
    public function noneMatch(callable $predicate): bool
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $keyProducer
     * @return CollectionsContextApi
     * @throws CollectionsNotImplementedException
     */
    public function groupBy(callable $keyProducer): CollectionsContextApi
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $isNullPredicate
     * @param $defaultElement
     * @return CollectionsContextApi
     * @throws CollectionsNotImplementedException
     */
    public function orElse(callable $isNullPredicate, $defaultElement): CollectionsContextApi
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $isNullPredicate
     * @return CollectionsContextApi
     * @throws CollectionsNotImplementedException
     */
    public function orElseThrow(callable $isNullPredicate): CollectionsContextApi
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $predicate
     * @return bool
     * @throws CollectionsNotImplementedException
     */
    public function contains(callable $predicate): bool
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $predicate
     * @return CollectionsContextApi
     * @throws CollectionsNotImplementedException
     */
    public function search(callable $predicate): CollectionsContextApi
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $consumer
     * @throws CollectionsNotImplementedException
     */
    public function forEach(callable $consumer): void
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $keyProducer
     * @param bool $strict
     * @return CollectionsContextApi
     * @throws CollectionsNotImplementedException
     */
    public function toAssocArray(callable $keyProducer, $strict = true): CollectionsContextApi
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param callable $flatterFunction
     * @return CollectionsContextApi
     * @throws CollectionsNotImplementedException
     */
    public function flatMap(callable $flatterFunction): CollectionsContextApi
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param $element
     * @return CollectionsContextApi
     * @throws CollectionsNotImplementedException
     */
    public function append($element): CollectionsContextApi
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @return CollectionsContextApi
     * @throws CollectionsNotImplementedException
     */
    public function shuffle(): CollectionsContextApi
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param int $skipElements
     * @return CollectionsContextApi
     * @throws CollectionsNotImplementedException
     */
    public function skip(int $skipElements): CollectionsContextApi
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param int $limit
     * @return CollectionsContextApi
     * @throws CollectionsNotImplementedException
     */
    public function limit(int $limit): CollectionsContextApi
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @return CollectionsContextApi
     * @throws CollectionsNotImplementedException
     */
    public function reverse(): CollectionsContextApi
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param $element
     * @return CollectionsContextApi
     * @throws CollectionsNotImplementedException
     */
    public function prepend($element): CollectionsContextApi
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }

    /**
     * @param Collector $collector
     * @throws CollectionsNotImplementedException
     */
    public function collect(Collector $collector)
    {
        throw new CollectionsNotImplementedException("Not implemented");
    }
}