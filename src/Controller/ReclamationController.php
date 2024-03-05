<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/search', name: 'app_search', methods: ['GET','POST'])]
    public function search(Request $request,ReclamationRepository $reclamationRepository): Response
    {
        $search=$request->request->get('search');
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->search($search),
        ]);
    }
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }
    #[Route('/ExportPdf', name: 'app_reclamation_pdf', methods: ['GET', 'POST'])]
    public function ExportPdf(ReclamationRepository $rep) :Response
    {
          $recs=$rep->findAll();
          $options = new Options();
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $html = $this->renderView('reclamation/pdf.html.twig', [
            // Pass any necessary data to your Twig template
            'recs' => $recs,
        ]);

        $dompdf->loadHtml($html);

        // (Optional) Set paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to browser (inline view)
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
    #[Route('/Front', name: 'app_reclamation_index_front', methods: ['GET'])]
    public function indexFront(PaginatorInterface $paginator,ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager,Request $request): Response
    {
        $reclamations=$reclamationRepository->findByidUser(1);
       
        $pagination = $paginator->paginate(
            $reclamations,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('reclamation/indexFront.html.twig', [
            'reclamations' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request,PaginatorInterface $paginator, EntityManagerInterface $entityManager,ReclamationRepository $reclamationRepository): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $count = $reclamationRepository->countRecentReclamations(1, 3);
            if ($count >= 3) {
                // Redirect the user back to the new reclamation page with an error message
                $this->addFlash('error', 'You have already submitted the maximum number of reclamations allowed in the last 3 days.');
                $reclamations=$reclamationRepository->findByidUser(1);
       
                $pagination = $paginator->paginate(
                    $reclamations,
                    $request->query->getInt('page', 1),
                    3
                );
                return $this->redirectToRoute('app_reclamation_index_front', [
                    'reclamations' => $pagination], Response::HTTP_SEE_OTHER
              );
            }
            $reclamation->setEtat("Not Treated");
            $d=new \DateTimeImmutable();
            $reclamation->setCreatedAt($d);
            $reclamation->setIdUser(1);
            $cleaned=\ConsoleTVs\Profanity\Builder::blocker($reclamation->getDescription())->filter();
            $reclamation->setDescription($cleaned);
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation,ReponseRepository $reprepo): Response
    {
        $reponse = new Reponse();
        $reponse=$reprepo->findByRec($reclamation->getId());
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
            'reponse'=>$reponse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }
       $check=$request->request->get('front');
       if($check==1)
       {
        return $this->redirectToRoute('app_reclamation_index_front', [], Response::HTTP_SEE_OTHER);
       }
       else
        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
  
}
