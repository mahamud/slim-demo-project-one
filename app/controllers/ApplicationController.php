<?php

namespace App\controllers;

use App\models\ContentModel;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


/**
 * Class ApplicationController
 * @package App\controllers
 */
class ApplicationController extends BaseController
{

    /**
     * ApplicationController constructor.
     * @param $container
     */
    public function __construct($container){
        parent::__construct($container);
    }


    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface $response PSR7 response
     * @param array $arguments
     * @return Response
     */
    public function getPosts(Request $request, Response $response, Array $arguments){
        $contentModel = new ContentModel($this->container);
        try {
            $posts = $contentModel->getPosts();
            return $this->renderer->render($response, 'posts.phtml', [
                'posts' => $posts
            ]);
        }
        catch(\Exception $exception){
            return $this->renderer->render($response, 'error.phtml', [
                'error' => $exception->getMessage()
            ]);
        }
    }


    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface $response PSR7 response
     * @param array $arguments
     * @return Response
     */
    public function getPostDetail(Request $request, Response $response, Array $arguments){
        $contentModel = new ContentModel($this->container);
        $postid = filter_var($arguments['post_id'], FILTER_SANITIZE_STRING);
        try {
            $post = $contentModel->getPostDetail($postid);
            return $this->renderer->render($response, 'detail.phtml', [
                'post' => $post
            ]);
        }
        catch(\Exception $exception){
            return $this->renderer->render($response, 'error.phtml', [
                'error' => $exception->getMessage()
            ]);
        }
    }

}