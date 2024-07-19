<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ProductRepository $products, CategoryRepository $categories): Response
    {
        return $this->render('home/index.html.twig', [
            'products' => $products->findAllOneState(),
            'categories' => $categories->findAll(),
        ]);
    }
}
