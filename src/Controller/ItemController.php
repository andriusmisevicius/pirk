<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Item;
use App\Form\CommentType;
use App\Form\ItemType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/items")
 */
class ItemController extends AbstractController
{
    /**
     * @Route("/", name="item_index", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $items= $user->getItems();
        return $this->render('item/index.html.twig', [
            'items' => $items,
        ]);
    }

    /**
     * @Route("/new", name="item_new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item->setAuthor($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('item/new.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_show", methods={"GET","POST"})
     */
    public function show(Item $item, Request $request): Response
    {

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted('create', $comment);
            $comment->setAuthor($this->getUser());
            $comment->setItem($item);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('item_show',[
                'id' => $item->getId(),
            ]);
        }

        return $this->render('item/show.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
            'comments' => $item->getComments(),
            'comment' => $comment,
        ]);



    }

    /**
     * @Route("/{id}/edit", name="item_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Item $item): Response
    {
        $this->denyAccessUnlessGranted('edit', $item);

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('item_index', [
                'id' => $item->getId(),
            ]);
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Item $item): Response
    {
        $this->denyAccessUnlessGranted('delete', $item);

        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('item_index');
    }
}
