<?php
namespace Controller\Front;

use Doctrine\ORM\Tools\Pagination\Paginator;

use Keratine\Controller\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends Controller
{
    public function connect(Application $app)
    {
        $this->container = $app;

        $controllers = $app['controllers_factory'];

        $controllers->get('/', array($this, 'indexAction'))
            ->bind('homepage');

        return $controllers;
    }


    public function indexAction(Request $request, Application $app)
    {
        return $app['twig']->render('front/index.html.twig', array());
    }
}