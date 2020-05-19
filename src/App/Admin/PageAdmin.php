<?php

namespace App\Admin;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Validator\Constraints as Assert;

final class PageAdmin extends AbstractAdmin
{
    protected function configureTabMenu(ItemInterface $menu, $action, ?AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, ['edit', 'show'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');

        $menu->addChild('Edit Page', [
            'uri' => $admin->generateUrl('edit', ['id' => $id])
        ]);

        $menu->addChild('Modules', [
            'uri' => $admin->generateUrl('app.admin.module.list', ['id' => $id])
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('translations.title')
            ->add('publish');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('title')
            ->add('slug')
            ->add('publish')
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('translations', TranslationsType::class, [
                'label' => false,
                'fields' => [
                    'title' => [],
                    'slug' => [
                        'disabled' => true,
                    ],
                ],
                'constraints' => [
                    new Assert\Valid(),
                ],
            ])
            ->add('publish');
    }
}
