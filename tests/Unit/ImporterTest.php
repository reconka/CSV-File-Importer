<?php

namespace Tests\Unit;

use App\CsvImporter\Validators\Rules;
use App\CsvImporter\CsvImporter;
use App\Exceptions\CsvValidationException;
use Tests\TestCase;

class ImporterTest extends TestCase
{
    /** @test */
    public function compareCsvFieldsAndCheckRowsCount()
    {
        $csv = new CSVImporter(base_path('CSV/stock.csv'));
        $columnNames = [
            'Product Code',
            'Product Name',
            'Product Description',
            'Stock',
            'Cost in GBP',
            'Discontinued',
        ];
        
        $this->assertArraySubset($columnNames, $csv->getAllColumns());
        $this->assertCount(29, $csv->getAllRows());
    }

    /** @test */
    public function testGetRowByIndexWithResult()
    {
        $csvFile = new CSVImporter(base_path('CSV/stock.csv'));
        $stockItem = $csvFile->getRowByIndex(4)->data;

        $this->assertEquals($stockItem['Product Description'], 'Best.console.ever');
        $this->assertEquals($stockItem['Product Code'], 'P0005');
        $this->assertEquals($stockItem['Product Name'], 'XBOX360');
        $this->assertEquals($stockItem['Stock'], '5');
        $this->assertEquals($stockItem['Cost in GBP'], '30.44');
        $this->assertEquals($stockItem['Discontinued'], '');
    }

    /**
     * @expectedException \App\Exceptions\CsvValidationException
     * @test
     */
    public function validationExceptionTest()
    {
        $csv = new CSVImporter(base_path('CSV/stock.csv'));
        $csv->getRowByIndex(6)->validateRow([
            'Product Code' => Rules::required(),
            'Product Name' => Rules::required(),
            'Product Description' => Rules::nullable(),
            'Stock' => Rules::integer(),
            'Cost in GBP' => Rules::float(),
            'Discontinued' => Rules::checkAllowedStrings(['yes', 'no',''])
        ]);

        $this->expectException(CsvValidationException::class);
    }
}
