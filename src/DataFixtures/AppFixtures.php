<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product->setName("Skateboard");
        $product->setQuantity(1);

        $category = new Category();
        $category->setName("skateboard");
        $manager->persist($product);
        $manager->persist($category);

        $manager->flush();
    }
}
