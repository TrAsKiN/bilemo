<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';

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
        $manager->getRepository(User::class)->add($user);

        $userTest = (new User())
            ->setEmail('test@example.com')
            ->setCompanyName('Test Company')
            ->setSiren(87654321)
        ;
        $userTest->setPassword($this->passwordHasher->hashPassword($userTest, 'example'));
        $manager->getRepository(User::class)->add($userTest);

        $manager->flush();
        $this->addReference(self::USER_REFERENCE, $user);
        $this->addReference(self::USER_REFERENCE . 'Test', $userTest);
    }
}
