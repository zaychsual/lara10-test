<?php

use Carbon\Carbon;
use App\Models\Order;

if (! function_exists('moneyFormat')) {
    /**
     * moneyFormat
     *
     * @param  mixed $str
     * @return void
     */
    function formatIdr($str) {
        return 'Rp. ' . number_format($str, '0', '', '.');
    }
}

if (! function_exists('createSlug')) {
    /**
     * createSlug
     *
     * @param  mixed $str
     * @return void
     */
    function createSlug($str) {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $str)));
    }
}

if(!function_exists('generateNoInv')) {
    function generateNoInv()
    {
        $maxCode = Order::selectRaw("max(right(no_inv, 5)) as max")
			->whereYear('created_at', date("Y"))
            ->first();

        if($maxCode->max) {
            $nexCode = substr($maxCode->max, -5, 5);
        } else {
            $nexCode = 0;
        }

        $yy = Carbon::now();
        $yy->isoFormat('YY');
        $result = str_pad((int) $nexCode +1, 5, '0', STR_PAD_LEFT);

        return "INV/".$yy->isoFormat('YY')."/".$yy->isoFormat('MM')."/".$yy->isoFormat('DD')."/".$result;
    }
}

if(!function_exists('arraySort')) {
    function arraySort(&$array, $key)
    {
        // Normalize criteria up front so that the comparer finds everything tidy
        $criteria = func_get_args();
        foreach ($criteria as $index => $criterion) {
            $criteria[$index] = is_array($criterion)
                ? array_pad($criterion, 3, null)
                : array($criterion, SORT_ASC, null);
        }

        return function($first, $second) use (&$criteria) {
            foreach ($criteria as $criterion) {
                // How will we compare this round?
                list($column, $sortOrder, $projection) = $criterion;
                $sortOrder = $sortOrder === SORT_DESC ? -1 : 1;

                // If a projection was defined project the values now
                if ($projection) {
                    $lhs = call_user_func($projection, $first[$column]);
                    $rhs = call_user_func($projection, $second[$column]);
                }
                else {
                    $lhs = $first[$column];
                    $rhs = $second[$column];
                }

                // Do the actual comparison; do not return if equal
                if ($lhs < $rhs) {
                    return -1 * $sortOrder;
                }
                else if ($lhs > $rhs) {
                    return 1 * $sortOrder;
                }
            }

            return 0; // tiebreakers exhausted, so $first == $second
        };
    }
}
