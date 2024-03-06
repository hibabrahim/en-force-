<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Entity\Exposees;
use App\Entity\PdfGeneratorService;
use App\Form\ProduitsType;
use App\Repository\ProduitsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/produits')]
class ProduitsController extends AbstractController
{
    #[Route('/', name: 'app_produits_index', methods: ['GET'])]
    public function index(Request $request, ProduitsRepository $produitsRepository): Response
    {
        $sortBy = $request->query->get('sortBy');
    
        switch ($sortBy) {
            case 'prixAscending':
                $produits = $produitsRepository->findBy([], ['prix' => 'ASC']);
                break;
            case 'prixDescending':
                $produits = $produitsRepository->findBy([], ['prix' => 'DESC']);
                break;
            default:
                $produits = $produitsRepository->findAll();
        }
    
        return $this->render('produits/index.html.twig', [
            'produits' => $produits,
        ]);
    }
    
    //-------------------FRONT-------------------
    #[Route('/front', name: 'app_produit_indexFront', methods: ['GET'])]
    public function indexFront(ProduitsRepository $produitsRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $sortBy = $request->query->get('sortBy');
    
        switch ($sortBy) {
            case 'prixAscending':
                $query = $produitsRepository->createQueryBuilder('p')
                    ->orderBy('p.prix', 'ASC')
                    ->getQuery();
                break;
            case 'prixDescending':
                $query = $produitsRepository->createQueryBuilder('p')
                    ->orderBy('p.prix', 'DESC')
                    ->getQuery();
                break;
            default:
                $query = $produitsRepository->createQueryBuilder('p')
                    ->getQuery();
        }
    
        $produits = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Current page number, default 1
            3 // Number of items per page
        );
    
        return $this->render('produits/indexFront.html.twig', [
            'produits' => $produits,
        ]);
    }
    
    //-----------------FIN FRONT------------------
    
    //----------------CODE PDF--------------
    #[Route('/pdf', name: 'generator_serviceProd')]
    public function pdfService(): Response
    { 
        $produits= $this->getDoctrine()
        ->getRepository(Produits::class)
        ->findAll();
        $html =$this->renderView('produits/produitPdf.html.twig', ['produits' => $produits]);
        $pdfGeneratorService=new PdfGeneratorService();
        $pdf = $pdfGeneratorService->generatePdf($html);

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="document.pdf"',
        ]);

    }
    //-------------FIN PDF--------------------
    #[Route('/new', name: 'app_produits_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produits();
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $produit->getRessource();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('uploads'), $filename);
            $produit->setRessource($filename);
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produits/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produits_show', methods: ['GET'])]
    public function show(Produits $produit): Response
    {
        return $this->render('produits/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produits_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produits $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produits/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produits_delete', methods: ['POST'])]
    public function delete(Request $request, Produits $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
    }
}
