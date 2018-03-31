<?php

namespace App\Repository;

use App\Entity\Amis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Amis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Amis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Amis[]    findAll()
 * @method Amis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AmisRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Amis::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('a')
            ->where('a.something = :value')->setParameter('value', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
