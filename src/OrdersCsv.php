<?php

namespace Sem\FlindersCase;

class OrdersCsv
{
    public string $csvFile;
    public array $csvData;
    public array $csvIssues;
    public array $ordersPerCountry;

    public function __construct(string $file = BASEDIR . '/resources/orders.csv')
    {
        $this->csvFile = $file;
        $this->parseCsv();
    }

    public function parseCsv(bool $headers = true)
    {
        // Create array from csv data
        $csvArray = file($this->csvFile);

        if ($headers) {
            // Remove first row and declare those values headers
            $csvHeaders = str_getcsv(array_shift($csvArray));
        }

        foreach ($csvArray as $csvLine) {
            // Check if number of column headers matches number of values in csv line
            if (count($csvHeaders) === count(str_getcsv($csvLine))) {
                // Use headers and csv line elements as key => values in csv data array
                $this->csvData[] = array_combine($csvHeaders, str_getcsv($csvLine));
            } else {
                // CSV line not parsable ERROR
                $this->csvIssues[] = [
                    'values' => str_getcsv($csvLine),
                    'issue' => 'incorrect number of values'
                ];
            }
        }
    }

    public function listClientsByOrders()
    {
        // Sort csv cata by descending number of orders
        usort($this->csvData, function ($a, $b) {
            return $b['order'] - $a['order'];
        });

        return $this->csvData;
    }

    public function listOrdersPerCountry()
    {
        $countries = [];
        $ordersTotal = 0;

        foreach ($this->csvData as $client) {
            // Check if country was not listed before
            if (!isset($countries[$client['location.country']])) {
                // Add country and order amount of current client
                $countries += [$client['location.country'] => intval($client['order'])];
            } else {
                // Add order amount of current client to already listed country
                $countries[$client['location.country']] += intval($client['order']);
            }
            // Add current client order amount to total amount
            $ordersTotal += $client['order'];
        }

        foreach ($countries as $country => $orders) {
            // Create array of associative arrays with percentage for each country
            $this->ordersPerCountry[] = [
                'country' => $country,
                'orders' => $orders,
                'percentage' => round($orders * 100 / $ordersTotal, 3)
            ];
        }

        // Sort orders per country by amount descending
        usort($this->ordersPerCountry, function ($a, $b) {
            return $b['orders'] - $a['orders'];
        });

        return $this->ordersPerCountry;
    }

    public function print($data)
    {
        print_r(json_encode($data));
    }
}
