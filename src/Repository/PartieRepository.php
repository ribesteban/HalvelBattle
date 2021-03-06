<?php

namespace App\Repository;

use App\Entity\Partie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Partie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partie[]    findAll()
 * @method Partie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartieRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Partie::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('p')
            ->where('p.something = :value')->setParameter('value', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function findPartiesJoueur($joueur)
    {
        return $this->createQueryBuilder('p')
            ->where('p.j1 = :val')
            ->orWhere('p.j2 = :val')
            ->setParameter('val', $joueur)
            ->getQuery()
            ->getResult();
    }

    public function findPartiesProposition($joueur)
    {
        return $this->createQueryBuilder('p')
            ->where('p.j2 = :val')
            ->andWhere('p.vainqueur = 0')
            ->setParameter('val', $joueur)
            ->getQuery()
            ->getResult();
    }
}
