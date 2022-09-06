<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CustomerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = $this->getReference(UserFixtures::USER_REFERENCE);
        /** @var User $userTest */
        $userTest = $this->getReference(UserFixtures::USER_REFERENCE . 'Test');
        $customerRepository = $manager->getRepository(Customer::class);
        for ($i = 1; $i <= 5; $i++) {
            $customer = (new Customer())
                ->setEmail("customer$i@example.com")
                ->setName("Customer $i")
                ->setOwner($user)
            ;
            $customerRepository->add($customer);
            $customerTest = (new Customer())
                ->setEmail("customer$i@test.com")
                ->setName("Customer Test $i")
                ->setOwner($userTest)
            ;
            $customerRepository->add($customerTest);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
