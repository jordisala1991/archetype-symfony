<?php

namespace App\Controller;

use App\Entity\MenuItem;
use App\Entity\Page;
use App\Repository\MenuItemRepository;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PageController extends AbstractController
{
    protected $menuItemRepository;
    protected $pageRepository;

    public function __construct(
        MenuItemRepository $menuItemRepository,
        PageRepository $pageRepository
    ) {
        $this->menuItemRepository = $menuItemRepository;
        $this->pageRepository = $pageRepository;
    }

    public function index(string $slug): Response
    {
        $menu = $this->menuItemRepository->getTree('/main');
        $page = $this->pageRepository->findBySlug($slug);

        return $this->render('app/page/index.html.twig', [
            'page' => $page,
            'menu' => $menu,
        ]);
    }
}
