<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Person::class);
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function findRandomPersons()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id IN (:ids)')
            ->setParameter('ids', [
                random_int(1, 358355),
                random_int(1, 358355),
                random_int(1, 358355),
                random_int(1, 358355),
                random_int(1, 358355),
                random_int(1, 358355),
                random_int(1, 358355),
                random_int(1, 358355),
                random_int(1, 358355),
                random_int(1, 358355),
                random_int(1, 358355),
                random_int(1, 358355),
                random_int(1, 358355),
                random_int(1, 358355),
                random_int(1, 358355),
            ]) // 15 random ids
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
}
