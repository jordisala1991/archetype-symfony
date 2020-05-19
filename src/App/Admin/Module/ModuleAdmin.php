<?php

namespace App\Admin\Module;

use App\Entity\Module\WysiwygModule;
use Runroom\SortableBehaviorBundle\Admin\AbstractSortableAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

final class ModuleAdmin extends AbstractSortableAdmin
{
    public function __construct($code, $class, $baseControllerName)
    {
        parent::__construct($code, $class, $baseControllerName);

        $this->setSubclasses([
            WysiwygModule::TYPE => WysiwygModule::class,
        ]);
    }

    protected function configureRoutes(RouteCollection $collection): void
    {
        parent::configureRoutes($collection);

        if ($this->isChild()) {
            return;
        }

        $collection->clear();
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('template', null, [
                'template' => 'app/admin_template.html.twig',
            ])
            ->add('type', 'trans')
            ->add('publish', 'boolean', [
                'editable' => true,
            ])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                    'move' => [
                        'template' => '@RunroomSortableBehavior/sort.html.twig',
                    ],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper->add('publish');
    }
}
