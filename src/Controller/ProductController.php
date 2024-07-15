<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(ProductRepository $products, CategoryRepository $categories): Response
    {

        return $this->render('product/index.html.twig', [
            'products' => $products->findAll(),
        ]);
    }

    #[Route('/product/{product}', name: 'app_product_one')]
    public function oneProduct(Product $product): Response
    {

        return $this->render('product/oneProduct.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/product/add', name: 'app_product_add', priority: 2)]
    public function addProduct(EntityManagerInterface $em, Request $request, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProductType::class);
        $product = new Product();
        $form->handleRequest(($request));
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('productImage')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move($this->getParameter('products_directory'), $newFilename);
                } catch (FileException $e) {
                    // ...
                }
            }


            // $product = $form->getData();
            $product->setImage($newFilename);
            $em->persist($product);
            $em->flush();

            $this->addFlash('succes', 'Product has been added!');

            return $this->redirectToRoute('app_product');
        }

        return $this->render('product/add.html.twig', [
            'form' => $form,
        ]);
    }
}
