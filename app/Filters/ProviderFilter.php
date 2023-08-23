<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ProviderFilter implements FilterInterface
{
    public function apply(array $data, Request $request)
    {
        // Apply provider filter logic here
        return array_filter($data, function ($user) use ($request) {
            $provider = $request->input('provider');
            return $user['source'] === $provider;
        });
    }
}
