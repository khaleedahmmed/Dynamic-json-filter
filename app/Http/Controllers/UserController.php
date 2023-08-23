<?php

namespace App\Http\Controllers;

use App\FilterPipeline;
use App\Filters\BalanceMaxFilter;
use App\Filters\BalanceMinFilter;
use App\Filters\CurrencyFilter;
use App\Filters\ProviderFilter;
use App\Filters\StatusCodeFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Get Files
            $combinedData = $this->getCombinedData();

            // Apply filters
            $filteredData = $this->applyFilters($request, $combinedData);

            // Return Response
            return response()->json(array_values($filteredData), 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    private function getCombinedData()
    {
        // The combined data
        $combinedData = [];

        // Get a list of JSON files in the data directory
        $jsonFiles = Storage::disk('local')->files('json_data');

        foreach ($jsonFiles as $jsonFile) {
            $source = pathinfo($jsonFile, PATHINFO_FILENAME);

            $jsonData = $this->standardizeJson($source, json_decode(Storage::disk('local')->get($jsonFile), true));

            foreach ($jsonData as $user) {
                $user['source'] = $source;
                $combinedData[] = $user;
            }
        }

        return $combinedData;
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

    private function applyFilters(Request $request, array $data)
    {
        // Create a filter pipeline
        $filterPipeline = new FilterPipeline();

        if ($request->has('provider')) {
            $filterPipeline->addFilter(new ProviderFilter());
        }

        if ($request->has('statusCode')) {
            $filterPipeline->addFilter(new StatusCodeFilter());
        }

        if ($request->has('balanceMin')) {
            $filterPipeline->addFilter(new BalanceMinFilter());
        }

        if ($request->has('balanceMax')) {
            $filterPipeline->addFilter(new BalanceMaxFilter());
        }

        if ($request->has('currency')) {
            $filterPipeline->addFilter(new CurrencyFilter());
        }

        return $filterPipeline->apply($data, $request);
    }
}
