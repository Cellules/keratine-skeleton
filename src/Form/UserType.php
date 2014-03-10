<?php

namespace Form;

use Form\EventListener\AddRolesFieldSubscriber;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;

class UserType extends AbstractType
{
    private $securityContext;

    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // if user is not granted ROLE_SUPER_ADMIN, he can only access to his own profile
        $securityContext = $this->securityContext;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($securityContext) {
            $data = $event->getData();
            $form = $event->getForm();
            $user = $securityContext->getToken()->getUser();
            if (!$user) {
                throw new \LogicException('The UserType cannot be used without an authenticated user!');
            }
            if (false === $securityContext->isGranted('ROLE_SUPER_ADMIN') && $user->getId() !== $data->getId()) {
                throw new AccessDeniedException('This user does not have access to this section.');
            }
        });

        $builder
            ->add('username')
            ->add('email', 'email')
            ->add('password', 'repeated', array(
                'type' => 'password',
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
        ;

        // if user is granted ROLE_SUPER_ADMIN
        if ($this->securityContext->isGranted('ROLE_SUPER_ADMIN'))
        {
            $builder
                // add roles choice input
                ->add('roles', 'choice', array(
                    'choices' => array(
                        'ROLE_SUPER_ADMIN' => 'Super administrateur',
                        'ROLE_ADMIN' => 'Administrateur',
                        'ROLE_USER' => 'Utilisateur'
                    ),
                    'multiple' => true,
                ))
                // add isActive input
                ->add('is_active', 'checkbox')
            ;
        }

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user';
    }
}
