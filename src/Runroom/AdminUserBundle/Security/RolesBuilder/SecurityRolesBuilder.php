<?php

namespace Runroom\AdminUserBundle\Security\RolesBuilder;

use Sonata\AdminBundle\Admin\Pool;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class SecurityRolesBuilder
{
    private $authorizationChecker;
    private $pool;
    private $rolesHierarchy;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        Pool $pool,
        array $rolesHierarchy = []
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->pool = $pool;
        $this->rolesHierarchy = $rolesHierarchy;
    }

    public function getExpandedRoles(): array
    {
        $securityRoles = [];
        foreach ($hierarchy = $this->getHierarchy() as $role => $childRoles) {
            $securityRoles[$role] = [
                'role' => $role,
                'is_granted' => $this->authorizationChecker->isGranted($role),
            ];

            $securityRoles = \array_merge(
                $securityRoles,
                $this->getSecurityRoles($hierarchy, $childRoles)
            );
        }

        return $securityRoles;
    }

    public function getRoles(): array
    {
        $securityRoles = [];
        foreach ($hierarchy = $this->getHierarchy() as $role => $childRoles) {
            $securityRoles[$role] = $this->getSecurityRole($role);
            $securityRoles = \array_merge(
                $securityRoles,
                $this->getSecurityRoles($hierarchy, $childRoles)
            );
        }

        return $securityRoles;
    }

    private function getHierarchy(): array
    {
        return \array_merge([
            $this->pool->getOption('role_super_admin') => [],
            $this->pool->getOption('role_admin') => [],
        ], $this->rolesHierarchy);
    }

    private function getSecurityRole(string $role): array
    {
        return [
            'role' => $role,
            'is_granted' => $this->authorizationChecker->isGranted($role),
        ];
    }

    private function getSecurityRoles(array $hierarchy, array $roles): array
    {
        $securityRoles = [];
        foreach ($roles as $role) {
            if (!\array_key_exists($role, $hierarchy) && !isset($securityRoles[$role])
                && !$this->recursiveArraySearch($role, $securityRoles)) {
                $securityRoles[$role] = $this->getSecurityRole($role);
            }
        }

        return $securityRoles;
    }

    private function recursiveArraySearch(string $role, array $roles): bool
    {
        foreach ($roles as $key => $value) {
            if ($role === $key || (\is_array($value) && true === $this->recursiveArraySearch($role, $value))) {
                return true;
            }
        }

        return false;
    }
}
