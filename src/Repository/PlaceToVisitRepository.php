<?php

namespace App\Repository;

use App\Entity\PlaceToVisit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlaceToVisit>
 *
 * @method PlaceToVisit|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlaceToVisit|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlaceToVisit[]    findAll()
 * @method PlaceToVisit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaceToVisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlaceToVisit::class);
    }

    public function index(PlaceToVisitRepository $placeToVisitRepository, Request $request): Response
    {
        $searchQuery = $request->query->get('q');

        if ($searchQuery) {
            $places = $placeToVisitRepository->search($searchQuery);
        } else {
            $places = $placeToVisitRepository->findAll();
        }

        return $this->render('place_to_visit/index.html.twig', [
            'place_to_visits' => $places,
            'searchQuery' => $searchQuery,
        ]);
    }

  //  public function countReviewsForDestinations()
   // {
   //     return $this->createQueryBuilder('p')
  //          ->leftJoin('p.reviews', 'r')
  //          ->select('p.id, COUNT(r.id) as reviewCount')
  //          ->groupBy('p.id')
  //          ->getQuery()
 //           ->getResult();
   // }


//    /**
//     * @return PlaceToVisit[] Returns an array of PlaceToVisit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PlaceToVisit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
