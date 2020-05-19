<?php

namespace App\Repository;

use App\Entity\MenuItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\DoctrineBehaviors\ORM\Tree\TreeTrait;

class MenuItemRepository extends ServiceEntityRepository
{
    use TreeTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuItem::class);
    }
}
