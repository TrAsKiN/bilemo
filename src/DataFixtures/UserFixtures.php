<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }
    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setEmail('moi@traskin.net')
            ->setCompanyName('My Company')
            ->setSiren(12345678)
        ;
        $user->setPassword($this->passwordHasher->hashPassword($user, 'test'));
        $manager->persist($user);

        $manager->flush();
    }
}
