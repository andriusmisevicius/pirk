<?php

namespace App\Controller;

use App\Repository\ItemCategoryRepository;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ItemRepository $itemRepository, ItemCategoryRepository $categories, Request $request)
    {

        return $this->render('home/index.html.twig', [
            'items' => $itemRepository->sortByNewest(),
            'categories' => $categories->findAll(),
        ]);
    }
}
