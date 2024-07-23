<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'app_categories')]
    public function index(CategoryRepository $categories): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categories->findAll(),
        ]);
    }

    #[Route('/category/{category}/edit', name: 'app_category_edit')]
    public function oneCategory(EntityManagerInterface $em, CategoryRepository $categories, Category $category, Request $request): Response
    {

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $em->persist($category);
            $em->flush();

            $this->addFlash('succes', 'Category has been updated!');

            return $this->redirectToRoute('app_categories', ['categories', $categories->findAll()]);
        }
        
        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'categories' => $categories->findAll(),
            'form' => $form,
        ]);
    }

    #[Route('/category/{category}/delete', name: 'app_category_delete')]
    public function delete(EntityManagerInterface $em, Category $category, Request $request, CategoryRepository $categories): Response
    {
        $em->remove($category);
        $em->flush();
        return $this->render('category/index.html.twig', [
            'categories' => $categories->findAll(),
        ]);
    }

    #[Route('/category/add', name: 'app_category_add')]
    public function addCategory(EntityManagerInterface $em, Request $request, CategoryRepository $categories): Response
    {

        $form = $this->createForm(CategoryType::class);

        $form->handleRequest(($request));
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $em->persist($category);
            $em->flush();

            $this->addFlash('succes', 'Category has been added!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('category/add.html.twig', [
            'form' => $form,
            'categories' => $categories->findAll(),
        ]);
    }
}
