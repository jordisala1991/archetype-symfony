<?php

namespace App\Admin\Module;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use App\Entity\Module\WysiwygModule;
use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints as Assert;

class WysiwygModuleAdminExtension extends AbstractAdminExtension
{
    public function configureFormFields(FormMapper $formMapper)
    {
        $class = $formMapper->getAdmin()->getClass();
        if ($class != WysiwygModule::class) {
            return;
        }

        $formMapper->add('translations', TranslationsType::class, [
            'label' => false,
            'fields' => [
                'text' => [],
            ],
            'constraints' => [
                new Assert\Valid(),
            ],
        ]);
    }
}
