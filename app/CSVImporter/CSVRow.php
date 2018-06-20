<?php

namespace App\CSVImporter;

use App\Exceptions\CsvValidationException;
use Illuminate\Support\Collection;

class CSVRow
{
    public $data;

    public function __construct($row, $keys)
    {
        $this->setData($row, $keys);
    }

    /**
     * Validate row with rules
     *
     * @param $rules
     * @throws CsvValidationException
     */
    public function validateRow($rules)
    {
        collect($rules)->each(function ($rule, $key) {
            $rule->checkValidation($this->getValue($key), $key);
        });
    }

    /**
     *
     * @param $row
     * @param $keys
     */
    private function setData($row, $keys)
    {
        $data = collect($keys)
            ->map(function ($attr, $index) use ($row) {
                return [$attr => $row[$index]];
            });

        $this->data = $data->collapse();
    }

    /**
     * Replace a value in the row.
     *
     * @param $field
     * @param $value
     */
    public function replaceValue($field, $value)
    {
        $this->data = $this->data->map(function ($attr, $key) use ($field, $value) {
            if ($key === $field) {
                return $value;
            }
            return $attr;
        });
    }

    /**
     *
     * @param $columnName
     * @return mixed|null
     */
    public function getValue($columnName)
    {
        if ($this->data->has($columnName)) {
            return $this->data->get($columnName);
        }
        return null;
    }
}