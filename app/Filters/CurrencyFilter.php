<?php

namespace App\Filters;

use Illuminate\Http\Request;

class CurrencyFilter implements FilterInterface
{
    public function apply(array $data, Request $request)
    {
        if ($request->has('currency')) {
            $currency = $request->input('currency');

            return array_filter($data, function ($user) use ($currency) {
                return strcasecmp($user['currency'], $currency) === 0;
            });
        }

        return $data;
    }
}
