# iSklad API Connector #

The iSklad API Connector library enables you to communicate with iSklad API. 

API documentation: [isklad/egon-api-documentation](https://github.com/isklad/egon-api-documentation)

## Requirements ##
* [PHP 7.4 or higher](https://www.php.net/)

## Installation ##

You can use **Composer** or simply **Download the Release**

### Composer

The preferred method is via [composer](https://getcomposer.org/). Follow the
[installation instructions](https://getcomposer.org/doc/00-intro.md) if you do not already have
composer installed.

Once composer is installed, execute the following command in your project root to install this library:

```sh
composer require zoltanlaca/isklad-api-connector
```

Finally, be sure to include the autoloader:

```php
require_once '/path/to/your-project/vendor/autoload.php';
```

### Download the Release

If you prefer not to use composer, you can download the package in its entirety. The [Releases](https://github.com/zoltanlaca/isklad-api-connector/releases) page lists all stable versions.

Uncompress the zip file you download, and include the autoloader in your project:

```php
require_once '/path/to/isklad-api-connector/vendor/autoload.php';
```

## Examples ##
See the [`examples/`](examples) directory for examples of the key features.

### Basic Example ###

```php
// import classes
use ZoltanLaca\IskladApiConnector\Connector;
use ZoltanLaca\IskladApiConnector\ConnectorException;

// include composer autoload file
include_once dirname(__DIR__) . '/vendor/autoload.php';

// create connector instance
$connector = New Connector(123456, 'xxx', 'xxx');

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
```
