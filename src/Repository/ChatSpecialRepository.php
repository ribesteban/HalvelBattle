<?php

namespace App\Repository;

use App\Entity\ChatSpecial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ChatSpecial|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChatSpecial|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChatSpecial[]    findAll()
 * @method ChatSpecial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatSpecialRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ChatSpecial::class);
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
