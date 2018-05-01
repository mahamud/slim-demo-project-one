<?php

namespace App\controllers;
use App\exception\ConflictException;
use App\helper\Functions;
use App\libraries\session\Session;
use App\middleware\LoggerMiddleWare;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
//use \Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;


/**
 * Class BaseController
 * @property Session $session
 * @property ServerRequestInterface $request
 * @property ResponseInterface $response
 * @property LoggerMiddleWare $logger
 * @property ContainerInterface $container
 * @property PhpRenderer $renderer
 * @property Manager $db
 * @package App\controllers
 *
 */

class BaseController
{

    /**
     * Core variable declaration
     *
     * @var
     */
    protected $container;
    protected $session;
    protected $response;
    protected $request;
    protected $offset;
    protected $limit;
    protected $logger;
    protected $events;
    protected $renderer;
    protected $db;


    /**
     * BaseController constructor.
     * @param $container
     */
    public function __construct($container){
        $this->container = $container;
        $this->session = $this->container->session;
        $this->response = $this->container->response;
        $this->request = $this->container->request;
        $this->logger = $this->container->logger;
        $this->renderer = $this->container->renderer;
        $this->db = $this->container->database;
    }


    /**
     * @param $property
     * @return mixed
     */
    public function __get($property){
        if ($this->container->{$property}) {
            return $this->container->{$property};
        }
    }


    /**
     * Method to initiate offset and limit
     */
    protected function setOffsetAndLimit(){
        $parameters = $this->request->getQueryParams();
        $this->offset = !empty($parameters['offset']) ? (int)filter_var($parameters['offset'], FILTER_SANITIZE_NUMBER_INT) : 0;
        if((!empty($parameters['limit']) && (int)$parameters['limit'] <= DEFAULT_DB_LIMIT) ){
            $this->limit = (int)filter_var($parameters['limit'], FILTER_SANITIZE_NUMBER_INT);
        }else{
            $this->limit = DEFAULT_DB_LIMIT;
        }
    }


    /**
     * @param $method
     * @param $data
     * @throws ConflictException
     */
    protected function validateRequest($method, $data){
        $sessionKey = $this->session->id().'_'.$method;
        $exists = $this->session->exists($sessionKey);
        if($exists){
            $sessionData = $this->session->get($sessionKey);
            if($sessionData === Functions::safeSerialize($data)){
                throw new ConflictException(DUPLICATE_REQUEST_ERROR_MESSAGE);
            }
        }
        $this->session->set($sessionKey, Functions::safeSerialize($data));
    }


}