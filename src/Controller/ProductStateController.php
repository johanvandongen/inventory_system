<?php

namespace App\Controller;

use App\Entity\ProductState;
use App\Form\ProductStateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductStateController extends AbstractController
{
    #[Route('/product/state/add', name: 'app_product_state')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProductStateType::class);
        $product = new ProductState();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $em->persist($product);
            $em->flush();

            $this->addFlash('succes', 'Product has been added!');

            return $this->redirectToRoute('app_product');
        }

        return $this->render('product_state/index.html.twig', [
            'form' => $form,
        ]);
    }
}
