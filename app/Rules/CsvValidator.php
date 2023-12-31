<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class CsvValidator implements Rule {

    private $csvData;
    private $rules;
    private $headingRow;
    private $errors;
    private $headingKeys = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) {
        //
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        return 'The validation error message.';
    }

}
