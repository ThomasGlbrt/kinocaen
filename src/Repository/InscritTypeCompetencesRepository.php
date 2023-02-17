<?php

namespace App\Repository;

use App\Entity\InscritTypeCompetences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InscritTypeCompetences>
 *
 * @method InscritTypeCompetences|null find($id, $lockMode = null, $lockVersion = null)
 * @method InscritTypeCompetences|null findOneBy(array $criteria, array $orderBy = null)
 * @method InscritTypeCompetences[]    findAll()
 * @method InscritTypeCompetences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscritTypeCompetencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InscritTypeCompetences::class);
    }

    public function save(InscritTypeCompetences $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InscritTypeCompetences $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return InscritTypeCompetences[] Returns an array of InscritTypeCompetences objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InscritTypeCompetences
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
