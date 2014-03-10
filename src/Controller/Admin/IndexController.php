<?php
namespace Controller\Admin;

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
			->bind('dashboard');

		$controllers->get('/help', array($this, 'helpAction'))
			->bind('help');

		return $controllers;
	}


	public function indexAction(Request $request, Application $app)
	{
        $repository = $this->get('orm.em')->getRepository('Gedmo\Loggable\Entity\LogEntry');

        $queryBuilder = $repository->createQueryBuilder('log');

        $queryBuilder->orderBy('log.loggedAt', 'DESC');

        $entities = $queryBuilder->getQuery()->getResult();

		return $app['twig']->render('admin/index.html.twig', array(
            'entities' => $entities,
        ));
	}


	public function helpAction(Request $request, Application $app)
	{
		return $app['twig']->render('admin/help.html.twig', array());
	}

}