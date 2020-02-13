<?php

namespace Runroom\AdminUserBundle\Twig;

use Runroom\AdminUserBundle\Security\RolesBuilder\MatrixRolesBuilder;
use Symfony\Component\Form\FormView;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class RolesMatrixExtension extends AbstractExtension
{
    private $matrixRolesBuilder;

    public function __construct(MatrixRolesBuilder $matrixRolesBuilder)
    {
        $this->matrixRolesBuilder = $matrixRolesBuilder;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('renderMatrix', [$this, 'renderMatrix'], ['needs_environment' => true]),
            new TwigFunction(
                'renderRolesList',
                [$this, 'renderRolesList'],
                ['needs_environment' => true]
            ),
        ];
    }

    public function renderRolesList(Environment $environment, FormView $form): string
    {
        $roles = $this->matrixRolesBuilder->getRoles();
        foreach ($roles as $role => $attributes) {
            if (isset($attributes['admin_label'])) {
                unset($roles[$role]);
                continue;
            }

            $roles[$role] = $attributes;
            foreach ($form->getIterator() as $child) {
                if ($child->vars['value'] === $role) {
                    $roles[$role]['form'] = $child;
                }
            }
        }

        return $environment->render('security/roles_matrix_list.html.twig', [
            'roles' => $roles,
        ]);
    }

    public function renderMatrix(Environment $environment, FormView $form): string
    {
        $groupedRoles = [];
        foreach ($this->matrixRolesBuilder->getRoles() as $role => $attributes) {
            if (!isset($attributes['admin_label'])) {
                continue;
            }

            $groupedRoles[$attributes['admin_label']][$role] = $attributes;
            foreach ($form->getIterator() as $child) {
                if ($child->vars['value'] === $role) {
                    $groupedRoles[$attributes['admin_label']][$role]['form'] = $child;
                }
            }
        }

        return $environment->render('security/roles_matrix.html.twig', [
            'grouped_roles' => $groupedRoles,
            'permission_labels' => $this->matrixRolesBuilder->getPermissionLabels(),
        ]);
    }
}
