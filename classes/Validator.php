<?php

class Validator
{
    protected $rules = [];
    protected $fields = [];
    protected $errors = [];
    protected $validatedData = [];

    /**
     * Run validator
     *
     * @param  array  $data            data to validate
     * @param  array  $rulesAndFields  rules for fields to validate
     * @return object
     */
    public function run(array $data, array $rulesAndFields)
    {
        foreach ($rulesAndFields as $field => $expected) {
            if (empty($expected)) {
                continue;
            }
            // build an array of rules
            $rulesAndValues = explode(',', $expected);

            foreach ($rulesAndValues as $key => $rule) {
                $value = explode('=', $rule);
                // if there are values to compare rule against
                // e.g. 'max_length=10'
                if (count($value) > 1) {
                    // method name e.g. 'max_length' becomes validateMaxLength
                    $ruleMethodName = 'validate' .
                        str_replace(' ', '', (ucwords(str_replace('_', ' ', $value[0]))));
                    // value to compare against e.g. '10'
                    $value = $value[1];
                } else {
                    // method name e.g. 'required' becomes validateRequired
                    $ruleMethodName = 'validate' .
                        str_replace(' ', '', (ucwords(str_replace('_', ' ', $value[0]))));
                    // can add password match here
                    $value = $data[$field];
                }

                if (!call_user_func_array([$this, $ruleMethodName],
                    [$field, $data[$field], $value])) {
                    $this->generateError($ruleMethodName, $field, $value);
                    // throw new Exception($this->getAllValidationErrors()[0]);
                } else {
                    $this->validatedData[$field] = $data[$field];
                }
            }
        }
        return $this;
    }

    /**
     * Error templates
     *
     * @param  string $rule
     * @param  string $field
     * @param  string $expected expected outcome
     * @return void
     */
    protected function generateError($rule, $field, $expected)
    {
        switch ($rule) {
            case 'validateRequired':
                $this->errors[] = $field . ' must not be empty';
                break;
            case 'validateMaxLength':
                $this->errors[] = $field . ' must not be more that ' .
                    $expected . ' characters long';
                break;
            case 'validateMinLength':
                $this->errors[] = $field . ' must not be less that ' .
                    $expected . ' characters';
                break;
            case 'validateAlphaNum':
                $this->errors[] = $field . ' must contain only numbers and letters';
                break;
            case 'validateAlpha':
                $this->errors[] = $field . ' must contain only letters';
                break;
            case 'validateAlphaSpace':
                $this->errors[] = $field . ' must contain only letters and spaces';
                break;
            case 'validateAlphaNumSpace':
                $this->errors[] = $field . ' must contain only letters, numbers and spaces';
                break;
            default:
                $this->errors[] = $field . ' is invalid';
                break;
        }
    }

    /**
     * Check if there are any validation errors
     *
     * @return boolean
     */
    public function hasErrors()
    {
        return (!empty($this->errors)) ? true : false;
    }

    /**
     * Get validation errors
     *
     * @return array
     */
    public function getAllValidationErrors()
    {
        return $this->errors;
    }

    /**
     * Get validated data
     *
     * @return array
     */
    public function getValidatedData()
    {
        return $this->validatedData;
    }

    protected function validateRequired($field, $input, $expected = null)
    {
        return !empty($input) ? true : false;
    }

    protected function validateMaxLength($field, $input, $expected)
    {
        return mb_strlen($input) <= $expected ? true : false;
    }

    protected function validateMinLength($field, $input, $expected)
    {
        return mb_strlen($input) >= $expected ? true : false;
    }

    protected function validateAlphaNum($field, $input, $expected = null)
    {
        return ctype_alnum($input) === true ? true : false;
    }

    protected function validateAlphaNumSpace($field, $input, $expected = null)
    {
        return preg_match('/([^a-zA-Z0-9 ])/', $input) ? false : true;
    }

    protected function validateAlpha($field, $input, $expected = null)
    {
        return ctype_alpha($input) === true ? true : false;
    }

    protected function validateAlphaSpace($field, $input, $expected = null)
    {
        return preg_match('/([a-zA-Z ]+)/', $input);
    }

    protected function validateNumeric($field, $input, $expected = null)
    {
        return is_numeric($input);
    }

    protected function validateEmail($field, $input, $expected = null)
    {
        if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }
}
