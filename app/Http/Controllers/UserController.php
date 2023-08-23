<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Get a list of JSON files in the data directory
            $jsonFiles = Storage::disk('local')->files('json_data');

            // The combined data
            $combinedData = [];

            foreach ($jsonFiles as $jsonFile) {
                $source = pathinfo($jsonFile, PATHINFO_FILENAME);

                $jsonData = $this->standardizeJson($source, json_decode(Storage::disk('local')->get($jsonFile), true));

                foreach ($jsonData as $user) {
                    $user['source'] = $source;
                    $combinedData[] = $user;
                }
            }

            // Apply filters
            if ($request->has('provider')) {
                $combinedData = array_filter($combinedData, function ($user) use ($request) {
                    $provider = $request->input('provider');
                    return $user['source'] === $provider;
                });
            }

            if ($request->has('statusCode')) {
                $statusCode = $this->getStatusCode($request->input('statusCode'));
                $combinedData = array_filter($combinedData, function ($user) use ($statusCode) {
                    return $user['status'] === $statusCode;
                });
            }

            if ($request->has('balanceMin')) {
                $combinedData = array_filter($combinedData, function ($user) use ($request) {
                    return $user['balance'] >= $request->input('balanceMin');
                });
            }

            if ($request->has('balanceMax')) {
                $combinedData = array_filter($combinedData, function ($user) use ($request) {
                    return $user['balance'] <= $request->input('balanceMax');
                });
            }

            if ($request->has('currency')) {
                $currency = $request->input('currency');
                $combinedData = array_filter($combinedData, function ($user) use ($currency) {
                    return strcasecmp($user['currency'], $currency) === 0;
                });
            }
            return response()->json(array_values($combinedData), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
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

    private function standardizeJson($fileName, $data)
    {
        $mapping = config("keys_mapping.$fileName", []);

        // Initialize an array to store the standardized data.
        $standardizedData = [];

        // Define a mapping function to transform each item
        $mapFunction = function ($item) use ($mapping) {
            $standardizedItem = [];

            // Map keys based on the configuration
            array_walk($item, function ($value, $sourceKey) use ($mapping, &$standardizedItem) {
                if ($sourceKey === 'status' && isset($mapping['status_mapping'][$value])) {
                    $standardizedItem[$mapping['status']] = $mapping['status_mapping'][$value];
                } elseif (isset($mapping[$sourceKey])) {
                    $standardizedItem[$mapping[$sourceKey]] = $value;
                }
            });

            return $standardizedItem;
        };

        // Use array_map to apply the mapping function to each item in $data
        $standardizedData = array_map($mapFunction, $data);

        return $standardizedData;
    }
}
