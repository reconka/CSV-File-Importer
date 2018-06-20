<?php

namespace Tests\Unit;

use App\CsvImporter\Validators\Rules;
use App\Exceptions\CsvValidationException;
use Tests\TestCase;

class ValidatorTest extends TestCase
{
    /**
     * @expectedException \App\Exceptions\CsvValidationException
     * @test
     */
    public function requiredRulesTestWithNoData()
    {
        $Rules = Rules::required();

        $Rules->checkValidation('', '');
        $this->expectException(CsvValidationException::class);
    }

    /**
     * @test
     */
    public function requiredRulesTestWithData()
    {
        $Rules = Rules::required();
        $this->assertTrue($Rules->checkValidation('something.', ''));
    }

    /**
     * @test
     */
    public function nullableRulesTestWithNullData()
    {
        $Rules = Rules::nullable();

        $this->assertTrue($Rules->checkValidation(null, ''));
    }

    /**
     * @test
     */
    public function checkAllowedStringsRulesTestWithAllowedString()
    {
        $Rules = Rules::checkAllowedStrings(['allowedString']);

        $this->assertTrue($Rules->checkValidation('allowedString', ''));
    }

    /**
     * @expectedException \App\Exceptions\CsvValidationException
     * @test
     */
    public function checkAllowedStringsRulesTestWithNotAllowedString()
    {
        $Rules = Rules::checkAllowedStrings(['allowedString']);

        $this->assertTrue($Rules->checkValidation('not-allowed', ''));
        $this->expectException(CsvValidationException::class);
    }

    /**
     * @expectedException \App\Exceptions\CsvValidationException
     * @test
     */
    public function integerRulesTestWithString()
    {
        $Rules = Rules::integer();

        $this->assertTrue($Rules->checkValidation('test', ''));
        $this->expectException(CsvValidationException::class);
    }

    /**
     * @expectedException \App\Exceptions\CsvValidationException
     * @test
     */
    public function integerRulesTestWithFloat()
    {
        $Rules = Rules::integer();

        $this->assertTrue($Rules->checkValidation(4.223232, ''));
        $this->expectException(CsvValidationException::class);
    }


    /**
     * @test
     */
    public function integerRulesTestWithNumber()
    {
        $Rules = Rules::integer();

        $this->assertTrue($Rules->checkValidation(42, ''));
    }

    /**
     * @test
     */
    public function floatRulesTestWithFloatNumber()
    {
        $Rules = Rules::float();

        $this->assertTrue($Rules->checkValidation(3.141592653589793238462, ''));
    }

    /**
     * @expectedException \App\Exceptions\CsvValidationException
     * @test
     */
    public function floatRulesTestWithString()
    {
        $Rules = Rules::float();

        $this->assertTrue($Rules->checkValidation('Test String', ''));
        $this->expectException(CsvValidationException::class);
    }
}
