<?php

namespace App\Controller;

use App\Entity\PlaceToVisit;
use App\Form\PlaceToVisitType;
use App\Repository\PlaceToVisitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/place/to/visit')]
class PlaceToVisitController extends AbstractController
{
    #[Route('/', name: 'app_place_to_visit_index', methods: ['GET'])]
    public function index(PlaceToVisitRepository $placeToVisitRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $searchQuery = $request->query->get('q');
        $queryBuilder = $placeToVisitRepository->createQueryBuilder('p');

        if ($searchQuery) {
            $queryBuilder
                ->where('p.nom_lieu LIKE :searchQuery')
                ->orWhere('p.description LIKE :searchQuery')
                ->orWhere('p.address LIKE :searchQuery')
                ->orWhere('p.cordonnee_geo LIKE :searchQuery')
                ->orWhere('p.destination LIKE :searchQuery')
                ->setParameter('searchQuery', '%' . $searchQuery . '%');
        }

        $query = $queryBuilder->getQuery();
        $pagination = $paginator->paginate(
            $query, // Query to paginate
            $request->query->getInt('page', 1), // Page number
            3 // Limit per page
        );
     //   $destinationsWithReviewCount = $placeToVisitRepository->countReviewsForDestinations();

        return $this->render('place_to_visit/index.html.twig', [
            'pagination' => $pagination,
            'searchQuery' => $searchQuery,

        ]);
    }
    #[Route('/Front', name: 'app_place_to_visit_front', methods: ['GET'])]
    public function frontList(PlaceToVisitRepository $placeToVisitRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $searchQuery = $request->query->get('q');
        $queryBuilder = $placeToVisitRepository->createQueryBuilder('p');

        if ($searchQuery) {
            $queryBuilder
                ->where('p.nom_lieu LIKE :searchQuery')
                ->orWhere('p.description LIKE :searchQuery')
                ->orWhere('p.address LIKE :searchQuery')
                ->orWhere('p.cordonnee_geo LIKE :searchQuery')
                ->orWhere('p.destination LIKE :searchQuery')
                ->setParameter('searchQuery', '%' . $searchQuery . '%');
        }

        $query = $queryBuilder->getQuery();
        $pagination = $paginator->paginate(
            $query, // Query to paginate
            $request->query->getInt('page', 1), // Page number
            10 // Limit per page
        );
        //   $destinationsWithReviewCount = $placeToVisitRepository->countReviewsForDestinations();

        return $this->render('place_to_visit/listFront.html.twig', [
            'pagination' => $pagination,
            'searchQuery' => $searchQuery,

        ]);
    }
    #[Route('/new', name: 'app_place_to_visit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $placeToVisit = new PlaceToVisit();
        $form = $this->createForm(PlaceToVisitType::class, $placeToVisit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($placeToVisit);
            $entityManager->flush();

            return $this->redirectToRoute('app_place_to_visit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('place_to_visit/new.html.twig', [
            'place_to_visit' => $placeToVisit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_place_to_visit_show', methods: ['GET'])]
    public function show(PlaceToVisit $placeToVisit): Response
    {
        return $this->render('place_to_visit/show.html.twig', [
            'place_to_visit' => $placeToVisit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_place_to_visit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PlaceToVisit $placeToVisit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlaceToVisitType::class, $placeToVisit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_place_to_visit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('place_to_visit/edit.html.twig', [
            'place_to_visit' => $placeToVisit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_place_to_visit_delete', methods: ['POST'])]
    public function delete(Request $request, PlaceToVisit $placeToVisit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$placeToVisit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($placeToVisit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_place_to_visit_index', [], Response::HTTP_SEE_OTHER);
    }
}
