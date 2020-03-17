<?php
/** @noinspection PhpUnusedAliasInspection | PhpUnusedFunctionInspection | PhpUnusedPrivateFieldInspection | PhpUnusedPrivateMethodInspection */

declare(strict_types=1);

namespace Collections;


use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsInvalidInputException;
use Collections\Interfaces\CollectionsContextApi;

class CollectionsStream extends CollectionsUtils implements CollectionsContextApi
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

    /**
     * map
     *
     * [ INTERMEDIATE ]
     *
     * @param callable $mapper
     * @return $this
     * @throws Exceptions\CollectionsException
     */
    public function map(callable $mapper): self
    {
        return $this->setCollection(Collections::map($this->getCollection(), $mapper));
    }

    /**
     * filter
     *
     * [ INTERMEDIATE ]
     *
     * @param callable $predicate
     * @return $this
     * @throws Exceptions\CollectionsException
     */
    public function filter(callable $predicate): self
    {
        return $this->setCollection(Collections::filter($this->getCollection(), $predicate));
    }

    /**
     * min
     *
     * [ TERMINAL ]
     *
     * @param callable $firstIsLowerComparator
     * @return int
     * @throws Exceptions\CollectionsException
     */
    public function min(callable $firstIsLowerComparator) : int
    {
        $this->setTerminatedBy(__METHOD__);
        return Collections::min($this->getCollection(), $firstIsLowerComparator);
    }

    /**
     * max
     *
     * [ TERMINAL ]
     *
     * @param callable $firstIsLowerComparator
     * @return int
     * @throws Exceptions\CollectionsException
     */
    public function max(callable $firstIsLowerComparator) :int
    {
        $this->setTerminatedBy(__METHOD__);
        return Collections::max($this->getCollection(), $firstIsLowerComparator);
    }

    /**
     * sort
     *
     * [ INTERMEDIATE ]
     *
     * @param callable $biSorter
     * @return $this
     * @throws Exceptions\CollectionsException
     */
    public function sort(callable $biSorter): self
    {
        return $this->setCollection(Collections::sort($this->getCollection(), $biSorter));
    }

    /**
     * flatMap
     *
     * [ INTERMEDIATE ]
     *
     * @param callable $flatter
     * @return $this
     * @throws Exceptions\CollectionsNotImplementedException
     */
    public function flatMap(callable $flatter) : self
    {
        return $this->setCollection(Collections::flatMap($this->getCollection(), $flatter));
    }

    /**
     * collect
     *
     * [ TERMINAL ]
     *
     * @param Collector $collector
     * @return iterable
     * @throws Exceptions\CollectionsException
     */
    public function collect(Collector $collector) : iterable
    {
        $this->setTerminatedBy(__METHOD__);
        return Collections::collect($this->getCollection(), $collector);
    }

    /**
     * count
     *
     * [ TERMINAL ]
     *
     * @return int
     * @throws Exceptions\CollectionsException
     */
    public function count(): int
    {
        $this->setTerminatedBy(__METHOD__);
        return Collections::count($this->getCollection());
    }

    /**
     * reduce
     *
     * [ TERMINAL ]
     *
     * @param callable $reducer
     * @return mixed
     * @throws Exceptions\CollectionsException
     */
    public function reduce(callable $reducer)
    {
        $this->setTerminatedBy(__METHOD__);
        return Collections::reduce($this->getCollection(), $reducer);
    }

    /**
     * distinct
     *
     * [ INTERMEDIATE ]
     *
     * @param callable $biPredicate
     * @return $this
     * @throws Exceptions\CollectionsException
     */
    public function distinct(callable $biPredicate): self
    {
        return $this->setCollection(Collections::distinct($this->getCollection(), $biPredicate));
    }

    /**
     * allMatch
     *
     * [ TERMINAL ]
     *
     * @param callable $predicate
     * @return bool
     * @throws Exceptions\CollectionsException
     */
    public function allMatch(callable $predicate): bool
    {
        $this->setTerminatedBy(__METHOD__);
        return Collections::allMatch($this->getCollection(), $predicate);
    }

    /**
     * anyMatch
     *
     * [ TERMINAL ]
     *
     * @param callable $predicate
     * @return bool
     * @throws Exceptions\CollectionsException
     */
    public function anyMatch(callable $predicate): bool
    {
        $this->setTerminatedBy(__METHOD__);
        return Collections::anyMatch($this->getCollection(), $predicate);
    }

    /**
     * noneMatch
     *
     * [ TERMINAL ]
     *
     * @param callable $predicate
     * @return bool
     * @throws Exceptions\CollectionsException
     */
    public function noneMatch(callable $predicate): bool
    {
        $this->setTerminatedBy(__METHOD__);
        return Collections::noneMatch($this->getCollection(), $predicate);
    }

    /**
     * groupBy
     *
     * [ INTERMEDIATE ]
     *
     * @param callable $groupper
     * @return $this
     * @throws Exceptions\CollectionsException
     */
    public function groupBy(callable $groupper): self
    {
        return $this->setCollection(Collections::groupBy($this->getCollection(), $groupper));
    }

    /**
     * orElse
     *
     * [ INTERMEDIATE ]
     *
     * @param callable $isNullPredicate
     * @param Object $defaultElement
     * @return $this
     * @throws Exceptions\CollectionsException
     */
    public function orElse(callable $isNullPredicate, $defaultElement): self
    {
        return $this->setCollection(Collections::orElse($this->getCollection(), $isNullPredicate, $defaultElement));
    }

    /**
     * orElseThrow
     *
     * [ INTERMEDIATE ]
     *
     * @param callable $isNullPredicate
     * @return $this
     * @throws Exceptions\CollectionsException
     */
    public function orElseThrow(callable $isNullPredicate): self
    {
        return $this->setCollection(Collections::orElseThrow($this->getCollection(), $isNullPredicate));
    }

    /**
     * forEach
     *
     * [ INTERMEDIATE ]
     *
     * @param callable $consumer
     * @throws Exceptions\CollectionsException
     */
    public function forEach(callable $consumer): void
    {
        Collections::forEach($this->getCollection(), $consumer);
    }

    /**
     * @param callable $mapper
     * @return $this
     * @throws Exceptions\CollectionsException
     */
    public function transform(callable $mapper): self
    {
        return $this->setCollection(Collections::map($this->getCollection(), $mapper));
    }

    /**
     * reject
     *
     * [ INTERMEDIATE ]
     *
     * @param callable $predicate
     * @return $this
     * @throws Exceptions\CollectionsException
     */
    public function reject(callable $predicate): self
    {
        return $this->setCollection(Collections::reject($this->getCollection(), $predicate));
    }

    /**
     * isEmpty
     *
     * [ TERMINAL ]
     *
     * @return bool
     * @throws Exceptions\CollectionsException
     */
    public function isEmpty(): bool
    {
        return Collections::isEmpty($this->getCollection());
    }

    /**
     * isNotEmpty
     *
     * [ TERMINAL ]
     *
     * @return bool
     * @throws Exceptions\CollectionsException
     */
    public function isNotEmpty(): bool
    {
        return Collections::isNotEmpty($this->getCollection());
    }

    /**
     * countBy
     *
     * [ TERMINAL ]
     *
     * @param callable $keyProducer
     * @return array
     * @throws Exceptions\CollectionsException
     */
    public function countBy(callable $keyProducer): array
    {
        return Collections::countBy($this->getCollection(),$keyProducer);
    }


    /**
     * contains
     *
     * [ TERMINAL ]
     *
     * @param callable $predicate
     * @return bool
     * @throws Exceptions\CollectionsException
     */
    public function contains(callable $predicate): bool
    {
        return Collections::contains($this->getCollection(), $predicate);
    }

    /**
     * search
     *
     * [ INTERMEDIATE ]
     *
     * @param callable $predicate
     * @return $this
     * @throws Exceptions\CollectionsException
     */
    public function search(callable $predicate): self
    {
        return $this->setCollection(Collections::search($this->getCollection(), $predicate));
    }

    /**
     * toAssocArray
     *
     * [ INTERMEDIATE ]
     *
     * @param callable $keyProducer
     * @param bool $strict
     * @return $this
     * @throws Exceptions\CollectionsException
     */
    public function toAssocArray(callable $keyProducer, $strict = true): self
    {
        return $this->setCollection(Collections::toAssocArray($this->getCollection(), $keyProducer, $strict));
    }

    /**
     * append
     *
     * [ INTERMEDIATE ]
     *
     * @param $element
     * @return $this
     * @throws Exceptions\CollectionsException
     */
    public function append($element): self
    {
        return $this->setCollection(Collections::append($this->getCollection(), $element));
    }

    /**
     * shuffle
     *
     * [ INTERMEDIATE ]
     *
     * @return $this
     * @throws Exceptions\CollectionsException
     */
    public function shuffle(): self
    {
        return $this->setCollection(Collections::shuffle($this->getCollection()));
    }

    /**
     * skip
     *
     * [ INTERMEDIATE ]
     *
     * @param int $skipElements
     * @return $this
     * @throws Exceptions\CollectionsException
     */
    public function skip(int $skipElements): self
    {
        return $this->setCollection(Collections::skip($this->getCollection(), $skipElements));
    }

    /**
     * limit
     *
     * [ INTERMEDIATE ]
     *
     * @param int $limit
     * @return $this
     * @throws Exceptions\CollectionsException
     */
    public function limit(int $limit): self
    {
        return $this->setCollection(Collections::limit($this->getCollection(), $limit));
    }

    /**
     * reverse
     *
     * [ INTERMEDIATE ]
     *
     * @return $this
     * @throws Exceptions\CollectionsException
     */
    public function reverse(): self
    {
        return $this->setCollection(Collections::reverse($this->getCollection()));
    }

    /**
     * prepend
     *
     * [ INTERMEDIATE ]
     *
     * @param $element
     * @return $this
     * @throws Exceptions\CollectionsException
     */
    public function prepend($element): self
    {
        return $this->setCollection(Collections::prepend($this->getCollection(), $element));
    }
}