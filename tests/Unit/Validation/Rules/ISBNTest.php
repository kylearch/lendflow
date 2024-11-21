<?php

namespace Tests\Unit\Validation\Rules;


use Illuminate\Support\Facades\Validator;
use App\Rules\ISBN;
use Tests\TestCase;

class ISBNTest extends TestCase
{
    public function testValidISBNs()
    {
        // 10 digit ISBN as a single string
        $validator = Validator::make(['isbn' => '0140278583'], ['isbn' => new ISBN]);
        $this->assertTrue($validator->passes());

        // 13 digit ISBN as a single string
        $validator = Validator::make(['isbn' => '9780140278583'], ['isbn' => new ISBN]);
        $this->assertTrue($validator->passes());

        // 10 and 13 digit ISBNs in an array
        $validator = Validator::make(['isbn' => ['0140278583', '9780140278583']], ['isbn' => new ISBN]);
        $this->assertTrue($validator->passes());

        // Leading and trailing whitespace
        $validator = Validator::make(['isbn' => ['0140278583 ', ' 9780140278583']], ['isbn' => new ISBN]);
        $this->assertTrue($validator->passes());

        // Valid variations
        $validator = Validator::make(['isbn' => [
            '125079997X',
            '0-14-027858-3',
            '978-0-14-027858-3',
            '0 14 027858 3',
            '978 0 14 027858 3',
            '0.14.027858.3',
        ]], ['isbn' => new ISBN]);
        $this->assertTrue($validator->passes());
    }

    public function testInvalidISBNs()
    {
        // 9 digit ISBN
        $validator = Validator::make(['isbn' => '014027858'], ['isbn' => new ISBN]);
        $this->assertFalse($validator->passes());

        // 11 digit ISBN
        $validator = Validator::make(['isbn' => '01402785833'], ['isbn' => new ISBN]);
        $this->assertFalse($validator->passes());
    }
}
