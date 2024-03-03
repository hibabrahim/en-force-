<?php

namespace App\Controller;
use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Repository\RatingRepository;
use App\Entity\Rating;

#[Route('/reponse')]
class ReponseController extends AbstractController
{
    #[Route('/', name: 'app_reponse_index', methods: ['GET'])]
    public function index(ReponseRepository $reponseRepository): Response
    {
        return $this->render('reponse/index.html.twig', [
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

    #[Route('/{id}/new', name: 'app_reponse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,Reclamation $reclamation): Response
    {

        $reponse = new Reponse();
        $reclamation->setEtat("Waiting");
        $entityManager->flush();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reponse->setIdRec($reclamation);
            $date= new \DateTimeImmutable();
            $reponse->setCreatedAt($date);
            $reponse->SetIdUser(1);
            
            $entityManager->persist($reponse);
            $entityManager->flush();
            $reclamation->setEtat("Done");
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse/new.html.twig', [
            'id'=>$reclamation,
            'reclamation'=>$reclamation,
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reponse_show', methods: ['GET','POST'])]
    public function show(Reponse $reponse,RatingRepository $ratingrepo,Request $request): Response
    {
        
       
        $check=0;
      
       
        $rating = new Rating();
    $form = $this->createFormBuilder($rating)
        ->add('note', ChoiceType::class, [
            'label' => 'Rating',
            'choices' => [
                '1 star' => 1,
                '2 stars' => 2,
                '3 stars' => 3,
                '4 stars' => 4,
                '5 stars' => 5,
            ],
            'expanded' => true,
            'multiple' => false,
        ])
        ->getForm();
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        // Save the rating
        $rating->setUser(1);
        $rating->setIdAdmin(1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($rating);
        $entityManager->flush();

        // Redirect to the same page to prevent form resubmission
        return $this->redirectToRoute('app_reponse_show', ['id' => $reponse->getId()]);
    }
    if($reponse){
        $check=1;
    $ratings = $ratingrepo->createQueryBuilder('r')
        ->select('AVG(r.note) as average_rating')
        ->andWhere('r.idAdmin = :id')
        ->setParameter('id', $reponse->getIdUser())
        ->getQuery()
        ->getSingleScalarResult();
    $averageRating =round($ratings, 1);
    return $this->render('reponse/show.html.twig', [
        'id' => $reponse->getId(),
        'reponse'=>$reponse,
        'check'=>$check,
        
        'average_rating' => $averageRating,
        'rating_form' => $form->createView(),
    ]);
    }
        return $this->render('reponse/show.html.twig', [
            'id' => $reponse->getId(),
            'reponse' => $reponse,
            
            'rating_form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reponse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
        $reclamation=$reponse->getIdRec();
        $date=$reponse->getCreatedAt();
       

        if ($form->isSubmitted() && $form->isValid()) {
            $reponse->setIdRec($reclamation);
          
            $reponse->setCreatedAt($date);
            $reponse->SetIdUser(1);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse/edit.html.twig', [
            'id'=>$reclamation->getId(),
            'reclamation'=>$reclamation,
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }

    #[Route('/Delete/{id}', name: 'app_reponse_delete', methods: ['POST'])]
    public function delete(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}
