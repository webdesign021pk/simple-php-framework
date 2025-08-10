<?php

/**
 * Simple Validator class for basic form validation. 
 * Provides rules such as required, email, min, max, etc.
 * app/config/Validator.php
 * 
 * @package     App
 * @author      rrafiq
 * @version     1.0.0
 * @license     MIT
 */

if (php_sapi_name() !== 'cli' && basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    http_response_code(403);
    exit("403 Forbidden");
}

class Validator
{
    // These properties track state during validation
    private $errors = [];  // Stores all validation errors
    private $data;         // Stores the input data to validate
    private $pdo; // PDO instance
    private $validated = [];  // Store only validated & allowed fields


    // 1. Constructor - Initialize with data to validate
    public function __construct(array $data)
    {
        // global $pdo;
        $pdo = DB::connection();
        $this->data = $data;
        $this->pdo = $pdo;
    }

    // 2. Main validation method
    public function validate(array $rulesArray): bool
    {
        foreach ($rulesArray as $field => $ruleString) {
            // $value = $this->data[$field] ?? null;

            // $rules = explode('|', $ruleString[0]); // Convert "required|integer|max:50" to array

            // Convert rule to array whether it's string or array
            $rules = is_array($ruleString) ? explode('|', $ruleString[0]) : explode('|', $ruleString);


            $isSometimes = in_array('sometimes', $rules);
            $fieldExists = array_key_exists($field, $this->data);

            // Skip if 'sometimes' is set and field doesn't exist in input
            if ($isSometimes && !$fieldExists) {
                continue;
            }

            $value = $this->data[$field] ?? null;

            foreach ($rules as $rule) {
                $parts = explode(':', $rule);
                $ruleName = $parts[0];
                $ruleValue = $parts[1] ?? null;

                // Handle required check
                if ($ruleName === 'required') {
                    if ($value === null || $value === '') {
                        $this->addError($field, "The $field field is required");
                        continue 2; // Skip other rules for this field
                    }
                    continue; // Move to next rule
                }

                // Skip validation if value is empty (and not required)
                // Only skip if value is null or empty string (but allow 0)
                if (($value === null || $value === '') && !in_array('required', $rules, true)) {
                    continue;
                }
                // if (empty($value)) continue;


                // Validate based on rule type
                $this->validateRule($field, $value, $ruleName, $ruleValue);
            }

            // If no error added for this field, it's valid
            if (!isset($this->errors[$field])) {
                $this->validated[$field] = $value;
            }
        }

        return empty($this->errors);
    }

    private function validateRule(string $field, $value, string $ruleName, $ruleValue = null)
    {
        switch ($ruleName) {
            case 'integer':
                if (!is_numeric($value) || (int)$value != $value) {
                    $this->addError($field, "The $field must be an integer");
                }
                break;

            case 'string':
                if (!is_string($value)) {
                    $this->addError($field, "The $field must be a string");
                }
                break;

            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "The $field must be a valid email address");
                }
                break;

            case 'date':
                if (!strtotime($value)) {
                    $this->addError($field, "The $field must be a valid date");
                }
                break;

            case 'max':
                if (strlen($value) > $ruleValue) {
                    $this->addError($field, "The $field may not be greater than $ruleValue characters");
                }
                break;

            case 'min':
                if (strlen($value) < $ruleValue) {
                    $this->addError($field, "The $field must be at least $ruleValue characters");
                }
                break;

            case 'unique':
                $this->validateUnique($field, $value, $ruleValue);
                break;

            case 'exists':
                $this->validateExists($field, $value, $ruleValue);
                break;

                // Add more rules as needed
        }
    }

    private function validateUnique(string $field, $value, string $ruleValue)
    {
        if (!$this->pdo) {
            throw new RuntimeException("PDO instance required for unique validation");
        }

        // Parse rule (format: "table,column,ignore_id,ignore_column")
        $parts = explode(',', $ruleValue);
        $table = $parts[0] ?? '';
        $column = $parts[1] ?? $field;
        $ignoreId = $parts[2] ?? null;
        $ignoreColumn = $parts[3] ?? 'id'; // Default to the same column

        // Build query
        $sql = "SELECT COUNT(*) FROM {$table} WHERE {$column} = ?";
        $params = [$value];

        // Add ignore clause if provided
        if ($ignoreId !== null) {
            $sql .= " AND {$ignoreColumn} != ?";
            $params[] = $ignoreId;
        }

        $stmt = $this->pdo->prepare($sql);

        // $stmt->execute([$value]);
        $stmt->execute($params);


        if ($stmt->fetchColumn() > 0) {
            $this->addError($field, "The $field already exists in $table");
        }
    }

    private function validateExists(string $field, $value, string $ruleValue)
    {
        if (!$this->pdo) {
            throw new RuntimeException("PDO instance required for exists validation");
        }

        [$table, $column] = array_pad(explode(',', $ruleValue), 2, $field);

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM {$table} WHERE {$column} = ?");
        $stmt->execute([$value]);

        if ($stmt->fetchColumn() === 0) {
            $this->addError($field, "The selected $field is invalid");
        }
    }

    // 4. Helper to record errors
    private function addError(string $field, string $message)
    {
        $this->errors[$field] = $message;
    }

    // 5. Get validation results
    public function getErrors(): array
    {
        return $this->errors;
    }

    // 6. Get cleaned data
    public function getValidatedData(): array
    {
        return $this->validated;
        // return $this->data;
    }
}
