<?php

namespace App\CsvImporter\Validators;

use Countable;
use App\Exceptions\CsvValidationException;

class Rules
{
    protected $rule;

    protected $message;

    public function __construct($msg)
    {
        $this->message = $msg;
    }

    /**
     * Validate that a required attribute exists.
     * @see https://github.com/illuminate/validation/blob/master/Concerns/ValidatesAttributes.php#L1243
     * @return bool
     */
    public static function required()
    {
        return tap((new self('Value is required')))->setValidationRule(function ($value) {
            if (is_null($value)) {
                return false;
            } elseif (is_string($value) && trim($value) === '') {
                return false;
            } elseif ((is_array($value) || $value instanceof Countable) && count($value) < 1) {
                return false;
            }
            return true;
        });
    }

    /**
     * Always returns true, just lets us put "nullable" in rules.
     * @see https://github.com/illuminate/validation/blob/master/Concerns/ValidatesAttributes.php#L1150
     * @return bool
     */
    public static function nullable()
    {
        return tap((new self('')))->setValidationRule(function ($value) {
            return true;
        });
    }

    /**
     * Validate that an attribute is an integer.
     * @see https://github.com/illuminate/validation/blob/master/Concerns/ValidatesAttributes.php#L994
     * @return bool
     */
    public static function integer()
    {
        return tap((new self('an integer is required')))->setValidationRule(function ($value) {
            return filter_var($value, FILTER_VALIDATE_INT) !== false;
        });
    }

    /**
     * Validate that an attribute is an float.
     * @return bool
     */
    public static function float()
    {
        return tap((new self('a float value is required')))->setValidationRule(function ($value) {
            return filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
        });
    }

    /**
     * @param $allowed
     * @return bool
     */
    public static function checkAllowedStrings($allowed)
    {
        $allowedStrings = implode(', ', $allowed);
        return tap((new self('please enter a value from the following options: ' .$allowedStrings)))->setValidationRule(function ($value) use ($allowed) {
            return in_array((string) $value, $allowed);
        });
    }

    /**
     * @param $value
     * @param $key
     * @return bool
     */
    public function checkValidation($value, $key)
    {
        $rule = $this->rule;
        if (! $rule($value)) {
            throw new CsvValidationException($key, $value, $this->message);
        }
        return true;
    }

    /**
     * @param $validationRule
     */
    public function setValidationRule(\Closure $validationRule)
    {
        $this->rule = $validationRule;
    }
}