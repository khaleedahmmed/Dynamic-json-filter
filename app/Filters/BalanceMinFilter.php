<?php

namespace App\Filters;

use Illuminate\Http\Request;

class BalanceMinFilter implements FilterInterface
{
    public function apply(array $data, Request $request)
    {
        if ($request->has('balanceMin')) {
            $minBalance = (float)$request->input('balanceMin');

            return array_filter($data, function ($user) use ($minBalance) {
                return $user['balance'] >= $minBalance;
            });
        }

        return $data;
    }
}
