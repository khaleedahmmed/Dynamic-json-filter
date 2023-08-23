<?php

namespace App\Filters;

use Illuminate\Http\Request;

class BalanceMaxFilter implements FilterInterface
{
    public function apply(array $data, Request $request)
    {
        if ($request->has('balanceMax')) {
            $maxBalance = (float)$request->input('balanceMax');

            return array_filter($data, function ($user) use ($maxBalance) {
                return $user['balance'] <= $maxBalance;
            });
        }

        return $data;
    }
}
