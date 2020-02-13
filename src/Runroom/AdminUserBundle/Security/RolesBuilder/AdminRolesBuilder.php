<?php

namespace Runroom\AdminUserBundle\Security\RolesBuilder;

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\Pool;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class AdminRolesBuilder
{
    private $authorizationChecker;
    private $pool;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        Pool $pool
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->pool = $pool;
    }

    public function getPermissionLabels(): array
    {
        $permissionLabels = [];
        foreach ($this->getRoles() as $attributes) {
            if (isset($attributes['label'])) {
                $permissionLabels[$attributes['label']] = $attributes['label'];
            }
        }

        return $permissionLabels;
    }

    public function getRoles(): array
    {
        $adminRoles = [];
        foreach ($this->pool->getAdminServiceIds() as $id) {
            $admin = $this->pool->getInstance($id);
            $securityHandler = $admin->getSecurityHandler();
            $baseRole = $securityHandler->getBaseRole($admin);
            foreach (\array_keys($admin->getSecurityInformation()) as $key) {
                $role = \sprintf($baseRole, $key);
                $adminRoles[$role] = [
                    'role' => $role,
                    'label' => $key,
                    'is_granted' => $this->isMaster($admin) || $this->authorizationChecker->isGranted($role),
                    'admin_label' => $admin->getLabel(),
                ];
            }
        }

        return $adminRoles;
    }

    private function isMaster(AdminInterface $admin): bool
    {
        return $admin->isGranted('MASTER') || $admin->isGranted('OPERATOR')
            || $this->authorizationChecker->isGranted($this->pool->getOption('role_super_admin'));
    }
}
