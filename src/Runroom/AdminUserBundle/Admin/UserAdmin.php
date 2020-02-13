<?php

namespace Runroom\AdminUserBundle\Admin;

use Runroom\AdminUserBundle\Form\Type\RolesMatrixType;
use Runroom\AdminUserBundle\Security\UserManipulator;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserAdmin extends AbstractAdmin
{
    private $userManipulator;

    public function setUserManipulator(UserManipulator $userManipulator)
    {
        $this->userManipulator = $userManipulator;
    }

    public function preUpdate($object)
    {
        $this->userManipulator->hashPassword($object);
    }

    public function prePersist($object)
    {
        $this->userManipulator->setCreatedAt($object);
        $this->userManipulator->hashPassword($object);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('email')
            ->add('enabled');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('email')
            ->add('enabled', null, [
                'editable' => true,
            ])
            ->add('createdAt')
            ->add('lastLogin')
            ->add('_action', 'actions', [
                'actions' => [
                    'delete' => [],
                    'impersonate' => [
                        'template' => 'security/impersonate_user.html.twig',
                    ],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $user = $this->getSubject();

        $formMapper
            ->with('General', [
                'class' => 'col-md-4',
                'box_class' => 'box box-solid box-primary',
            ])
                ->add('email')
                ->add('plainPassword', TextType::class, [
                    'required' => !$user || \is_null($user->getId()),
                ])
                ->add('enabled')
            ->end()
            ->with('Roles', [
                'class' => 'col-md-8',
                'box_class' => 'box box-solid box-primary',
            ])
                ->add('roles', RolesMatrixType::class, [
                    'label' => false,
                    'required' => false,
                    'multiple' => true,
                ])
            ->end();
    }
}
