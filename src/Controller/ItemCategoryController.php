<?php

namespace App\Controller;

use App\Entity\ItemCategory;
use App\Form\ItemCategoryType;
use App\Repository\ItemCategoryRepository;
use App\Repository\ItemRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/item/category")
 */
class ItemCategoryController extends AbstractController
{
    /**
     * @Route("/{id}", name="item_category_index", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function index(ItemRepository $itemRepository, ItemCategory $category, ItemCategoryRepository $itemCategoryRepository): Response
    {
        $id = $category;
        return $this->render('item_category/index.html.twig', [
            'items' => $itemRepository->findByCategory($id),
            'categories' => $itemCategoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="item_category_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $itemCategory = new ItemCategory();
        $form = $this->createForm(ItemCategoryType::class, $itemCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($itemCategory);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('item_category/new.html.twig', [
            'item_category' => $itemCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_category_show", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(ItemCategory $itemCategory): Response
    {
        return $this->render('item_category/show.html.twig', [
            'item_category' => $itemCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="item_category_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, ItemCategory $itemCategory): Response
    {
        $form = $this->createForm(ItemCategoryType::class, $itemCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home', [
                'id' => $itemCategory->getId(),
            ]);
        }

        return $this->render('item_category/edit.html.twig', [
            'item_category' => $itemCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_category_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, ItemCategory $itemCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$itemCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($itemCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }
}
