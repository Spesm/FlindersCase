<?php

namespace Sem\FlindersCase;

use PHPUnit\Framework\TestCase;

final class OrdersCsvTest extends TestCase
{
    public function testConstructor()
    {
        $obj = new OrdersCsv();

        $this->assertFileExists($obj->csvFile, "Source file not found in given location.");
    }

    public function testParseCsv()
    {
        $obj = new OrdersCsv();

        $this->assertIsArray($obj->csvData, "Failed to create array from csv data.");
        $this->assertNotEmpty($obj->csvData, "Failed to load data into csv data array.");
        $this->assertEmpty($obj->csvIssues, "One or more lines in the csv file have a different number of values than the number of headers.");
    }

    public function testListClientsByOrders()
    {
        $obj = new OrdersCsv();
        $obj->listClientsByOrders();

        foreach ($obj->csvData as $index => $client) {
            $this->assertArrayHasKey('order', $client, "One or more entries misses order key.");
            $this->assertIsNumeric($client['order'], "One or more entries has non-numeric value for order key.");

            $previous = $index > 0 ? $obj->csvData[$index - 1]['order'] : INF;
            $this->assertLessThan($previous, $client['order'], "One or more entries is not sorted by descending order amount.");
        }
    }

    public function testListOrdersPerCountry()
    {
        $obj = new OrdersCsv();
        $obj->listOrdersPerCountry();

        foreach ($obj->ordersPerCountry as $country) {
            $this->assertArrayHasKey('orders', $country, "Orders key has not been set for one or more countries.");
            $this->assertIsNumeric($country['orders'], "One or more countries has non-numeric value for orders key.");
            $this->assertArrayHasKey('percentage', $country, "Percentage key has not been set for one or more countries.");
            $this->assertIsNumeric($country['percentage'], "One or more countries has non-numeric value for percentage key.");
        }

        $cent = round(array_sum(array_column($obj->ordersPerCountry, 'percentage')));
        $this->assertEquals(100, $cent, "Percentages do not add up to 100%.");
    }
}
