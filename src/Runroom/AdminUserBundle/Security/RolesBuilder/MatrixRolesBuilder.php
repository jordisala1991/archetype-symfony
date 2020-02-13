<?php

namespace Runroom\AdminUserBundle\Security\RolesBuilder;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class MatrixRolesBuilder
{
    private $tokenStorage;
    private $adminRolesBuilder;
    private $securityRolesBuilder;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AdminRolesBuilder $adminRolesBuilder,
        SecurityRolesBuilder $securityRolesBuilder
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->adminRolesBuilder = $adminRolesBuilder;
        $this->securityRolesBuilder = $securityRolesBuilder;
    }

    public function getRoles(): array
    {
        if (!$this->tokenStorage->getToken()) {
            return [];
        }

        return \array_merge(
            $this->securityRolesBuilder->getRoles(),
            $this->adminRolesBuilder->getRoles()
        );
    }

    public function getPermissionLabels(): array
    {
        return $this->adminRolesBuilder->getPermissionLabels();
    }
}
