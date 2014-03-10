<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

// Homepage route
$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig', array());
})
->bind('homepage');

// Default admin routes
$app->mount('/', new Controller\Admin\LoginController());
$app->mount('/admin', new Controller\Admin\IndexController());
$app->mount('/admin/user', new Controller\Admin\UserController());
$app->mount('/admin/search', new Controller\Admin\SearchController());

// Admin Tools
$app->mount('/admin/tool/finder', new Controller\Tool\FinderController());
$app->mount('/admin/tool/manager', new Controller\Tool\ManagerController());

// Custom routes
// $app->mount('/admin/hello', new Controller\Admin\HelloController()); // Sample route


// Error routes
$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});