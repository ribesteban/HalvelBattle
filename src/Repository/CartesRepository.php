<?php

namespace App\Repository;

use App\Entity\Cartes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Cartes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cartes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cartes[]    findAll()
 * @method Cartes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cartes::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('c')
            ->where('c.something = :value')->setParameter('value', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
