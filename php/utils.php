<?php

// utility contants


// server error error
const ERR_SERVER_ERROR = 'Server Error';
// missing data error
const ERR_MISSING_DATA = 'Missing Data';
// duplicate data error
const ERR_DUPLICATE_DATA = 'Duplicate Data';
// not found data error
const ERR_NOT_FOUND = 'Object Not Found';
// no error
const ERR_EMPTY = FALSE;


// utility functions


if (!function_exists('input_post')) 
{
        /**
         * get field from the post array
         * @param string $field_name
         * @return mixed false if field is empty or not exists, otherwise field value
         */
        function input_post($field_name) 
        {
                if (isset($_POST[$field_name]) && !empty($_POST[$field_name])) 
                {
                        return $_POST[$field_name];
                }

                return FALSE;
        }

}

if (!function_exists('input_get')) 
{
        /**
         * get field from the get array
         * @param string $field_name
         * @return mixed false if field is empty or not exists, otherwise field value
         */
        function input_get($field_name) 
        {
                if (isset($_GET[$field_name]) && !empty($_GET[$field_name])) 
                {
                        return $_GET[$field_name];
                }

                return FALSE;
        }

}

if (!function_exists('output_json')) 
{
        /**
         * format output in json form
         * @param boolean $response_status response status
         * @param string $response_errors response error message
         * @param array $response_data response data
         * @return string json_encoded output string
         */
        function output_json($response_status = FALSE, $response_errors = FALSE, $response_data = FALSE) 
        {
                return json_encode(array(
                            'status' => $response_status,
                            'errorMessage' => $response_errors,
                            'data' => $response_data
                        ));
        }

}

/* End of file utils.php */
/* Location ./utils.php */