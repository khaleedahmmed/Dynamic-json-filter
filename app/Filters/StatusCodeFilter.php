<?php

namespace App\Filters;

use Illuminate\Http\Request;

class StatusCodeFilter implements FilterInterface
{
    public function apply(array $data, Request $request)
    {
        if ($request->has('statusCode')) {
            $statusCode = $this->getStatusCode($request->input('statusCode'));

            return array_filter($data, function ($user) use ($statusCode) {
                return $user['status'] === $statusCode;
            });
        }

        return $data;
    }

    private function getStatusCode($status)
    {
        switch ($status) {
            case 'authorised':
                return 1;
            case 'decline':
                return 2;
            case 'refunded':
                return 3;
            default:
                return null;
        }
    }

}
