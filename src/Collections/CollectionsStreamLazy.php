<?php
/** @noinspection PhpUnusedAliasInspection | PhpUnusedFunctionInspection | PhpUnusedPrivateFieldInspection | PhpUnusedPrivateMethodInspection */

declare(strict_types=1);

namespace Collections;


use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsInvalidInputException;
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
     * @return CollectionsStream
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

    public function map(callable $mapper): CollectionsContextApi
    {
        // TODO: Implement map() method.
    }

    public function filter(callable $predicate): CollectionsContextApi
    {
        // TODO: Implement filter() method.
    }

    public function transform(callable $predicate): CollectionsContextApi
    {
        // TODO: Implement transform() method.
    }

    public function reject(callable $predicate): CollectionsContextApi
    {
        // TODO: Implement reject() method.
    }

    public function min(callable $firstIsLowerComparator)
    {
        // TODO: Implement min() method.
    }

    public function max(callable $firstIsLowerComparator)
    {
        // TODO: Implement max() method.
    }

    public function isEmpty(): bool
    {
        // TODO: Implement isEmpty() method.
    }

    public function isNotEmpty(): bool
    {
        // TODO: Implement isNotEmpty() method.
    }

    public function sort(callable $biSorter): CollectionsContextApi
    {
        // TODO: Implement sort() method.
    }

    public function count(): int
    {
        // TODO: Implement count() method.
    }

    public function countBy(callable $keyProducer): array
    {
        // TODO: Implement countBy() method.
    }

    public function reduce(callable $reducer)
    {
        // TODO: Implement reduce() method.
    }

    public function distinct(callable $biPredicate): CollectionsContextApi
    {
        // TODO: Implement distinct() method.
    }

    public function allMatch(callable $predicate): bool
    {
        // TODO: Implement allMatch() method.
    }

    public function anyMatch(callable $predicate): bool
    {
        // TODO: Implement anyMatch() method.
    }

    public function noneMatch(callable $predicate): bool
    {
        // TODO: Implement noneMatch() method.
    }

    public function groupBy(callable $keyProducer): CollectionsContextApi
    {
        // TODO: Implement groupBy() method.
    }

    public function orElse(callable $isNullPredicate, $defaultElement): CollectionsContextApi
    {
        // TODO: Implement orElse() method.
    }

    public function orElseThrow(callable $isNullPredicate): CollectionsContextApi
    {
        // TODO: Implement orElseThrow() method.
    }

    public function contains(callable $predicate): bool
    {
        // TODO: Implement contains() method.
    }

    public function search(callable $predicate): CollectionsContextApi
    {
        // TODO: Implement search() method.
    }

    public function forEach(callable $consumer): void
    {
        // TODO: Implement forEach() method.
    }

    public function toAssocArray(callable $keyProducer, $strict = true): CollectionsContextApi
    {
        // TODO: Implement toAssocArray() method.
    }

    public function flatMap(callable $flatterFunction): CollectionsContextApi
    {
        // TODO: Implement flatMap() method.
    }

    public function collect(callable $collector)
    {
        // TODO: Implement collect() method.
    }

    public function append($element): CollectionsContextApi
    {
        // TODO: Implement append() method.
    }

    public function shuffle(): CollectionsContextApi
    {
        // TODO: Implement shuffle() method.
    }

    public function skip(int $skipElements): CollectionsContextApi
    {
        // TODO: Implement skip() method.
    }

    public function limit(int $limit): CollectionsContextApi
    {
        // TODO: Implement limit() method.
    }

    public function reverse(): CollectionsContextApi
    {
        // TODO: Implement reverse() method.
    }
}