<?php
namespace Controller\Admin;

use Keratine\Controller\CrudController;

use Silex\Application;
use Silex\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends CrudController
{

	/**
     * {@inheritdoc}
     */
	protected function getRoutePrefix()
	{
		return 'user';
	}

	/**
     * {@inheritdoc}
     */
	protected function getEntityClass()
	{
		return 'Entity\User';
	}

	/**
     * {@inheritdoc}
     */
	protected function getColumns()
	{
		return array(
			'username' => array(
				'label' => 'Identifiant'
			),
			'email' => array(
				'label' => 'Email'
			),
			'roles' => array(
				'label' => 'Rôles',
				'widget' => new \Keratine\Widget\Set(array('data' => array(
                    'ROLE_SUPER_ADMIN' => 'Super administrateur',
                    'ROLE_ADMIN'       => 'Administrateur',
                    'ROLE_USER'        => 'Utilisateur'
                )))
			),
			'isActive' => array(
				'label' => 'Actif',
				'widget' => new \Keratine\Widget\Boolean()
			)
		);
	}

	/**
     * {@inheritdoc}
     */
	protected function getType()
	{
		return new \Form\UserType($this->get('security'));
	}


    public function indexAction()
    {
        // restrict acess to ROLE_SUPER_ADMIN
        if (false === $this->get('security')->isGranted('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return parent::indexAction();
    }


	public function createAction(Request $request)
    {
        $entityClass = $this->getEntityClass();
        $entity = new $entityClass;

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
        	// encode password
        	$this->get('users')->setUserPassword($entity, $entity->getPassword());

            $this->get('orm.em')->persist($entity);
            $this->get('orm.em')->flush();

            return $this->redirect($this->generateUrl($this->getRoutePrefix()));
        }

        return $this->get('twig')->render('admin/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }


    public function updateAction(Request $request, $id)
    {
        $entity = $this->get('orm.em')->find($this->getEntityClass(), $id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find entity.');
        }

        $editForm = $this->createEditForm($entity);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
        	// encode password
        	$this->get('users')->setUserPassword($entity, $entity->getPassword());

            $this->get('orm.em')->flush();

            return $this->redirect($this->generateUrl($this->getRoutePrefix() . '_edit', array('id' => $id)));
        }

        return $app['twig']->render('admin/edit.html.twig',  array(
            'entity' => $entity,
            'form'   => $editForm->createView()
        ));
    }

}