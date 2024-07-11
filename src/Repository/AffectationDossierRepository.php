<?php

namespace App\Repository;

use App\Entity\AffectationDossier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AffectationDossier>
 *
 * @method AffectationDossier|null find($id, $lockMode = null, $lockVersion = null)
 * @method AffectationDossier|null findOneBy(array $criteria, array $orderBy = null)
 * @method AffectationDossier[]    findAll()
 * @method AffectationDossier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AffectationDossierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AffectationDossier::class);
    }

    //    /**
    //     * @return AffectationDossier[] Returns an array of AffectationDossier objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AffectationDossier
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
