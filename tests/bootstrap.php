<?php
declare(strict_types=1);


/**
 * ara_arrays_equal
 *
 * @param $a - 1 dimension array only !
 * @param $b - 1 dimension array only !
 * @return bool
 */
function arrays_have_same_simle_elements($a, $b): bool {

    $diff = array_diff(array_values($a), array_values($b));
    $diff2 = array_diff(array_values($b), array_values($a));

    return (empty($diff) && empty($diff2));
}

/**
 * simple printer with exit;
 *
 * @param mixed ...$obj
 */
function vae(...$obj)
{
    foreach ($obj as $item) {

        if($item instanceof \App\Interfaces\Repr) {
            repr($item);
            continue;
        }

        va($item);

    }

    exit;
}


/**
 * va
 *
 * Utility function - var_dump alias
 *
 * @param $obj
 * @param bool $exit
 */
function va(...$obj)
{
    echo '<pre>';

    foreach ($obj as $item) {

        if(is_array($item) && !empty($item) && array_values($item)[0] instanceof \App\Interfaces\Repr) {

            foreach ($item as $itemSingle) {
                repr($itemSingle);
            }

        } else {
            var_dump($item);
        }

    }

    echo '<pre>';

}

/**
 * vao
 *
 * var_dump() original object
 *
 * @param mixed ...$obj
 */
function vao(...$obj)
{
    foreach ($obj as $item) {
        var_dump($item);
    }

    exit;
}
