<?php

namespace Runroom\AdminUserBundle\Form\Type;

use Runroom\AdminUserBundle\Security\RolesBuilder\MatrixRolesBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class RolesMatrixType extends AbstractType
{
    private $rolesBuilder;

    public function __construct(MatrixRolesBuilder $rolesBuilder)
    {
        $this->rolesBuilder = $rolesBuilder;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'expanded' => true,
            'choices' => function (Options $options, $parentChoices): array {
                if (!empty($parentChoices)) {
                    return [];
                }

                $roles = $this->rolesBuilder->getRoles();
                $roles = \array_keys($roles);

                return \array_combine($roles, $roles);
            },
            'data_class' => null,
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'user_roles_matrix';
    }
}
