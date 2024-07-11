<?php

namespace App\Repository;

use App\Entity\VieDossier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VieDossier>
 *
 * @method VieDossier|null find($id, $lockMode = null, $lockVersion = null)
 * @method VieDossier|null findOneBy(array $criteria, array $orderBy = null)
 * @method VieDossier[]    findAll()
 * @method VieDossier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VieDossierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VieDossier::class);
    }

    //    /**
    //     * @return VieDossier[] Returns an array of VieDossier objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?VieDossier
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
