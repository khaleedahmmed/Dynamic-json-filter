<?php

namespace App;

use App\Filters\FilterInterface;
use Illuminate\Http\Request;

class FilterPipeline
{
    private $filters = [];

    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;
        return $this;
    }

    public function apply(array $data, Request $request)
    {
        foreach ($this->filters as $filter) {
            $data = $filter->apply($data, $request);
        }

        return $data;
    }
}
