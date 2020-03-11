<?php

# PHP 7 version

require_once "vendor/autoload.php";

use Collections\Examples\{
    _AllMatchExamples,
    _AnyMatchExamples,
    _CollectionsExamples,
    _ContainsSearchExamples,
    _CountExamples,
    _DistinctExamples,
    _FilterExamples,
    _ForeachExamples,
    _GroupByExamples,
    _InitConstructExamples,
    _MapExamples,
    _NoneMatchExamples,
    _OrElseExamples,
    _OtherMethodsExamples,
    _ReduceExamples,
    _RejectExamples,
    _SortExamples,
    _StreamExamples,
    _ToArrayAssocExamples
};
use Collections\Exceptions\CollectionsException;
use Collections\Exceptions\CollectionsInvalidInputException;

/**
 * Global error handler
 */
set_error_handler(function (int $severity, string $message, string $file, int $line): void {

    if (!(error_reporting() & $severity)) {
        return;
    }

    throw new ErrorException($message, 0, $severity, $file, $line);

}, E_ERROR | E_WARNING | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR);


if (false) {
    ini_set('display_errors', 'On');
    ini_set('display_startup_errors', 'On');
    error_reporting(E_ALL);
}


try {
    _AllMatchExamples::allMatch(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

echo PHP_EOL;

try {
    _AllMatchExamples::allMatch3(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}


try {
    _AnyMatchExamples::anyMatch2(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _AnyMatchExamples::anyMatch(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _AnyMatchExamples::anyMatch3(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _CollectionsExamples::streamTest(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _ContainsSearchExamples::search(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _ContainsSearchExamples::contains(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _CountExamples::count(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _CountExamples::count2(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _DistinctExamples::distinct(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _DistinctExamples::distinct2(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _FilterExamples::filter(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _FilterExamples::filter2(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _ForeachExamples::sort2(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _GroupByExamples::group(); echo PHP_EOL;
} catch (CollectionsInvalidInputException $e) {
    print_r($e);
}

try {
    _InitConstructExamples::init(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _MapExamples::map(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _MapExamples::map2(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _NoneMatchExamples::noneMatch(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _NoneMatchExamples::noneMatch2(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _NoneMatchExamples::noneMatch3(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _NoneMatchExamples::noneMatch4(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _OrElseExamples::orElse(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _OrElseExamples::orElse2(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _OrElseExamples::orElse3(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _OtherMethodsExamples::test(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _ReduceExamples::reduce(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _ReduceExamples::reduce2(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _ReduceExamples::reduce3(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _RejectExamples::reject(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _SortExamples::sort(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _SortExamples::sort2(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _SortExamples::sort3(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _SortExamples::sort4(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _StreamExamples::stream(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}

try {
    _ToArrayAssocExamples::toAssocArray(); echo PHP_EOL;
} catch (CollectionsException $e) {
    print_r($e);
}