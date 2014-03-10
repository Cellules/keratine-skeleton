<?php
namespace Controller\Admin;

use Keratine\Controller\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class LoginController extends Controller
{

	public function connect(Application $app)
	{
		$controllers = $app['controllers_factory'];

		$controllers->get('/login', array($this, 'loginAction'))
			->bind('login');

		return $controllers;
	}


	public function loginAction(Request $request, Application $app)
	{
	    return $app['twig']->render('admin/login.html.twig', array(
	        'error'         => $app['security.last_error']($request),
	        'last_username' => $app['session']->get('_security.last_username'),
	    ));
	}

}