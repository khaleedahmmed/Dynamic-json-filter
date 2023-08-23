<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Get a list of JSON files in the data directory
        $jsonFiles = Storage::disk('local')->files('json_data');

        // The combined data
        $combinedData = [];

        // Loop through the JSON files
        foreach ($jsonFiles as $jsonFile) {
            // Extract the filename without the extension as the "source"
            $source = pathinfo($jsonFile, PATHINFO_FILENAME);

            // Load data from the JSON file with standard convert
            $jsonData = $this->standardizeJson($source,json_decode(Storage::disk('local')->get($jsonFile), true));

            // Add the "source" to each user's data
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

        // Return the filtered data as JSON
        return response()->json(array_values($combinedData));
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

        foreach ($data as $item) {
            $standardizedItem = [];

            foreach ($mapping as $sourceKey => $destinationKey) {
                if ($sourceKey === 'status' && isset($mapping['status_mapping'][$item['status']])) {
                    $standardizedItem[$destinationKey] = $mapping['status_mapping'][$item['status']];
                } elseif (isset($item[$sourceKey])) {
                    $standardizedItem[$destinationKey] = $item[$sourceKey];
                }
            }

            $standardizedData[] = $standardizedItem;
        }

        return $standardizedData;
    }
}
