<?php
/**
 * Overrides defined here
 */

$container = $application->getContainer();


//Adding the renderer to the Container
$container['renderer'] = function ($container) {
    $renderer = $container->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($renderer['template_path']);
};


//Override the default 'Not Found Handler'
$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        $output = array(
            'status' => 'Error',
            'message' => NOT_FOUND_MESSAGE
        );
        return $container['response']
            ->withStatus(HTTP_STATUS_NOT_FOUND)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($output));
    };
};

//Override the default 'Not Allowed Handler'
$container['notAllowedHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        $output = array(
            'status' => 'Error',
            'message' => NOT_ALLOWED_MESSAGE
        );
        return $container['response']
            ->withStatus(HTTP_STATUS_NOT_ALLOWED)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($output));
    };
};


//Custom Error Handler
$container['errorHandler'] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        $response->getBody()->rewind();
        \App\helper\Functions::logException($exception); //Logging of the exception
        $output = array(
            'status' => 'Error',
            'message' => APPLICATION_ERROR_MESSAGE
        );
        return $container['response']
            ->withStatus(HTTP_STATUS_INTERNAL_SERVER_ERROR)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($output));
    };
};


//Custom PHP RunTime Error Handler - Applicable only for PHP 7.x
$container['phpErrorHandler'] = function ($container) {
    return $container['errorHandler'];
};


//Adding database instance to container
$container['database'] = function ($container) { //Container for core database
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['database']);
    $capsule->setFetchMode(PDO::FETCH_ASSOC); //By default will return array for result set
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    //var_dump($capsule);
    return $capsule;
};


//Adding queue
$container['queue'] = function ($container) { //Container for core queue
    /* */
};


//Adding logger to container
$container['logger'] = function() {
    return new \App\middleware\LoggerMiddleWare();
};


//Session Helper
$container['session'] = function () {
    return new \App\libraries\session\Helper();
};


$container['ApplicationController'] = function ($container) {
    return new \App\controllers\ApplicationController($container);
};

