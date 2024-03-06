<?php

namespace App\Repository;

use App\Entity\Exposees;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exposees>
 *
 * @method Exposees|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exposees|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exposees[]    findAll()
 * @method Exposees[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExposeesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exposees::class);
    }

//    /**
//     * @return Exposees[] Returns an array of Exposees objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Exposees
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function SortByNomExpo(){
    return $this->createQueryBuilder('e')
        ->orderBy('e.nom_e','ASC')
        ->getQuery()
        ->getResult()
        ;
}
public function findBydateDebut( $date_debut)
{
    return $this-> createQueryBuilder('e')
        ->andWhere('e.date_debut LIKE :date_debut')
        ->setParameter('date_debut','%' .$date_debut. '%')
        ->getQuery()
        ->execute();
}
public function findBydateFin( $date_fin)
{
    return $this-> createQueryBuilder('e')
        ->andWhere('e.date_fin LIKE :date_fin')
        ->setParameter('dateFin','%' .$date_fin. '%')
        ->getQuery()
        ->execute();
}
// public function searchExposees($optionsearch, $searchValue): array
// {
//     switch ($optionsearch) {
//         case 'Nom_e':
//             return $this->createQueryBuilder('e')
//                 ->andWhere('e.nom_e LIKE :searchValue')
//                 ->setParameter('searchValue', '%' . $searchValue . '%')
//                 ->getQuery()
//                 ->getResult();
//             break;

//         case 'date_debut':
//             return $this->createQueryBuilder('e')
//                 ->andWhere('e.date_debut LIKE :searchValue')
//                 ->setParameter('searchValue', '%' . $searchValue . '%')
//                 ->getQuery()
//                 ->getResult();
//             break;

//         case 'date_fin':
//             return $this->createQueryBuilder('e')
//                 ->andWhere('e.date_fin LIKE :searchValue')
//                 ->setParameter('searchValue', '%' . $searchValue . '%')
//                 ->getQuery()
//                 ->getResult();
//             break;

//         default:
//             return []; // Return an empty array if the optionsearch is invalid
//     }
// }
}
