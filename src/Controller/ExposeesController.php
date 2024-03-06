<?php

namespace App\Controller;

use App\Entity\Exposees;
use App\Entity\Produits;
use App\Form\ExposeesType;
use App\Entity\PdfGeneratorService;
use App\Repository\ExposeesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/exposees')]
class ExposeesController extends AbstractController
{

    #[Route('/', name: 'app_exposees_index', methods: ['GET','POST'])]
    public function index(ExposeesRepository $exposeesRepository,EntityManagerInterface $entityManager, Request $request ): Response
    {
        $exposees = $entityManager
        ->getRepository(Exposees::class)
        ->findAll();
        $back = null;
        if($request->isMethod("POST")){
            if ( $request->request->get('optionsRadios')){
                $SortKey = $request->request->get('optionsRadios');
                switch ($SortKey){
                    case 'nom_e':
                        $exposees = $exposeesRepository->SortByNomExpo();
                        break;
                }
            }
            else
            {
                $type = $request->request->get('optionsearch');
                $value = $request->request->get('Search');
                switch ($type){

                    case 'dateDebut':
                        $exposees = $exposeesRepository->findBydateDebut($value);
                        break;

                    case 'dateFin':
                        $exposees = $exposeesRepository->findBydateFin($value);
                        break;
                }
            }

            if ( $exposees){
                $back = "success";
            }else{
                $back = "failure";
            }
        }
        return $this->render('exposees/index.html.twig', [
            'exposees' => $exposees,
        ]);    
    }
    //------------Page Front--------------
    #[Route('/front', name: 'app_exposee_Front_page', methods: ['GET'])]
    public function Front_page(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
        $exposees = $entityManager
        ->getRepository(Exposees::class)
        ->findAll();

            $exposees = $paginator->paginate(
            $exposees, /* query NOT result */
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('exposees/Front_page.html.twig', [
            'exposees' => $exposees,
        ]);

    }
    
    //----------------FIN-------------------- 
    
    //----------------CODE PDF---------------
    #[Route('/pdf', name: 'generator_serviceExpo')]
    public function pdfService(): Response
    { 
        $exposees= $this->getDoctrine()
        ->getRepository(Exposees::class)
        ->findAll();
        $html =$this->renderView('exposees/exposeePdf.html.twig', ['exposees' => $exposees]);
        $pdfGeneratorService=new PdfGeneratorService();
        $pdf = $pdfGeneratorService->generatePdf($html);

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="document.pdf"',
        ]);

    }
    //-------------FIN PDF--------------------
    #[Route('/new', name: 'app_exposees_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $exposee = new Exposees();
        $form = $this->createForm(ExposeesType::class, $exposee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $exposee->getImageExposees();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('uploads'), $filename);
            $exposee->setImageExposees($filename);

            $entityManager->persist($exposee);
            $entityManager->flush();

            return $this->redirectToRoute('app_exposees_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('exposees/new.html.twig', [
            'exposee' => $exposee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_exposees_show', methods: ['GET'])]
    public function show(Exposees $exposee): Response
    {
        return $this->render('exposees/show.html.twig', [
            'exposee' => $exposee,
        ]);
    }
    //-----------SHOW FRONT----------------
    #[Route('/{id}', name: 'app_exposees_showF', methods: ['GET'])]
    public function showF(Exposees $exposee): Response
    {
        return $this->render('produits/show.html.twig', [
            'exposee' => $exposee,
        ]);
    }
    //-----------FIN SHOW FRONT-------------
    #[Route('/{id}/edit', name: 'app_exposees_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Exposees $exposee, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExposeesType::class, $exposee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_exposees_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('exposees/edit.html.twig', [
            'exposee' => $exposee,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_exposees_delete', methods: ['POST'])]
    public function delete(Request $request, Exposees $exposee, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exposee->getId(), $request->request->get('_token'))) {
            $entityManager->remove($exposee);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_exposees_index', [], Response::HTTP_SEE_OTHER);
    }
}
