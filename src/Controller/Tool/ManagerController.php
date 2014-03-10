<?php
namespace Controller\Tool;

use Keratine\Controller\Controller;

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ManagerController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $this->container = $app;

        $controllers = $app['controllers_factory'];

        return $controllers;
    }
}