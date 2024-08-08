<?php
/*
 * Envato API PHP Class
 *
 * This PHP Class was created in order to communicate with the new Envato API.
 *
 * Source: https://github.com/flowdee/envato-api-php-class
 * API Documentation: https://build.envato.com/api/
 *
 * Copyright 2016: flowdee
 */

class Envato {

    // API settings
    private $api_old_url = 'https://api.envato.com/v1/market';
    private $api_new_url = 'https://api.envato.com/v3/market';

    private $api_new_features = array('author', 'buyer');

    // User credentials
    private $api_key;

    // Return type
    private $responseType = 'object';

    # Constructor
    public function __construct($api_key) {

        // Initialize
        $this->api_key = $api_key;
    }

    /**
     * Verify the api credentials and unlock set status
     */
    public function verify_credentials() {

        $response = $this->call('/total-items.json');

        return ( ! isset( $response->error ) && ! isset( $response['error'] ) ) ? true : false;
    }

    /**
     * Set response types
     *
     * @param string $type The type of response, values: array & object (default)
     */
    public function set_response_type( $type ) {

        if ( 'array' === $type )
            $this->responseType = 'array';
    }

    /**
     * Preparing the api call by automatically selecting the correct API version and adding the set
     *
     * @param string $set The url parameters without the basic api url
     * @return mixed The response as object or transformed into an array
     */
    public function call( $set )
    {
        $url = $this->api_old_url;

        foreach ( $this->api_new_features as $feature ) {
            if (strpos($set, '/' . $feature . '/') !== false) {
                $url = $this->api_new_url;
                break;
            }
        }

        $url .= $set;

        // Fetch data
        $response = $this->remote_post($url);

        // Handle return types
        if ( 'array' === $this->responseType )
            return $this->object_to_array( $response );

        // Also returning possible error object/array including the attributes "error" (code) and "description" (message)
        return $response;
    }

    /**
     * General purpose function to query the Envato API.
     *
     * @param string $url The url to access.
     * @return object The results of the request.
     */
    protected function remote_post($url)
    {
        if ( empty($url) ) return false;

        $response = wp_remote_get($url,
		    array(
		        'headers' => array(
		            'Authorization' => "Bearer " . $this->api_key,
		            'User-Agent' => "Mozilla/5.0 (compatible; Envato API Wrapper PHP)"
		        )
		    )
	    );

        $response = json_decode($response['body']);

        return $response; // string or null
    }

    /*
     * Object to Array
     */
    protected function object_to_array($object) {
        return json_decode(json_encode($object), true);
    }

    /*
     * Debugging
     */
    protected function debug($args) {
        echo '<pre>';
        print_r($args);
        echo '</pre>';
    }
}