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
