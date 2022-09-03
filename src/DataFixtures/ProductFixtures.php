<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $product = (new Product())
                ->setBrand("Brand $i")
                ->setModel("Model $i")
                ->setDescription("Description $i")
                ->setPrice($i * 100)
            ;
            $manager->persist($product);
        }

        $manager->flush();
    }
}