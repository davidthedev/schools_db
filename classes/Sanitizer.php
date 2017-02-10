<?php

class Sanitizer
{
    protected $filteredData = [];
    protected $unfilteredData = [];

    /**
     * Run filter / sanitizer
     *
     * @param  array $data
     * @param  array $filtersAndFields
     * @return object
     */
    public function run($data, $filtersAndFields)
    {
        $this->unfilteredData = $data;

        foreach ($filtersAndFields as $field => $params) {
            if (!isset($data[$field])) {
                continue;
            }

            $filters = explode(',', $params);

            foreach ($filters as $key => $filter) {
                $filtered = $this->$filter($data[$field]);
                $data[$field] = $filtered;
            }

            $this->filteredData[$field] = $data[$field];
        }

        return $this;
    }

    /**
     * Get filtered / sanitized results + any data that does not require filtering
     *
     * @return array
     */
    public function getFilteredData()
    {
        foreach ($this->unfilteredData as $key => $value) {
            if (!array_key_exists($key, $this->filteredData)) {
                $this->filteredData[$key] = $value;
            }
        }
        return $this->filteredData;
    }

    /**
     * Trim string
     *
     * @param  string $data
     * @return string
     */
    protected function trim($data)
    {
        return trim($data);
    }

    /**
     * Sanitize string
     *
     * @param  string $data
     * @return string
     */
    protected function sanitize($data)
    {
        return filter_var($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    /**
     * Sanitize email
     *
     * @param  string $data
     * @return string
     */
    protected function email($data)
    {
        return filter_var($data, FILTER_SANITIZE_EMAIL);
    }
}
