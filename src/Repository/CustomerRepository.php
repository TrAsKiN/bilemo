<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Customer>
 *
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public const MAX_PER_PAGE = 5;

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Customer::class);
    }

    public function add(Customer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Customer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getPaginateCustomers(UserInterface $user, int $page, int $limit = self::MAX_PER_PAGE): Paginator
    {
        $offset = ($page - 1) * $limit;
        $query = $this->createQueryBuilder('c')
            ->where('c.owner = :user')
            ->setParameter('user', $user)
            ->orderBy('c.id')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
        ;
        return new Paginator($query);
    }
}
