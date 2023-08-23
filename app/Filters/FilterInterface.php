<?php

namespace App\Filters;

use Illuminate\Http\Request;

interface FilterInterface
{
    public function apply(array $data, Request $request);
}
