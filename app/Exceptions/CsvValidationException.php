<?php

namespace App\Exceptions;

use Exception;

class CsvValidationException extends Exception
{
    public $fieldName;
    public $value;
    public $isRequired;

    public function __construct($fieldName, $value, $isRequired)
    {
        $this->fieldName = $fieldName;
        $this->value = $value;
        $this->isRequired = $isRequired;
        parent::__construct();
    }
}
