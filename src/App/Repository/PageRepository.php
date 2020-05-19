<?php

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

class PageRepository extends ServiceEntityRepository
{
    protected $requestStack;

    public function __construct(
        ManagerRegistry $registry,
        RequestStack $requestStack
    ) {
        parent::__construct($registry, Page::class);

        $this->requestStack = $requestStack;
    }

    public function findBySlug(string $slug): Page
    {
        $request = $this->requestStack->getCurrentRequest();

        $query = $this->createQueryBuilder('page')
            ->leftJoin('page.translations', 'translations', Join::WITH, 'translations.locale = :locale')
            ->where('translations.slug = :slug')
            ->andWhere('page.publish = true')
            ->setParameter('slug', $slug)
            ->setParameter('locale', $request->getLocale())
            ->getQuery();

        return $query->getSingleResult();
    }
}
