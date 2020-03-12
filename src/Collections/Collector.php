<?php
declare(strict_types=1);

namespace Collections;

use Collections\Examples\CollectionsSampleObject;
use Collections\Exceptions\CollectionsException;
use Collections\Exceptions\CollectionsInvalidInputException;

/**
 * Class Collector
 * @package Collections
 *
 * Possible collectors:
 *
 * @see Collector::JOINING
 * @see Collector::SUMMING
 * @see Collector::MULTIPLYING
 * @see Collector::TO_FLAT_ARRAY
 * @see Collector::TO_CONCAT_OF_STRINGS
 * @see Collector::TO_ASSOC_ARRAY
 *
 * Usage:
 *  Collector::of(Collector::JOINING, fn($obj) => $obj);
 *  Collector::of(Collector::SUMMING, fn($obj) => $obj->getId());
 *  Collector::of(Collector::TO_LIST_OF_STRINGS, fn($obj) => $obj);
 */
class Collector extends CollectorOperations
{
    /**
     * [a, b, c] -> a,b,c
     */
    const JOINING = 1;

    /**
     * [a, b, c] -> a + b + c
     */
    const SUMMING = 2;

    /**
     * [a, b, c] -> a * b * c
     */
    const MULTIPLYING = 3;

    /**
     * [obj1, obj2, obj3] -> [
     *      obj1->getId(),
     *      obj2->getId(),
     *      obj3->getId()
     * ]
     */
    const TO_FLAT_ARRAY = 4;

    /**
     * [obj1, obj2, obj3] -> [
     *      '{obj1->getId()}',
     *      '{obj2->getId()}',
     *      '{obj3->getId()}'
     * ]
     */
    const TO_CONCAT_OF_STRINGS = 5;

    /**
     * [obj1, obj2, obj3] -> [
     *      obj1->getId() => obj1,
     *      obj2->getId() => obj2,
     *      obj3->getId() => obj3
     * ]
     *
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
    const TO_ASSOC_ARRAY = 6;

    /**
     * @var int
     */
    private int $type;

    /**
     * @var callable $extractor
     */
    private $extractor;

    /**
     * __construct
     *
     * Collector constructor.
     * @param int $type
     * @param callable $extractor
     */
    private function __construct(int $type, callable $extractor)
    {
        $this->type = $type;
        $this->extractor = $extractor;
    }

    /**
     *
     * @param int $type - one of above consts (JOINING, SUMMING, etc.)
     * @param callable $extractor - what do we want to extract from object and use
     * for example function($obj) { $obj->getId() } or fn ($obj) => $obj->getId();
     *
     * @return Collector
     * @throws CollectionsInvalidInputException
     */
    public static function of(int $type, callable $extractor): Collector
    {

        if (!in_array($type, [
            self::JOINING,
            self::SUMMING,
            self::TO_FLAT_ARRAY,
            self::TO_CONCAT_OF_STRINGS,
            self::TO_ASSOC_ARRAY,
            self::MULTIPLYING
        ])) {
            throw new CollectionsInvalidInputException(
                'Collector::of can be used only with predefined collectors. See class constants.'
            );
        }

        return new self($type, $extractor);

    }

    /**
     * @param $source
     * @return null
     * @throws CollectionsException
     */
    public function collect($source)
    {

        $source = CollectionsUtils::sourceAsArray($source);

        if (empty($source)) {
            return $source;
        }

        $acumulated = self::UNINIT;


        if ($this->getType() != self::TO_ASSOC_ARRAY && $this->getType() != self::JOINING) {
            $extracted = array_map($this->extractor, $source);
        } else if ($this->getType() == self::TO_ASSOC_ARRAY) {

            # check if extractor is valid

            $extractedTest = call_user_func($this->extractor, array_values($source)[0]);

            if (!(is_array($extractedTest) && array_key_exists('key', $extractedTest) && array_key_exists('value', $extractedTest))) {
                throw new CollectionsInvalidInputException(
                    'Invalid collector extractor. If you use Collector::TO_ASSOC_ARRAY then your extractor must 
                    return array like [key => _how_to_obtain_key_, value => _how_to_obtain_value_ ]'
                );
            }

            $extracted = $source;

        } else if ($this->getType() == self::JOINING) {

            # check if extractor is valid

            $extractedTest = call_user_func($this->extractor, array_values($source)[0]);

            $separator = null;

            if (!(is_array($extractedTest) && array_key_exists('separator', $extractedTest)
                && array_key_exists('value', $extractedTest))) {
                $extracted = array_map($this->extractor, $source);
                $customExtractor = null;
            } else {
                $separator = $extractedTest['separator'];
                $customExtractor = function ($el) {
                    $extr = call_user_func($this->extractor, $el);
                    return $extr['value'];
                };
                $extracted = $source;
            }

        }

        switch ($this->getType()) {

            case self::JOINING:

                /**
                 * @see Collector::joining()
                 */
                array_walk($extracted, function ($el) use (&$acumulated, $separator, $customExtractor) {

                    if (!empty($customExtractor)) {
                        $el = $customExtractor($el);
                    }

                    if (is_null($separator))
                        self::joining($acumulated, $el);
                    else
                        self::joining($acumulated, $el, $separator);
                });
                break;

            case self::SUMMING:
                /**
                 * @see Collector::summing()
                 */
                array_walk($extracted, function ($el) use (&$acumulated) {
                    self::summing($acumulated, $el);
                });
                break;

            case self::MULTIPLYING:
                /**
                 * @see Collector::multiplying()
                 */
                array_walk($extracted, function ($el) use (&$acumulated) {
                    self::multiplying($acumulated, $el);
                });
                break;

            case self::TO_FLAT_ARRAY:
                /**
                 * @see Collector::toArray()
                 */
                array_walk($extracted, function ($el) use (&$acumulated) {
                    self::toArray($acumulated, $el);
                });
                break;

            case self::TO_CONCAT_OF_STRINGS:
                /**
                 * @see Collector::toListOfStrings()
                 */
                array_walk($extracted, function ($el) use (&$acumulated) {
                    self::toListOfStrings($acumulated, $el);
                });
                $acumulated = implode(',', $acumulated);
                break;


            case self::TO_ASSOC_ARRAY:

                $extractor = $this->extractor;

                $keyProducer = function ($el) use ($extractor) {
                    $extr = call_user_func($extractor, $el);
                    return $extr['key'];
                };

                $valueProducer = function ($el) use ($extractor) {
                    $extr = call_user_func($this->extractor, $el);
                    return $extr['value'];
                };

                /**
                 * @see Collector::toAssocArray()
                 */
                array_walk($extracted, function ($el) use (&$acumulated, $keyProducer, $valueProducer) {
                    self::toAssocArray($acumulated, $el, $keyProducer, $valueProducer);
                });

                break;

            default:
                throw new CollectionsInvalidInputException(
                    'Collector::type can be used only with predefined collectors. See class constants.'
                );


        }

        if ($acumulated === self::UNINIT) {
            throw new CollectionsInvalidInputException(
                'Collector error. Please make sure your extractor is correct.'
            );
        }

        return $acumulated;

    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return self
     */
    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return callable
     */
    public function getExtractor(): callable
    {
        return $this->extractor;
    }

    /**
     * @param callable $extractor
     * @return self
     */
    public function setExtractor(callable $extractor): self
    {
        $this->extractor = $extractor;
        return $this;
    }

}

