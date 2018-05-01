<?php
/**
 * All system configurations, environment specific variables to be defined here.
 *
 */

// Configure Error Reporting
error_reporting(E_ALL);

// Configure Session properties - php-ini settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.hash_function', 'sha512');

session_cache_limiter(false);


// Configure Application Settings IF required
$settings = array('settings' => [
    'displayErrorDetails' => true, // set to false in production
    'addContentLengthHeader' => false, // Allow the web server to send the content-length header,
    'database' => [
        'driver' => 'mysql', //getenv('DBTYPE')
        'host' => 'ews-test.cnmj82dw3dge.ap-southeast-1.rds.amazonaws.com', //getenv('DBHOST')
        'database' => 'ews_test_application', //getenv('DBNAME')
        'username' => 'test123456', //getenv('DBUSERNAME')
        'password' => '123456test', //getenv('DBPASSWORD'),
        'charset' => 'utf8',
        'collation' => 'utf8_general_ci',
        'port' => '3306', //getenv('DBPORT')
        'strict' => false,
    ],
    'renderer' => [
        'template_path' => BASE_PATH.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR,
    ],
]);

