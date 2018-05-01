<?php

//Routes

$application->get('/posts/', \ApplicationController::class . ':getPosts');
$application->get('/details/{post_id}', \ApplicationController::class . ':getPostDetail');


