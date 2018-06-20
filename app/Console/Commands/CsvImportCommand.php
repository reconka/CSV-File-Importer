<?php

namespace App\Console\Commands;

use File;
use App\CSVImporter\CSVImporter;
use App\CSVImporter\CSVRow;
use App\CSVImporter\Validators\Rules;
use App\Exceptions\CsvValidationException;
use App\StockItems;
use Illuminate\Console\Command;

class CsvImportCommand extends Command
{
    /**
     * The console command brief description.
     */
    protected $description = 'Import Stock.csv file into the database.';

    /**
     * Supported Mimetypes
     */
    protected $supportedMimeTypes = ['text/plain', 'text/csv'];

    /**
     * The name of the console command.
     */
    protected $signature = 'import:stock {csv=CSV/stock.csv}';

    public function __construct()
    {
        parent::__construct();
        $this->rules = [
            'Product Code' => Rules::required(),
            'Product Name' => Rules::required(),
            'Product Description' => Rules::nullable(),
            'Cost in GBP' => Rules::float(),
            'Stock' => Rules::integer(),
            'Discontinued' => Rules::checkAllowedStrings(['yes','no',''])
        ];
    }

    /**
     * Console command Handler.
     */
    public function handle()
    {
        $path = $this->argument('csv');

        if (! file_exists(base_path($path))) {
            return $this->error('❕ Couldn\'t find the CSV file. Please copy into  root CSV folder');
        }

        if (! in_array(File::mimeType($path), $this->supportedMimeTypes)) {
            return $this->error('❕ This file is not a valid CSV file');
        }

        $this->info('Importing Stock Items from CSV file...');
        $csvFile = new CSVImporter(base_path($path));

        foreach ($csvFile->getAllRows() as $row) {
            $this->importStocks($row);
        }
        $this->info('Successfully imported your CSV file');
    }
    
    /**
     * @param $row
     */
    private function importStocks(CSVRow $row)
    {
        $discontinued = false;
        try {
            $row->validateRow($this->rules);
        } catch (CsvValidationException $exception) {
            $this->error("❕ Failed importing ".$row->getValue('Product Name')." item from CSV, this is not a valid value for ".$exception->fieldName);
            $setNewValue = $this->ask("Please enter new value for ".$exception->fieldName.", ".$exception->isRequired);
            $row->replaceValue($exception->fieldName, $setNewValue);
            $this->importStocks($row);
        }

        if ($row->getValue('Discontinued') == 'yes') {
            $discontinued = true;
        }
        // create a new record or update it if the record exist in the table
        StockItems::updateOrCreate([
            'product_code' => $row->getValue('Product Code')
        ],
        [
            'product_name' => $row->getValue('Product Name'),
            'product_description' => $row->getValue('Product Description'),
            'price' => $row->getValue('Cost in GBP'),
            'stock' => $row->getValue('Stock'),
            'discontinued' => $discontinued,
        ]);
    }
}
