<?php

namespace App\CsvImporter;

class CsvImporter
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var file stream
     */
    protected $file;

    /**
     * @var array
     */    
    protected $rows = [];

    /**
     * @var null|array
     */    
    protected $columns;

    public function __construct($filePath)
    {
        $this->path = $filePath;
        $this->file = fopen($this->path, 'r');
        $this->columns = fgetcsv($this->file);
        $this->setRows();
    }

    /**
     * return all the headers of the CSV file
     *
     * @return array
     */
    public function getAllColumns()
    {
        return $this->columns;
    }

    /**
     * Get all the rows
     *
     * @return array
     */
    public function getAllRows()
    {
        return $this->rows;
    }

    /**
     * Get row information by the index
     *
     * @param $index
     * @return null|mixed
     */
    public function getRowByIndex($index)
    {
        if (array_key_exists($index, $this->rows)) {
            return $this->rows[$index];
        } 
        return null;
    }

    /**
     * Sets the rows
     */
    private function setRows()
    {
        while ($row = fgetcsv($this->file)) {
            $this->rows[] = new CSVRow($row, $this->columns);
        }
	}

    public function __destruct()
    {
        fclose($this->file);
    }
}