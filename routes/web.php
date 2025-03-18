<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/products', 'ProductController@index');
$router->post('/products', 'ProductController@store');
$router->get('/products/{id}', 'ProductController@show');

$router->get('/posts/all', 'PostsController@index');
$router->post('/posts', 'PostsController@store');
$router->put('/posts/{id}', 'PostsController@update');
$router->get('/posts/{id}', 'PostsController@show');
$router->delete('/posts/{id}', 'PostsController@destroy');

$router->get('/posts/{postId}/comments', 'CommentController@index');
$router->post('/posts/{postId}/comments', 'CommentController@store');
$router->get('/comments/{id}', 'CommentController@show');  
$router->get('/posts/{postId}/comments/{id}', 'CommentController@showPostComment');
$router->put('/comments/{id}', 'CommentController@update'); 
$router->put('/posts/{postId}/comments/{id}', 'CommentController@updatePostComment');
$router->delete('/comments/{id}', 'CommentController@destroy');