<?php
namespace Controller\Admin;

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

		$controllers->get('/{page}', array($this, 'indexAction'))
            ->value('page', 1)
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

        $nbResultsPerPage = 30;
        $firstResult = ($request->get('page', 1) - 1) * $nbResultsPerPage;

        $queryBuilder->setFirstResult($firstResult)
                     ->setMaxResults($nbResultsPerPage);

        // $entities = $queryBuilder->getQuery()->getResult();
        $entities = new Paginator($queryBuilder->getQuery(), $fetchJoinCollection = false);

        // list of associations between classes of entities and routes's prefixes of the application
        $routes = array(
            // 'Entity/Post' => 'post', // example
        );

		return $app['twig']->render('admin/index.html.twig', array(
            'entities' => $entities,
            'nbPages'  => ceil($entities->count() / $nbResultsPerPage),
            'routes'   => $routes,
        ));
	}


	public function helpAction(Request $request, Application $app)
	{
		return $app['twig']->render('admin/help.html.twig', array());
	}

}