<?php
declare(strict_types=1);

// import classes
use ZoltanLaca\IskladApiConnector\Connector;
use ZoltanLaca\IskladApiConnector\ConnectorException;

// include composer autoload file
include_once dirname(__DIR__) . '/vendor/autoload.php';

// create connector instance
$connector = New Connector('123456', 'xxx', 'xxx');

try {
    $response = $connector
        // set the input data to the request
        ->createRequest('GetOrderStatus', [
            'original_order_id' => 123,
        ])
        // send to api
        ->send() // SSL is not verified in development @localhost
        // get parsed response from connector
        ->getResponseHeaders();

        // print it
        print_r($response);
} catch (ConnectorException $exception) {
    // handle error
    print_r(sprintf('Connection ERROR: %s', $exception->getMessage()));
}


