<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Product;
use App\Entity\ProductState;
use App\Form\AddProductType;
use App\Form\ImageType;
use App\Form\ProductStateType;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;
use DateTime;
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
            'products' => $products->findAllOneState(),
        ]);
    }

    #[Route('/product/{product}', name: 'app_product_one')]
    public function oneProduct(Product $product): Response
    {

        return $this->render('product/oneProduct.html.twig', [
            'product' => $product,
            'images' => array_map(fn($p) => $p->getPath(),  $product->getImage()->toArray())
        ]);
    }

    // #[Route('/product/{product}/{idx}', name: 'app_product_one_idx')]
    // public function oneProductIdx(int $idx, Product $product): Response
    // {

    //     return $this->render('product/oneProduct.html.twig', [
    //         'product' => $product,
    //         'idx' => $idx,
    //     ]);
    // }

    #[Route('/product/{product}/edit', name: 'app_product_edit')]
    public function edit(EntityManagerInterface $em, Request $request, SluggerInterface $slugger, Product $product, ProductRepository $products, ImageRepository $images): Response
    {
        // Product
        $editProductForm = $this->createForm(ProductType::class, $product);
        $editProductForm->handleRequest($request);
        if ($editProductForm->isSubmitted() && $editProductForm->isValid()) {
            
            /** @var UploadedFile $imageFile */
            // $imageFile = $editProductForm->get('productImage')->getData();
            // if ($imageFile) {
            //     $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            //     $safeFilename = $slugger->slug($originalFilename);
            //     $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
            //     try {
            //         $imageFile->move($this->getParameter('products_directory'), $newFilename);
            //     } catch (FileException $e) {
            //         // ...
            //     }
            //     $product->setImage($newFilename);
            // }

            $em->persist($product);
            $em->flush();

            $this->addFlash('succes', 'Product has been added!');

            return $this->redirectToRoute('app_product');
        }

        // Product State
        /** @var Product $productStates */
        $productsWithMostRecentState = $products->findProductMostRecentState($product->getId());
        $productState = count($productsWithMostRecentState) > 0 ? $productsWithMostRecentState[0]->getState()[0] : new ProductState();

        $editStateForm = $this->createForm(ProductStateType::class, $productState);
        $editStateForm->handleRequest($request);
        if ($editStateForm->isSubmitted() && $editStateForm->isValid()) {
            $productState->setDate(new DateTime());
            $productState->setProduct($product);
            $em->persist($productState);
            $em->flush();

            $this->addFlash('succes', 'Product state has been updated!');

            return $this->redirectToRoute('app_product_edit', ['product' => $product->getId()]);
        }

        // Product Image
        $productsMostRecentImage = $images->findProductMostRecentImage($product->getId());
        $productsMostRecentImage = count($productsMostRecentImage) > 0 ? $productsMostRecentImage[0] : null;
        $productImage = new Image();
        $editImageForm = $this->createForm(ImageType::class, $productImage);
        $editImageForm->handleRequest($request);
        if ($editImageForm->isSubmitted() && $editImageForm->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $editImageForm->get('path')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move($this->getParameter('products_directory'), $newFilename);
                } catch (FileException $e) {
                    // ...
                }
                $productImage->setPath($newFilename);
                $productImage->setProduct($product);
                $productImage->setCreatedAt(new DateTime());
                $productImage->setPriority(0);
                $em->persist($productImage);
                $em->flush();
                $this->addFlash('succes', 'Product state has been updated!');
            }

            return $this->redirectToRoute('app_product_edit', ['product' => $product->getId()]);
        }

        return $this->render('product/edit.html.twig', [
            'editProductForm' => $editProductForm,
            'editStateForm' => $editStateForm,
            'editImageForm' => $editImageForm,
            'product' => $product,
            'image' => $productsMostRecentImage,
        ]);
    }

    #[Route('/product/add', name: 'app_product_add', priority: 2)]
    public function addProduct(EntityManagerInterface $em, Request $request, SluggerInterface $slugger): Response
    {
        $product = new Product();
        $product->setQuantity(1);
        $productState = new ProductState();
        $product->getState()->add($productState);
        $form = $this->createForm(AddProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move($this->getParameter('products_directory'), $newFilename);
                } catch (FileException $e) {
                    // ...
                }
                $productImage = new Image();
                $productImage->setPath($newFilename);
                $productImage->setProduct($product);
                $productImage->setCreatedAt(new DateTime());
                $productImage->setPriority(0);
                $em->persist($productImage);
            }

            $productState->setDate(new DateTime());
            $productState->setProduct($product);

            $em->persist($productState);
            $em->persist($product);
            $em->flush();

            $this->addFlash('succes', 'Product has been added!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/product/{product}/delete', name: 'app_product_delete')]
    public function deleteProduct(EntityManagerInterface $em, Request $request, Product $product): Response
    {
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('app_home');
    }
}
